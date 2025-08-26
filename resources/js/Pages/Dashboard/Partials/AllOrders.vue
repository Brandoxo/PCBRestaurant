<script setup>
import Orders from "@/Components/Orders.vue";
// const props = defineProps({ orders: Array });
// const emit = defineEmits(["select"]);
</script>

<template>
 <div> <!-- v-for="order in orders" :key="order.id" @click="emit('select', order) -->
        <h2 class="text-lg font-bold text-white text-center">
            Todas las órdenes
        </h2>
        <Orders /> <!-- <v-bind="order"--> 
        <Orders /> <!-- <v-bind="order"--> 
    </div>
</template>
