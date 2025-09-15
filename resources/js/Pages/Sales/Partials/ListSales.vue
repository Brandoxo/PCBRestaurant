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
</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar Ventas Realizadas Por..."
            class="border p-2 rounded-lg w-full mb-4"
        />
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
                        {{ value.quantity }}x ${{ value.unit_price }}
                    </td>
                    <td class="border p-2 text-center">
                        ${{ value.quantity * value.unit_price }}
                    </td>                </tr>
            </tbody>
        </table>
    </div>
</template>
