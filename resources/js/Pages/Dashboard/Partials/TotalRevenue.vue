<script setup>

</script>

<template>
    <div class="w-full ">
        <div class="bg-white rounded-xl shadow p-6 flex gap-4 justify-center items-center">
            <div class="rounded-full bg-orangeYellow/30 flex items-center justify-center w-14 h-14">
                <img src="/assets/icons/svg/dash/revenue.svg" alt="Total Revenue" class="h-10 w-10" />
            </div>
            <div class="">
                <h2 class="text-xl font-medium">Ingresos Totales</h2>
                <p class="text-3xl font-bold text-gray-800"><span>$ </span>{{ totalRevenue ?? 0 }} </p>
            </div>
        </div>
    </div>
</template>