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
    number: props.order.table.number,
    mesa_id: props.order.table.id,
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
        <section
            v-if="order.status === 'En curso'"
            class="p-4 py-6 overflow-y-auto mx-auto sm:px-6 lg:flex justify-center"
        >
            <Head title="Editar Orden" />
            <div class="flex flex-col">
                <div
                    class="bg-white rounded-lg animate-pulse pointer-events-none transition-transform duration-300 ease-in-out min-w-80 2xl:w-1/4"
                >
                    <div
                        class="'bg-white rounded-xl items-center p-4 space-y-2 shadow-lg'"
                    >
                        <div>
                            <StatusBadge :status="order.table.status" />
                        </div>
                        <div
                            class="flex justify-between gap-8 items-center mb-4"
                        >
                            <h2 class="text-xl uppercase font-black">Mesa</h2>
                            <img
                                src="/assets/icons/svg/menu/table.svg"
                                alt="Mesa"
                                class="w-16"
                            />
                            <span class="font-extrabold text-4xl text-red-900"
                                >#{{ order.table.number }}</span
                            >
                        </div>
                        <div class="flex justify-center items-center gap-2">
                            <img
                                src="/assets/icons/svg/visual/people.svg"
                                alt="Capacidad"
                                class="inline-block w-4 h-4"
                            />
                            <p class="text-center text-gray-500">
                                Capacidad para
                                <span class="font-bold text-lg text-gray-600">{{
                                    order.table.capacity
                                }}</span>
                                personas
                            </p>
                        </div>
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
    </AuthenticatedLayout>
</template>
