<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import TotalOrders from "@/Pages/Dashboard/Partials/TotalOrders.vue";
import TotalRevenue from "@/Pages/Dashboard/Partials/TotalRevenue.vue";
import TotalCancelled from "@/Pages/Dashboard/Partials/TotalCancelled.vue";
import { ref, watch, toRefs, computed } from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import AllOrders from "@/Pages/Dashboard/Partials/AllOrders.vue";
import SelectedOrder from "@/Pages/Dashboard/Partials/SelectedOrder.vue";
import OrderDetails from "./Partials/OrderDetails.vue";

const props = defineProps({
    totalProducts: Number,
    totalCategories: Number,
    totalUsers: Number,
    sales: Array,
    orders: Array,
    RoomServiceConfig: Object,
});

console.log("Sales prop in <Dashboard>: ", props.sales);

const d = new Date();
const date_today =
    d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
const shifts = ["Matutino", "Vespertino"];
console.log("Today's date: ", date_today);

const totalIncomeToday = computed(() => {
    return props.sales
        .filter(
            (sale) =>
                sale.is_courtesy === 0 &&
                new Date(sale.date_time).getFullYear() === d.getFullYear() &&
                new Date(sale.date_time).getMonth() === d.getMonth() &&
                new Date(sale.date_time).getDate() === d.getDate()
        )
        .reduce((sum, sale) => sum + parseFloat(sale.subtotal), 0);
});

console.log("Total Income: ", totalIncomeToday.value);

const totalOrdersToday = computed(() => {
    return props.orders.filter(
        (order) =>
            new Date(order.date_time).getFullYear() === d.getFullYear() &&
            new Date(order.date_time).getMonth() === d.getMonth() &&
            new Date(order.date_time).getDate() === d.getDate()
    ).length;
});

const totalCancelledToday = computed(() => {
    return props.orders.filter(
        (order) =>
            new Date(order.date_time).getFullYear() === d.getFullYear() &&
            new Date(order.date_time).getMonth() === d.getMonth() &&
            new Date(order.date_time).getDate() === d.getDate() &&
            order.status === "Cancelada"
    ).length;
});

const allOrders = props.orders;

const isWelcomeVisible = ref(true);

const closeWelcome = () => {
    let count = 0;
    isWelcomeVisible.value = false;
    count++;
};

const selectedOrder = ref(null);
const selectOrder = (order) => {
    selectedOrder.value = order;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
            <div class="px-4 p-4 overflow-y-auto h-svh">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg w-fit flex justify-between mx-auto"
                    v-if="isWelcomeVisible && count === 0"
                >
                    <h2 class="p-6 text-gray-900 text-xl text-center">
                        ¡Bienvenido al Panel de Control! <br />
                        <span class="font-bold">{{
                            $page.props.auth.user.name
                        }}</span>
                    </h2>
                    <DangerButton class="mr-2 mt-2 h-8" @click="closeWelcome"
                        >X</DangerButton
                    >
                </div>

                <div
                    class="lg:flex justify-evenly mt-2 gap-4 mx-auto lg:mx-0"
                >
                    <div class="grid grid-cols-2 lg:hidden gap-4">
                        <TotalOrders :totalOrders="totalOrdersToday" />
                        <TotalRevenue :totalRevenue="totalIncomeToday" />
                    </div>
                    <div class="hidden lg:block w-full">
                        <TotalOrders :totalOrders="totalOrdersToday" />
                    </div>
                    <div class="hidden lg:block w-full">
                        <TotalRevenue :totalRevenue="totalIncomeToday" />
                    </div>
                    <div class="hidden lg:block w-full">
                        <TotalCancelled :totalCancelled="totalCancelledToday" />
                    </div>

                    <div class="py-4 lg:hidden">
                        <TotalCancelled :totalCancelled="totalCancelledToday" />
                    </div>
                </div>
                <div class="hidden lg:flex gap-4">
                    <div
                        class="mt-4 w-full overflow-y-auto h-svh mx-auto scrollbar-hide 2xl:h-svh"
                    >
                        <h2
                            class="text-lg font-bold text-white text-center mb-2"
                        >
                            Todas las órdenes
                        </h2>
                        <AllOrders :orders="allOrders" @select="selectOrder" />
                    </div>
                    <div class="mt-2 w-full flex flex-col gap-4 overflow-y-auto h-svh 2xl:max-h-full scrollbar-hide">
                        <SelectedOrder :order="selectedOrder" />
                        <OrderDetails :order="selectedOrder" :RoomServiceConfig="RoomServiceConfig" />
                    </div>
                </div>
                <div class="flex flex-col lg:hidden gap-4">
                    <SelectedOrder :order="selectedOrder" />
                    <div class="overflow-y-auto h-96 scrollbar-hide">
                        <h2
                            class="text-lg font-bold text-white text-center mb-2"
                        >
                            Todas las órdenes
                        </h2>
                        <AllOrders :orders="allOrders" @select="selectOrder" />
                    </div>

                    <OrderDetails :order="selectedOrder" :RoomServiceConfig="RoomServiceConfig" />
                </div>
            </div>
    </AuthenticatedLayout>
</template>
