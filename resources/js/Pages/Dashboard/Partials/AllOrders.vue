<script setup>
import Orders from "@/Components/Orders.vue";
const props = defineProps({ orders: Array });
const emit = defineEmits(["select"]);
</script>

<template>

    <div class="grid 2xl:grid-cols-3 gap-4">
        <div
            v-if="!orders || orders.length === 0"
            v-for="n in 15"
            :key="n"
            class="animate-pulse bg-gray-300 2xl:w-56 h-28 rounded-lg"
        ></div>
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
