<script setup>
const props = defineProps({ sales: Array });

console.log("props in <ListSales>: ", props.sales);
</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar Venta Realizada Por..."
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
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in props.sales" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">
                        {{ value.date_time }}
                    </td>
                    <td class="border p-2 text-center">{{ value.order_id }}</td>
                    <td class="border p-2 text-center">{{ value.user_id }}</td>
                    <td class="border p-2 text-center">
                        {{ value.product_id }}
                    </td>
                    <td class="border p-2 text-center">
                        {{ value.quantity }}x ${{ value.unit_price }}
                    </td>
                    <td class="border p-2 text-center">
                        ${{ value.quantity * value.unit_price }}
                    </td>
                    <td class="border p-2 flex gap-2 justify-center">
                        <button
                            @click="showModal(value.id)"
                            class="bg-midBlue hover:bg-strongBlue text-white p-1 rounded"
                        >
                            <img
                                src="/assets/icons/svg/actions/edit.svg"
                                alt="Editar"
                                class="w-6"
                            />
                        </button>
                        <button
                            @click="deleteTable(value.id)"
                            class="bg-dangerRed hover:bg-red-700 text-white p-1 rounded"
                        >
                            <img
                                src="/assets/icons/svg/actions/delete.svg"
                                alt="Eliminar"
                                class="w-6"
                            />
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
