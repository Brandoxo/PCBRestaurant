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
    totalOrders: Number,
    sales: Array,
    orders: Array,
});

console.log("Sales prop in <Dashboard>: ", props.sales);

const d = new Date();
const date_today = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
const shifts = ["Matutino", "Vespertino"];
console.log("Today's date: ", date_today);

const totalIncome = computed(() => {
    return props.sales
        .filter(sales => new Date(sales.date_time).getFullYear() === d.getFullYear() && new Date(sales.date_time).getMonth() === d.getMonth() && new Date(sales.date_time).getDate() === d.getDate())
        .reduce((sum, sales) => sum + parseFloat(sales.subtotal), 0);
});

console.log("Total Income: ", totalIncome.value);

const totalCancelled = computed(() => {
    return (props.orders || []).filter((order) => order.status === "Cancelada")
        .length;
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
        <div class="py-20 h-screen overflow-y-auto">
            <div class="mx-auto sm:px-6 lg:px-8 lg:ml-64">
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
                    class="lg:flex justify-center gap-2 lg:gap-8 2xl:gap-60 mt-6"
                >
                    <div class="grid grid-cols-2 gap-2 lg:hidden">
                        <TotalOrders :totalOrders="props.totalOrders" />
                        <TotalRevenue :totalRevenue="totalIncome" />
                    </div>
                    <div class="hidden lg:block w-full">
                        <TotalOrders :totalOrders="props.totalOrders" />
                    </div>
                    <div class="hidden lg:block w-full">
                        <TotalRevenue :totalRevenue="totalIncome" />
                    </div>
                    <div class="hidden lg:block w-full">
                        <TotalCancelled :totalCancelled="totalCancelled" />
                    </div>

                    <div class="py-4 lg:hidden">
                        <TotalCancelled :totalCancelled="totalCancelled" />
                    </div>
                </div>
                <div class="hidden lg:flex gap-4">
                    <div
                         class="mt-6 min-w-96 2xl:min-w-[900px] overflow-y-auto h-[36rem] 2xl:h-screen mx-auto scrollbar-hide"
                    >          <h2 class="text-lg font-bold text-white text-center mb-2">
                                    Todas las órdenes
                                </h2>
                        <AllOrders :orders="allOrders" @select="selectOrder" />
                    </div>
                    <div class="mt-6 w-2/4 flex flex-col gap-4 mx-auto">
                        <SelectedOrder :order="selectedOrder" />
                        <OrderDetails :order="selectedOrder" />
                    </div>
                </div>
                <div class="flex flex-col lg:hidden gap-4">
                    <SelectedOrder :order="selectedOrder" />
                    <div class="overflow-y-auto h-96 scrollbar-hide">
                            <h2 class="text-lg font-bold text-white text-center mb-2">
                                Todas las órdenes
                            </h2>
                        <AllOrders :orders="allOrders" @select="selectOrder" />
                    </div>

                    <OrderDetails :order="selectedOrder" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
