<script setup>
import { formatter, formatterWithoutFraction } from '@/utils/currencyFormatter.js';
const props = defineProps({
  selectedTable: Object,
  orders: Object,
currentOrder: Object
});

console.log('Selected Table in OrderDetails:', props.selectedTable);
console.log('Orders in OrderDetails:', props.orders);
console.log('Current Order in OrderDetails:', props.currentOrder);
</script>

<template>
    <div class="bg-white rounded-xl p-6 mt-6 shadow-lg hover:scale-[1.01] transform transition-transform duration-300 ease-in-out">
        <h3 class="text-2xl font-bold text-center">Detalles de la Orden</h3>
        
        <div v-if="currentOrder && currentOrder.items.length > 0">
            <h4 class="text-xl font-extralight mb-2 text-center text-gray-500">Pedido en curso</h4>
            <div class="flex justify-evenly items-center">
                <h3 class="text-2xl font-bold mb-4 text-center text-red-900">Mesa #{{ selectedTable.number }}</h3>
                <h3 class="text-2xl font-bold mb-4 text-center">Orden #{{ currentOrder.id }}</h3>
            </div>
            <h5 class="text-lg font-semibold mb-2">Productos en la orden actual:</h5>

            <ul class="list-disc list-inside mb-2 text-gray-700 text-center max-h-24  2xl:max-h-80 overflow-y-auto">
                <li v-for="item in currentOrder.items" :key="item.product_id" class="mb-1 text-start ">
                    {{ item.product.name }} x{{ item.quantity }} <div class="flex justify-end"> <span class="font-extralight text-gray-500">${{ formatterWithoutFraction.format(item.price * item.quantity) }}</span></div>
                    <hr></hr>
                </li>
            </ul>
        </div>
        <div class="flex justify-between mt-4 space-x-4 text-lg">
        <span class="font-extrabold text-xl flex uppercase underline">Total:</span> <span class="font-black uppercase text-xl ">{{ formatter.format(currentOrder.items.reduce((total, item) => total + (item.price * item.quantity), 0)) }}</span>
        </div>

        <div v-if="(!currentOrder || currentOrder.items.length === 0) && (!orders || orders.length === 0)" class="text-gray-500">
            <p>No hay productos para la mesa seleccionada.</p>
        </div>
    </div>
</template>