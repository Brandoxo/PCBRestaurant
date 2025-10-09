<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import { usePage, router } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { useToast } from "vue-toastification";
import Swal from "sweetalert2";

const props = defineProps({ sales: Object });
const user = usePage().props.auth.user;
console.log('user details', user)


const canGenerateCutOff = computed(() =>{
    return user && user.permissions && user.permissions.includes('generate cutoff')
})

const selectedDate = ref("");
const selectedShift = ref("");
const openModal = ref(false);

const showModal = () => {
    openModal.value = true;
};
const closeModal = () => {
    openModal.value = false;
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat("es-MX", {
        style: "currency",
        currency: "MXN",
    }).format(amount);
};

const formatQuantityPrice = (quantity, unitPrice) => {
    return `${quantity} x ${formatCurrency(unitPrice)}`;
};

const formatTotal = (quantity, unitPrice) => {
    return formatCurrency(quantity * unitPrice);
};

const shifts = {
    morning: "Mañana",
    afternoon: "Tarde",
};

const getShift = (dateTime) => {
    const hour = new Date(dateTime).getHours();
    if (hour >= 7 && hour < 15) return shifts.morning;
    if (hour >= 15 && hour < 23) return shifts.afternoon;
    return "Fuera de turno";
};

function getFilteredSales() {
    let sales = props.sales.data;
    if (selectedDate.value) {
        sales = sales.filter((sale) => {
            const d = new Date(sale.date_time);
            const saleDate =
                d.getFullYear() +
                "-" +
                String(d.getMonth() + 1).padStart(2, "0") +
                "-" +
                String(d.getDate()).padStart(2, "0");
            return saleDate === selectedDate.value;
        });
    }
    if (selectedShift.value) {
        let shiftName = selectedShift.value;
        if (shiftName === "Matutino") shiftName = "Mañana";
        if (shiftName === "Vespertino") shiftName = "Tarde";
        if (shiftName === "Otro") shiftName = "Fuera de turno";
        sales = sales.filter((sale) => getShift(sale.date_time) === shiftName);
    }
    return sales;
}

function getFirstDate(sales) {
    if (!sales.length) return null;
    return new Date(Math.min(...sales.map((sale) => new Date(sale.date_time))));
}

function getTotalAmount(sales) {
    return sales
        .filter((sale) => !sale.is_courtesy)
        .reduce((total, sale) => total + sale.quantity * sale.unit_price, 0);
}

function getTotalTipsIntByOrder(sales) {
    const orderIds = new Set();
    let total = 0;
    sales.forEach((sale) => {
        if (
            sale.order &&
            !orderIds.has(sale.order.id) &&
            sale.order.tip !== null &&
            sale.order.tip !== undefined &&
            sale.order.tip !== ""
        ) {
            total += parseFloat(sale.order.tip);
            orderIds.add(sale.order.id);
        }
    });
    return total;
}

function getTotalTipsPercent(sales) {
    return sales.reduce((sum, sale) => {
        const tipPercent =
            sale.order && sale.order.tip_percent
                ? parseFloat(sale.order.tip_percent)
                : 0;
        return sum + sale.quantity * sale.unit_price * (tipPercent / 100);
    }, 0);
}

function formatDateToMySQL(dateString) {
    const date = new Date(dateString);
    return (
        date.getFullYear() +
        "-" +
        String(date.getMonth() + 1).padStart(2, "0") +
        "-" +
        String(date.getDate()).padStart(2, "0") +
        " " +
        String(date.getHours()).padStart(2, "0") +
        ":" +
        String(date.getMinutes()).padStart(2, "0") +
        ":" +
        String(date.getSeconds()).padStart(2, "0")
    );
}

