<script setup>
import Orders from "@/Components/Orders.vue";
const props = defineProps({ orders: Array });
const emit = defineEmits(["select"]);
</script>

<template>
    <h2 class="text-lg font-bold text-white text-center mb-2">
        Todas las órdenes
    </h2>
    <div class="grid 2xl:grid-cols-3 gap-4">
        <div
            v-for="order in orders"
            :key="order.id"
            @click="emit('select', order)"
            class="cursor-pointer overflow-y-auto hover:scale-[1.03] transition-all transform ease-in-out duration-300"
        >
            <Orders :order="order" />
        </div>
    </div>
</template>
