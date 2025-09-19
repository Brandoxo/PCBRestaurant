<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import { usePage } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";

const props = defineProps({ sales: Array });
const user = usePage().props.auth.user;

console.log("props in <ListSales>: ", props.sales);
console.log("user in <ListSales>: ", user);

const searchQuery = ref("");
const filteredSales = computed(() => {
    let ordered = [...props.sales].sort((a, b) => b.id - a.id);
    if (!searchQuery.value) {
        return ordered;
    }
    return ordered.filter((sale) => {
        const saleDate = new Date(sale.date_time).toISOString().split("T")[0];
        return saleDate === searchQuery.value;
    });
});

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

const salesByDayAndShift = computed(() => {
    const grouped = {};
    props.sales.forEach((sale) => {
        const date = new Date(sale.date_time).toISOString().split("T")[0];
        const shift = getShift(sale.date_time);
        if (!grouped[date]) {
            grouped[date] = { morning: [], afternoon: [], other: [] };
        }
        if (shift === shifts.morning) {
            grouped[date].morning.push(sale);
        } else if (shift === shifts.afternoon) {
            grouped[date].afternoon.push(sale);
        } else {
            grouped[date].other.push(sale);
        }
    });
    return grouped;
});

console.log("Sales grouped by day and shift:", salesByDayAndShift.value);

const totalSalesByShift = (shift) => {
    return props.sales
        .filter((sale) => getShift(sale.date_time) === shift)
        .reduce((total, sale) => total + sale.quantity * sale.unit_price, 0);
};
console.log("Total sales in the morning:", totalSalesByShift(shifts.morning));
console.log(
    "Total sales in the afternoon:",
    totalSalesByShift(shifts.afternoon)
);
console.log("Total sales outside shifts:", totalSalesByShift("Fuera de turno"));

const grandTotal = computed(() => {
    return props.sales.reduce(
        (total, sale) => total + sale.quantity * sale.unit_price,
        0
    );
});
console.log("Grand total of sales:", grandTotal.value);

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

const selectedDate = ref("");
const selectedShift = ref("");

const generateCashAudit = async () => {
    const user_id = user.id;

    let sales = props.sales;
    if (selectedDate.value) {
        sales = sales.filter((sale) => {
            const saleDate = new Date(sale.date_time)
                .toISOString()
                .split("T")[0];
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

    if (sales.length === 0) {
        alert("No hay ventas para la fecha y turno seleccionados.");
        return;
    }

    const firstDate = new Date(
        Math.min(...sales.map((sale) => new Date(sale.date_time)))
    );
    const startDate = firstDate;
    const endDate = firstDate;

    const totalAmount = sales.reduce(
        (total, sale) => total + sale.quantity * sale.unit_price,
        0
    );

    const totalTipsInt = sales.reduce((sum, sale) => {
    let tipAmount = 0;
        if (sale.order.tip !== null && sale.order.tip !== undefined && sale.order.tip !== "") {
            tipAmount = parseFloat(sale.order.tip);

        }
        return sum + tipAmount;
    }, 0);

    const totalTipsPercent = sales.reduce((sum, sale) => {
        const tipPercent =
            sale.order && sale.order.tip_percent ? parseFloat(sale.order.tip_percent) : 0;
        return sum + sale.quantity * sale.unit_price * (tipPercent / 100);
    }, 0);

    try {
        const response = await axios.post("/CashAudit", {
            user_id,
            start_date: formatDateToMySQL(startDate),
            end_date: formatDateToMySQL(endDate),
            shift: selectedShift.value,
            initial_amount: 3500,
            total_amount: totalAmount,
            final_amount: 3500 + totalAmount,
            total_tips: totalTipsInt + totalTipsPercent,
        });
        console.log("Corte de caja guardado:", response.data);
        alert(
            `Corte de caja generado y guardado exitosamente.\nPropinas: $${(totalTipsInt + totalTipsPercent).toFixed(
                2
            )}`
        );
    } catch (error) {
        console.error("Error al guardar el corte de caja:", error);
        alert("Error al generar y guardar el corte de caja.");
    }
};

const PrintCutOffTicket = () => {
    // Logica para obtener los datos de corte de caja
    const cutOffData = {
        fecha: new Date().toISOString(),
        turno: "Mañana",
        totalVentas: 1500.50,
        montoFinal: 1550.00,
        totalPropinas: 49.50
    };

    axios.get(route('print.cutOff'), {
        params: { data: cutOffData }
    })
    .then(response => {
        if (response.data && response.data.printData) {
            const printUrl = 'print://' + response.data.printData;
            window.location.href = printUrl;
        }
    });
};
</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <div class="flex gap-4 justify-between mx-auto">
            <input
                v-model="searchQuery"
                type="date"
                placeholder="Buscar Ventas por fecha"
                class="border p-2 rounded-lg w-full"
            />
            <button
                @click="showModal"
                class="bg-approveGreen hover:bg-green-700 transform *: text-white px-4 py-2 lg:w-96 rounded-md uppercase"
            >
                Generar Corte de Caja
            </button>
        </div>
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
                <tr v-for="value in filteredSales" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">
                        {{ value.date_time }}
                    </td>
                    <td class="border p-2 text-center">{{ value.order_id }}</td>
                    <td class="border p-2 text-center">
                        {{ value.user.name }}
                    </td>
                    <td class="border p-2 text-center">
                        {{ value.product ? value.product.name : value.product_id }}
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
