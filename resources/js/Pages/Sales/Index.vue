<script setup>
import Header from "@/Components/Header.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ListSales from "./Partials/ListSales.vue";

const props = defineProps({
    sales: Object,
});

console.log(props.sales);
</script>

<template>
    <AuthenticatedLayout>
        <Header title="Ventas" />
        <section
            class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6"
        >
            <div class="flex justify-between items-center">
                <h2 class="text-white text-2xl font-bold">Listado de Ventas</h2>
            </div>
            <ListSales :sales="sales" />
        </section>
    </AuthenticatedLayout>
</template>
