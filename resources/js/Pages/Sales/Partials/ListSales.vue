<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import { usePage, router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { useToast } from "vue-toastification";
import Swal from "sweetalert2";

const props = defineProps({
    sales: Object,
    cashFloat: Object,
    shifts: Object,
    // Arrives as a collection (array) from the backend via ->get()
    roomServiceConfig: Array,
});

const user = usePage().props.auth.user;
const cashFloatAmount = Number(props.cashFloat?.amount ?? 0);

const canGenerateCutOff = computed(() =>
    user?.permissions?.includes("generate cutoff") ?? false
);

const selectedDate = ref("");
const selectedShift = ref("");
const openModal = ref(false);

const showModal = () => { openModal.value = true; };
const closeModal = () => { openModal.value = false; };

// ─── Formatting ──────────────────────────────────────────────────────────────

const formatCurrency = (amount) =>
    new Intl.NumberFormat("es-MX", { style: "currency", currency: "MXN" }).format(amount);

const formatQuantityPrice = (quantity, unitPrice) =>
    `${quantity} x ${formatCurrency(unitPrice)}`;

const formatTotal = (quantity, unitPrice) => formatCurrency(quantity * unitPrice);

// ─── Room Service ─────────────────────────────────────────────────────────────

const isRoomServiceActive = computed(() =>
    Array.isArray(props.roomServiceConfig) &&
    props.roomServiceConfig.length > 0 &&
    !!props.roomServiceConfig[0].is_active
);

const serviceCostPercent = computed(() =>
    isRoomServiceActive.value ? (props.roomServiceConfig[0].service_cost ?? 0) : 0
);

// ─── Total Calculation ────────────────────────────────────────────────────────

function getTotalsFromSales(sales) {
    const nonCourtesy = (sales ?? []).filter((s) => !s.is_courtesy);

    const baseTotal = nonCourtesy.reduce(
        (sum, s) => sum + s.quantity * s.unit_price,
        0
    );

    const serviceTotal = nonCourtesy
        .filter((s) => s.is_room)
        .reduce(
            (sum, s) => sum + s.quantity * s.unit_price * (serviceCostPercent.value / 100),
            0
        );

    return { baseTotal, serviceTotal, totalWithService: baseTotal + serviceTotal };
}

/**
 * Fixed tip (order.tip): flat amount per ORDER. Deduplicated by order ID to avoid
 * counting it once per sale line item instead of once per order.
 */
function getTipsFixed(sales) {
    const seen = new Set();
    return (sales ?? []).reduce((total, sale) => {
        if (
            sale.order &&
            !seen.has(sale.order.id) &&
            sale.order.tip != null &&
            sale.order.tip !== ""
        ) {
            seen.add(sale.order.id);
            return total + parseFloat(sale.order.tip);
        }
        return total;
    }, 0);
}

/**
 * Percentage tip (order.tip_percent): applied per line item, which is mathematically
 * equivalent to applying it once to the order total (sum of parts = whole).
 * No per-order deduplication required.
 */
function getTipsPercent(sales) {
    return (sales ?? []).reduce((sum, sale) => {
        const pct = sale.order?.tip_percent ? parseFloat(sale.order.tip_percent) : 0;
        return sum + sale.quantity * sale.unit_price * (pct / 100);
    }, 0);
}

function getTotalTips(sales) {
    return getTipsFixed(sales) + getTipsPercent(sales);
}

/**
 * BUG FIX: the previous implementation called getTipsFixed([single_sale]) in a forEach,
 * which bypassed order-ID deduplication and counted order.tip once per sale item
 * instead of once per order — inflating tips by N× (N = items in the order).
 *
 * Correct approach: filter by payment method first, then delegate to the functions
 * that already handle deduplication correctly on the full filtered array.
 */
function getTotalTipsCard(sales) {
    const cardSales = (sales ?? []).filter((s) => s.payment_method === "Tarjeta");
    return getTotalTips(cardSales);
}

function getTotalTipsCash(sales) {
    const cashSales = (sales ?? []).filter((s) => s.payment_method === "Efectivo");
    return getTotalTips(cashSales);
}

// ─── Date Helpers ─────────────────────────────────────────────────────────────

function parseMySQLDate(str) {
    return new Date((str ?? "").replace(" ", "T"));
}

function getFirstDate(sales) {
    if (!sales?.length) return null;
    return new Date(Math.min(...sales.map((s) => parseMySQLDate(s.date_time).getTime())));
}

function getLastDate(sales) {
    if (!sales?.length) return null;
    return new Date(Math.max(...sales.map((s) => parseMySQLDate(s.date_time).getTime())));
}

function formatDateToMySQL(date) {
    return (
        [
            date.getFullYear(),
            String(date.getMonth() + 1).padStart(2, "0"),
            String(date.getDate()).padStart(2, "0"),
        ].join("-") +
        " " +
        [
            String(date.getHours()).padStart(2, "0"),
            String(date.getMinutes()).padStart(2, "0"),
            String(date.getSeconds()).padStart(2, "0"),
        ].join(":")
    );
}

// ─── Backend Sales Fetch ──────────────────────────────────────────────────────

async function fetchSalesForCutOff(shiftName, dateStr) {
    const response = await axios.get(route("Sales/ForCutOff"), {
        params: { date: dateStr, shift: shiftName },
    });
    return Array.isArray(response.data) ? response.data : [];
}

// ─── Cash Audit Build ─────────────────────────────────────────────────────────

async function buildCashAuditData() {
    if (!selectedDate.value || !selectedShift.value) {
        alert("Por favor, selecciona una fecha y un turno.");
        return null;
    }

    const sales = await fetchSalesForCutOff(selectedShift.value, selectedDate.value);
    if (!sales.length) return null;

    const firstDate = getFirstDate(sales);
    const lastDate  = getLastDate(sales);
    const totals    = getTotalsFromSales(sales);

    return {
        user_id:            user.id,
        start_date:         formatDateToMySQL(firstDate),
        end_date:           formatDateToMySQL(lastDate),
        shift:              selectedShift.value,
        initial_amount:     cashFloatAmount,
        total_amount:       totals.baseTotal,
        total_service:      totals.serviceTotal,
        total_with_service: totals.totalWithService,
        final_amount:       cashFloatAmount + totals.totalWithService,
        tips_card:          getTotalTipsCard(sales),
        tips_cash:          getTotalTipsCash(sales),
        total_tips:         getTotalTips(sales),
    };
}

// ─── Actions ──────────────────────────────────────────────────────────────────

async function createCashAudit() {
    const cashAuditData = await buildCashAuditData();
    if (!cashAuditData) return;

    try {
        await axios.post("/CashAudit", cashAuditData);
        useToast().success("Corte de caja generado exitosamente");
        closeModal();
        alert(
            `Corte de caja generado y guardado exitosamente.\nPropinas: $${cashAuditData.total_tips.toFixed(2)}`
        );
    } catch {
        closeModal();
        useToast().error("Error al generar el corte de caja");
    }
}

const generateCashAudit = async () => {
    const cashAuditData = await buildCashAuditData();
    if (!cashAuditData) {
        alert("No hay ventas para la fecha y turno seleccionados.");
        return;
    }

    closeModal();
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, generar corte",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) createCashAudit();
    });
};