async function buildCashAuditData() {
    if (!selectedDate.value || !selectedShift.value) {
        alert("Por favor, selecciona una fecha y un turno.");
        return null;
    }
    const { data: sales } = await axios.get("/Sales-for-cutoff", {
        params: {
            date: selectedDate.value,
            shift: selectedShift.value,
        },
    });
    if (!sales.length) return null;
    const firstDate = getFirstDate(sales);
    const totalAmount = getTotalAmount(sales);
    const totalTipsInt = getTotalTipsIntByOrder(sales);
    const totalTipsPercent = getTotalTipsPercent(sales);
    return {
        user_id: user.id,
        start_date: formatDateToMySQL(firstDate),
        end_date: formatDateToMySQL(firstDate),
        shift: selectedShift.value,
        initial_amount: 3500,
        total_amount: totalAmount,
        final_amount: 3500 + totalAmount,
        total_tips: totalTipsInt + totalTipsPercent,
    };
}

function getFilteredSalesPaymentMethodCash() {
    let sales = getFilteredSales();
    sales = sales.filter(
        (sale) => sale.payment_method === "Efectivo" && !sale.is_courtesy
    );
    return sales;
}

function getFilteredSalesPaymentMethodCard() {
    let sales = getFilteredSales();
    sales = sales.filter(
        (sale) => sale.payment_method === "Tarjeta" && !sale.is_courtesy
    );
    return sales;
}

function getFilteredSalesCourtesy() {
    let sales = getFilteredSales();
    sales = sales.filter((sale) => sale.is_courtesy === 1);
    return sales;
}

async function createCashAudit() {
    const cashAuditData = await buildCashAuditData();
    try {
        const response = await axios.post("/CashAudit", cashAuditData);
        useToast().success("Corte de caja Generado Exitosamente");
        closeModal();
        alert(
            `Corte de caja generado y guardado exitosamente.\nPropinas: $${cashAuditData.total_tips.toFixed(
                2
            )}`
        );
    } catch (error) {
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
        if (result.isConfirmed) {
            createCashAudit();
        }
    });
};

const PrintCutOffTicket = () => {
    const sales = getFilteredSales();
    if (!sales.length) {
        alert("No hay ventas para la fecha y turno seleccionados.");
        return;
    }
    const cutOffData = {
        fecha: selectedDate.value,
        turno: selectedShift.value,
        totalVentas: getTotalAmount(sales),
        montoFinal: 3500 + getTotalAmount(sales),
        totalPropinas:
            getTotalTipsIntByOrder(sales) + getTotalTipsPercent(sales),
        totalEfectivo: getTotalAmount(getFilteredSalesPaymentMethodCash()),
        totalTarjeta: getTotalAmount(getFilteredSalesPaymentMethodCard()),
        totalCortesias: getTotalAmount(getFilteredSalesCourtesy()),
    };

    axios
        .get(route("print.cutOff"), {
            params: { data: cutOffData },
        })
        .then((response) => {
            if (response.data && response.data.printData) {
                const printUrl = "print://" + response.data.printData;
                window.location.href = printUrl;
            }
        });
};

function onDateChange() {
    console.log("Selected date:", selectedDate.value);
    router.get(
        route("Sales/Index"),
        { date: selectedDate.value },
        { preserveState: true, replace: true }
    );
}
</script>

<template>
    <div
        class="bg-white p-4 rounded-2xl w-full max-w-96 sm:max-w-[40rem] lg:max-w-full mx-auto"
    >
        <div class="flex gap-4 justify-between mx-auto">
            <input
                v-model="selectedDate"
                @change="onDateChange"
                type="date"
                placeholder="Buscar Ventas por fecha"
                class="border p-2 rounded-lg w-full"
            />
            <button
            
                @click="showModal"
                class="bg-approveGreen hover:bg-green-700 text-white px-4 py-2 lg:w-96 rounded-md uppercase"
            >
                Generar Corte de Caja
            </button>
        </div>
        <div class="overflow-x-auto w-full">
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
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Paginación -->
        <div class="mt-4 flex justify-center">
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
                <option value="Matutino">Mañana (7am - 3pm)</option>
                <option value="Vespertino">Tarde (3pm - 11pm)</option>
            </select>

            <div
                v-if="
                    selectedShift === 'Matutino' ||
                    selectedShift === 'Vespertino'
                "
                class="mb-4"
            >
                <label class="block text-gray-700 font-medium mb-2"
                    >Selecciona la fecha</label
                >
                <input
                    v-model="selectedDate"
                    type="date"
                    class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-approveGreen focus:outline-none transition"
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
