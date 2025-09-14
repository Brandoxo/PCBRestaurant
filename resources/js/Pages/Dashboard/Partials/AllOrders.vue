<script setup>
import Orders from "@/Components/Orders.vue";
const props = defineProps({ orders: Array });
const emit = defineEmits(["select"]);
</script>

<template>
    <div>
        <h2 class="text-lg font-bold text-white text-center mb-2">
            Todas las órdenes
        </h2>
        <div
            v-for="order in orders"
            :key="order.id"
            @click="emit('select', order)"
            class="cursor-pointer"
        >
            <Orders :order="order" />
        </div>
    </div>
</template>
