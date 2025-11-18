<script setup>
import { defineProps, ref } from "vue";
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import MakeOrder from "../Orders/Partials/MakeOrder.vue";
import OrderDetails from "../Orders/Partials/OrderDetails.vue";
import StatusBadge from "@/Components/StatusBadge.vue";

const props = defineProps({
    order: Object,
    tables: Object,
});

const selectedTable = ref({
    number: props.order.table?.number ?? props.order.room?.number,
    mesa_id: props.order.table?.id ?? props.order.room?.id,
});

const currentOrder = ref({
    id: props.order.id,
    items: props.order.order_details.map((detail) => ({
        product_id: detail.product.id,
        product: detail.product,
        quantity: detail.quantity,
        price: detail.product.price,
        subtotal: detail.quantity * detail.product.price,
    })),
});

console.log("Current Orderssssss:", currentOrder.value);
</script>
<template>
    <AuthenticatedLayout>
        <div class="overflow-y-auto scrollbar-hide lg:py-60 lg:h-svh lg:pt-5">
            <section v-if="order.status === 'En curso'" class="px-4 p-8 flex">
                <Head title="Editar Orden" />
                <div class="flex flex-col">
                    <div
                        class="bg-white rounded-lg animate-pulse pointer-events-none transition-transform duration-300 ease-in-out w-96 "
                    >
                        <div
                            class="'bg-white rounded-xl items-center p-4 space-y-2 shadow-lg'"
                        >
                            <div>
                                <StatusBadge
                                    :status="
                                        order.table?.status ??
                                        order.room?.status
                                    "
                                />
                            </div>
                            <div
                                class="flex justify-between gap-8 items-center mb-4"
                            >
                                <h2 class="text-xl uppercase font-black">
                                    {{ order.table?.name ?? order.room?.name }}
                                </h2>
                                <img
                                    v-if="order.table"
                                    src="/assets/icons/svg/menu/table.svg"
                                    alt="Mesa"
                                    class="w-16"
                                />
                                <img
                                    v-else
                                    src="/assets/icons/svg/menu/rooms.svg"
                                    alt="Habitación"
                                    class="w-16"
                                />
                                <span
                                    class="font-extrabold text-4xl text-red-900"
                                    >#{{
                                        order.table?.number ??
                                        order.room?.number
                                    }}</span
                                >
                            </div>
                            <div
                                v-if="order.table?.capacity"
                                class="flex justify-center items-center gap-2"
                            >
                                <img
                                    src="/assets/icons/svg/visual/people.svg"
                                    alt="Capacidad"
                                    class="inline-block w-4 h-4"
                                />
                                <p class="text-center text-gray-500">
                                    Capacidad para
                                    <span
                                        class="font-bold text-lg text-gray-600"
                                        >{{ order.table?.capacity }}</span
                                    >
                                    personas
                                </p>
                            </div>
                            <div
                                v-else-if="order.room?.capacity"
                                class="flex justify-center items-center gap-2"
                            ></div>
                        </div>
                    </div>

                    <OrderDetails
                        :selectedTable="selectedTable"
                        :orders="[props.order]"
                        :currentOrder="currentOrder"
                    />
                </div>

                <MakeOrder
                    :selectedTable="selectedTable"
                    :tables="tables"
                    :orders="[props.order]"
                    :currentOrder="currentOrder"
                    :isEdit="true"
                />
            </section>
        </div>
    </AuthenticatedLayout>
</template>
