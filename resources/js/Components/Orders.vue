<script setup>
import StatusBadge from "@/Components/StatusBadge.vue";
const props = defineProps({ order: Object });
console.log("order:", props.order.order_details);
</script>

<template>
    <div
        :class="[
            'bg-white p-2 rounded-lg shadow-md mt-4 max-w-md mx-auto w-full',
            order.status === 'Cancelada'
                ? 'opacity-50 border-2 border-red-500'
                : '',
            order.status === 'Completada'
                ? 'border-2 border-green-500 opacity-50'
                : '',
        ]"
    >
        <div class="flex mb- justify-evenly items-center">
            <h2 class="md:text-lg lg:text-xl">
                Orden
                <span class="font-extrabold">ID #{{ order.id ?? "0000" }}</span>
            </h2>
            <StatusBadge :status="order.status" />
        </div>
        <h2 class="text-center text-3xl font-bold">
            Mesa {{ order.table.number ?? "-" }}
        </h2>
        <div class="flex justify-center items-center text-sm mt-2 gap-2">
            <p class="text-center text-secondary">
                {{
                    order.date_time
                        ? new Date(order.date_time).toLocaleTimeString()
                        : "-"
                }}
            </p>
            <span class="text-secondary">|</span>
            <p class="text-center text-secondary">
                {{
                    order.date_time
                        ? new Date(order.date_time).toLocaleDateString()
                        : "-"
                }}
            </p>
        </div>
        <select class="mt-2 w-full border rounded px-2 py-1">
            <option class="text-center" disabled selected>
                Ver menú de la Orden
            </option>
            <option
                disabled
                v-for="detail in order.order_details"
                :key="detail.id"
                :value="detail.product.id"
            >
                {{ detail.product.name }} x{{ detail.quantity }}
            </option>
        </select>
    </div>
</template>
