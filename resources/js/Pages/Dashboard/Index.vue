<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import TotalOrders from "@/Pages/Dashboard/Partials/TotalOrders.vue";
import TotalRevenue from "@/Pages/Dashboard/Partials/TotalRevenue.vue";
import TotalCancelled from "@/Pages/Dashboard/Partials/TotalCancelled.vue";
import { ref } from "vue";
import DangerButton from "@/Components/DangerButton.vue";
import AllOrders from "@/Pages/Dashboard/Partials/AllOrders.vue";


const isWelcomeVisible = ref(true);

//only when the user is logged in for the first time
const closeWelcome = () => {
    let count = 0;
    isWelcomeVisible.value = false;
    count++;
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <Header></Header>

        <div class="py-12 ">
            <div class="mx-auto sm:px-6 lg:px-8 ml-64">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg w-fit flex justify-between mx-auto" v-if="isWelcomeVisible && count === 0">
                    <h2 class="p-6 text-gray-900 text-xl text-center">
                        ¡Bienvenido al Panel de Control! <br /> <span class="font-bold">{{ $page.props.auth.user.name }}</span>
                    </h2>
                    <DangerButton class="mr-2 mt-2 h-8" @click="closeWelcome">X</DangerButton>
                </div>

                <div class="md:flex justify-between gap-2 md:gap-8 lg:gap-14  2xl:gap-60 mt-6 ">
                    <TotalOrders :totalOrders="totalOrders" />
                    <TotalRevenue :totalRevenue="totalRevenue" />
                    <TotalCancelled :totalCancelled="totalCancelled" />
                </div>
                <div class="mt-6 w-1/3">
                    <AllOrders :orders="allOrders" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
