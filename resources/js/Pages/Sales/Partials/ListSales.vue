<script setup>
import { ref, computed } from "vue";


const props = defineProps({ sales: Array });

console.log("props in <ListSales>: ", props.sales);

const searchQuery = ref("");
const filteredSales = computed(() => {
    if (!searchQuery.value) {
        return props.sales;
    }
    return props.sales.filter((sale) =>
        sale.user.name
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase())
    );
});

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
    if (hour >= 8 && hour < 14) return shifts.morning;
    if (hour >= 14 && hour < 23) return shifts.afternoon;
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
console.log("Total sales in the afternoon:", totalSalesByShift(shifts.afternoon));
console.log("Total sales outside shifts:", totalSalesByShift("Fuera de turno"));

const grandTotal = computed(() => {
    return props.sales.reduce(
        (total, sale) => total + sale.quantity * sale.unit_price,
        0
    );
});
console.log("Grand total of sales:", grandTotal.value);

const generateCashAudit = () => {
    const audit = [];
    Object.entries(salesByDayAndShift.value).forEach(([date, shifts]) => {
        audit.push({
            date,
            morning: {
                total: shifts.morning.reduce((sum, sale) => sum + sale.quantity * sale.unit_price, 0),
                count: shifts.morning.length,
            },
            afternoon: {
                total: shifts.afternoon.reduce((sum, sale) => sum + sale.quantity * sale.unit_price, 0),
                count: shifts.afternoon.length,
            },
            other: {
                total: shifts.other.reduce((sum, sale) => sum + sale.quantity * sale.unit_price, 0),
                count: shifts.other.length,
            },
        });
    });
    console.log("Corte de caja por día y turno:", audit);
    return audit;
};


</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <div class="flex gap-4 justify-between mx-auto">
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar Ventas Realizadas Por..."
            class="border p-2 rounded-lg w-full "
        />
        <button @click="generateCashAudit" class="bg-approveGreen hover:bg-green-700 transform *: text-white px-4 py-2 lg:w-96 rounded-md uppercase">Generar Corte de Caja</button>
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
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in filteredSales" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">
                        {{ value.date_time }}
                    </td>
                    <td class="border p-2 text-center">{{ value.order_id }}</td>
                    <td class="border p-2 text-center">{{ value.user.name }}</td>
                    <td class="border p-2 text-center">
                        {{ value.product_id }}
                    </td>
                    <td class="border p-2 text-center">
                        {{ formatQuantityPrice(value.quantity, value.unit_price) }}
                    </td>
                    <td class="border p-2 text-center">
                        {{ formatTotal(value.quantity, value.unit_price) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
