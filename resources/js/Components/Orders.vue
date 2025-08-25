<script setup>
import StatusBadge from '@/Components/StatusBadge.vue';



</script>

<template>
    <div class="bg-white p-2 rounded-lg shadow-md mt-4">
        <div class="md:flex justify-between items-center 2xl:gap-12 mx-auto">
            <h2 class="md:text-lg lg:text-xl">Orden <span class="font-extrabold">ID #{{ order ?? '0000' }}</span></h2>
            <StatusBadge class="" />
        </div>
        <h2 class="text-center text-3xl font-bold">Mesa {{ table ?? '7' }}</h2>
        <div class="flex justify-center items-center text-sm mt-2">
            <p class="text-center text-secondary">{{ hour ?? '12:00 PM' }} |</p>
            <p class="text-center text-secondary">{{ date ?? 'Hoy' }}</p>
        </div>
        <select class="mt-2 w-full rounded-xl text-center">
            <option v-for="value in source" :value="value.id">{{ value.name }}</option>
        </select>
    </div>
</template>
