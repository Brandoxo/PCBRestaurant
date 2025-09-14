<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import StatusBadge from "@/Components/StatusBadge.vue";

const props = defineProps({ order: Object });

console.log("order in:", props.order);
</script>

<template>
    <div class="bg-white shadow-md rounded-lg p-2 w-[80%] mx-auto" v-if="order">
        <div class="lg:flex justify-between items-center 2xl:gap-12 mx-auto">
            <h2 class="md:text-lg lg:text-xl">
                Orden
                <span class="font-extrabold"
                    >ID #
                    {{ order.id ?? "0000" }}
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
                    {{ order.table.number ?? "7" }}
                </h2>
                <div class="flex text-sm mt-2">
                    <p class="text-center text-secondary">
                        {{
                            order.date_time
                                ? new Date(order.date_time).toLocaleTimeString()
                                : "-"
                        }}
                    </p>
                    <p class="text-center text-secondary">
                        {{
                            order.date_time
                                ? new Date(order.date_time).toLocaleDateString()
                                : "-"
                        }}
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
                    Export #{{ order.id ?? "" }}
                </p>
            </button>
        </div>
    </div>

    <!-- wait for order details -->
    <div v-else class="mx-auto w-3/4 bg-white rounded-lg p-4">
        <div class="flex animate-pulse space-x-4">
            <div class="size-10 rounded-full bg-gray-200"></div>
            <div class="flex-1 space-y-6 py-1">
                <div class="h-2 rounded bg-gray-200"></div>
                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 h-2 rounded bg-gray-200"></div>
                        <div class="col-span-1 h-2 rounded bg-gray-200"></div>
                    </div>
                    <div class="h-2 rounded bg-gray-200"></div>
                </div>
            </div>
        </div>
    </div>
</template>