const PrintCutOffTicket = async () => {
    if (!selectedDate.value || !selectedShift.value) {
        alert("Por favor, selecciona una fecha y un turno.");
        return;
    }

    const sales = await fetchSalesForCutOff(selectedShift.value, selectedDate.value);
    if (!sales.length) {
        alert("No hay ventas para la fecha y turno seleccionados.");
        return;
    }

    const totals     = getTotalsFromSales(sales);
    const cashTotals = getTotalsFromSales(
        sales.filter((s) => s.payment_method === "Efectivo" && !s.is_courtesy)
    );
    const cardTotals = getTotalsFromSales(
        sales.filter((s) => s.payment_method === "Tarjeta" && !s.is_courtesy)
    );
    const courtesyBase = sales
        .filter((s) => s.is_courtesy == 1)
        .reduce((sum, s) => sum + s.quantity * s.unit_price, 0);

    const cutOffData = {
        fecha:            selectedDate.value,
        turno:            selectedShift.value,
        totalVentas:      totals.baseTotal,
        totalRoomService: totals.serviceTotal,
        montoFinal:       cashFloatAmount + totals.totalWithService,
        totalPropinas:    getTotalTips(sales),
        totalEfectivo:    cashTotals.totalWithService,
        totalTarjeta:     cardTotals.totalWithService,
        totalCortesias:   courtesyBase,
    };
     console.log("CutOffData para impresión:", cutOffData);

    try {
        const response = await axios.get(route("print.cutOff"), { params: { data: cutOffData } });
        if (response.data?.printData) {
            window.location.href = "print://" + response.data.printData;
        }
    } catch {
        useToast().error("Error al imprimir el corte de caja");
    }
};

function onDateChange() {
    router.get(
        route("Sales/Index"),
        { date: selectedDate.value },
        { preserveState: true, replace: true }
    );
}
</script>

