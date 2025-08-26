<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import StatusBadge from "@/Components/StatusBadge.vue";

// const props = defineProps({ order: Object });
</script>

<template>
    <div class="bg-white shadow-md rounded-lg p-2 w-3/4 mx-auto">
        <div class="md:flex justify-between items-center 2xl:gap-12 mx-auto">
            <h2 class="md:text-lg lg:text-xl">
                Orden
                <span class="font-extrabold">ID #
                    <!-- {{ order.id ?? '0000' }} -->
                </span>
                <img
                    src="assets/icons/svg/links/copy.svg"
                    alt="Copy Icon"
                    class="inline w-6 ml-2 cursor-pointer"
                />
            </h2>
            <StatusBadge class="" />
        </div>
        <div class="flex items-center justify-between mt-4">
            <div class="flex-col">
                <h2 class="text-start text-3xl font-bold">
                    Mesa 
                    <!-- {{ order.table ?? '7' }} -->
                </h2>
                <div class="flex text-sm mt-2">
                    <p class="text-center text-secondary">
                        <!-- {{ order.hour ?? '12:00 PM' }} | -->
                    </p>
                    <p class="text-center text-secondary">
                        <!-- {{ order.date ?? 'Hoy' }} -->
                    </p>
                </div>
            </div>
            <button
                class="mt-4 bg-dangerRed/30 p-2 rounded-full text-dangerRed font-bold hover:bg-dangerRed/50 transition"
            >
                Cancelar Orden
            </button>
            <button class="flex items-center gap-2">
                <img
                    src="assets/icons/svg/links/sheet.svg"
                    alt="Imagen de la orden"
                    class="w-6"
                />
                <p class="text-center text-midBlue font-bold">
                    Export
                    <!-- {{ order.id ?? '' }} -->
                </p>
            </button>
        </div>
    </div>
</template>
