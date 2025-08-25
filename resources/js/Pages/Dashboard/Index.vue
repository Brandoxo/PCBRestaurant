<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import TotalOrders from "@/Pages/Dashboard/Partials/TotalOrders.vue";
import TotalRevenue from "@/Pages/Dashboard/Partials/TotalRevenue.vue";
import TotalCancelled from "@/Pages/Dashboard/Partials/TotalCancelled.vue";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <Header></Header>

        <div class="py-12 ">
            <div class="mx-auto sm:px-6 lg:px-8 ml-64">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        ¡Bienvenido al Panel de Control!
                    </div>
                </div>

                <div class="md:flex justify-between gap-2 md:gap-8 lg:gap-14  2xl:gap-60 mt-6 ">
                    <TotalOrders :totalOrders="totalOrders" />
                    <TotalRevenue :totalRevenue="totalRevenue" />
                    <TotalCancelled :totalCancelled="totalCancelled" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