<template>
    <div
        class="bg-white p-4 rounded-2xl w-full max-w-96 sm:max-w-[40rem] lg:max-w-full
               lg:flex lg:flex-col lg:h-[calc(100vh-10rem)]"
    >
        <div class="flex gap-4 justify-between flex-shrink-0">
            <input
                v-model="selectedDate"
                @change="onDateChange"
                type="date"
                placeholder="Buscar Ventas por fecha"
                class="border p-2 rounded-lg w-full"
            />
            <button
            v-if="canGenerateCutOff"
                @click="showModal"
                class="bg-approveGreen hover:bg-green-700 text-white px-4 py-2 lg:w-96 rounded-md uppercase"
            >
                Generar Corte de Caja
            </button>
        </div>
        <div class="overflow-y-auto mt-2 scrollbar-hide lg:flex-1 lg:min-h-0">
            <table class="w-full mt-2 border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-4">#</th>
                        <th class="p-4">Fecha de Creación</th>
                        <th class="p-4">Orden ID</th>
                        <th class="p-4">Usuario</th>
                        <th class="p-4">Producto</th>
                        <th class="p-4">Subtotal</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Método de Pago</th>
                        <th class="p-1">Room Service</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="value in props.sales.data" :key="value.id">
                        <td class="border p-2 text-center">{{ value.id }}</td>
                        <td class="border p-2 text-center">
                            {{ value.date_time }}
                        </td>
                        <td class="border p-2 text-center">
                            {{ value.order_id }}
                        </td>
                        <td class="border p-2 text-center">
                            {{ value.user.name }}
                        </td>
                        <td class="border p-2 text-center">
                            {{
                                value.product
                                    ? value.product.name
                                    : value.product_id
                            }}
                        </td>
                        <td class="border p-2 text-center">
                            {{
                                formatQuantityPrice(
                                    value.quantity,
                                    value.unit_price
                                )
                            }}
                        </td>
                        <td class="border p-2 text-center">
                            {{ formatTotal(value.quantity, value.unit_price) }}
                        </td>
                        <td class="border p-2 text-center">
                            {{ value.payment_method }}
                        </td>
                        <td class="border p-1 text-center">
                            {{ value.is_room ? "Sí" : "No" }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Paginación -->
        <div class="mt-4 flex justify-center flex-shrink-0">
            <template v-for="link in props.sales.links" :key="link.label">
                <button
                    v-if="link.url"
                    @click="
                        router.get(
                            link.url,
                            {},
                            { preserveState: true, replace: true }
                        )
                    "
                    :class="[
                        'px-3 py-1 mx-1 rounded',
                        { 'bg-approveGreen text-white': link.active },
                    ]"
                    v-html="link.label"
                ></button>
                <span
                    v-else
                    class="px-3 py-1 mx-1 text-gray-400"
                    v-html="link.label"
                ></span>
            </template>
        </div>
    </div>

    <Modal v-model:show="openModal" @close="openModal = false">
        <div
            class="p-8 bg-gradient-to-br from-white via-gray-50 to-gray-200 rounded-2xl shadow-2xl mx-auto"
        >
            <h3
                class="text-2xl font-extrabold text-gray-800 mb-2 flex items-center gap-2"
            >
                <svg
                    class="w-7 h-7 text-approveGreen"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path d="M12 8v4l3 3"></path>
                    <circle cx="12" cy="12" r="10"></circle>
                </svg>
                Generar Corte
            </h3>
            <p class="mb-6 text-gray-600 text-base">
                ¿Cuál será el
                <span class="font-semibold text-approveGreen">Turno</span>
                para este corte de caja?
            </p>
            <select
                v-model="selectedShift"
                class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-approveGreen focus:outline-none transition mb-4"
            >
                <option value="" disabled>Seleccione un turno</option>
                <option v-for="shift in props.shifts" :key="shift.id" :value="shift.name_shift">
                    {{ shift.name_shift }} {{ shift.start_time.slice(0, 5) }} - {{ shift.end_time.slice(0, 5) }}
                </option>
            </select>

            <div v-if="selectedShift" class="mb-4">
                <label class="block text-gray-700 font-medium mb-2"
                    >Fecha Seleccionada</label
                >
                <input
                    v-model="selectedDate"
                    type="date"
                    class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-approveGreen focus:outline-none transition"
                    disabled
                />
            </div>

            <div class="flex justify-end gap-3 mt-8">
                <button
                    @click="openModal = false"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg transition"
                >
                    Cancelar
                </button>
                <button
                    @click="PrintCutOffTicket"
                    class="bg-green-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg transition"
                >
                    Imprimir
                </button>
                <button
                    @click="generateCashAudit"
                    class="bg-approveGreen hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition"
                >
                    Generar Corte
                </button>
            </div>
        </div>
    </Modal>
</template>
