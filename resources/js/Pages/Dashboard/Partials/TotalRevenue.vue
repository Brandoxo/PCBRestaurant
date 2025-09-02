<script setup></script>

<template>
    <div class="w-full max-w-md mx-auto">
        <div
            class="bg-white rounded-3xl shadow p-6 flex gap-4 justify-center items-center"
        >
            <div
                class="rounded-full bg-goodYellow/30 flex items-center justify-center w-14 h-14"
            >
                <img
                    src="/assets/icons/svg/dash/revenue.svg"
                    alt="Total Revenue"
                    class="h-10 w-10"
                />
            </div>
            <div class="w-fit">
                <h2 class="text-xl font-medium">Ingresos Totales</h2>
                <p class="text-3xl font-bold text-gray-800">
                    <span>$ </span>{{ totalRevenue ?? 0 }}
                </p>
            </div>
        </div>
    </div>
</template>
