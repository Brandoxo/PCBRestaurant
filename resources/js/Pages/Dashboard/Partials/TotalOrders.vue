<script setup>
import { defineProps } from "vue";

const props = defineProps({
    totalOrders: Number,
});

console.log(props.totalOrders);
</script>

<template>
    <div class="max-w-md w-full">
        <div
            class="bg-white rounded-3xl shadow p-6 lg:flex gap-4 justify-center items-center w-full text-center lg:text-start"
        >
            <div
                class="rounded-full bg-approveGreen/30 flex items-center justify-center w-14 h-14 mx-auto lg:mx-0 mb-4 lg:mb-0"
            >
                <img
                    src="/assets/icons/svg/dash/cube.svg"
                    alt="Total Orders"
                    class="h-10 w-10 "
                />
            </div>
            <div class="">
                <h2 class="text-xl font-medium">Ordenes Totales</h2>
                <p class="text-3xl font-bold text-gray-800">
                    {{ props.totalOrders ?? 0 }}
                </p>
            </div>
        </div>
    </div>
</template>
