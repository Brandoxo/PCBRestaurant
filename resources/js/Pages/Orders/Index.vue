<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TablesAvailables from "./Partials/TablesAvailables.vue";
import MakeOrder from "./Partials/MakeOrder.vue";

const selectedTable = ref(null);
const { props } = usePage();
const orders = ref(props.orders);
const tables = ref(props.tables);

function tableSelected(table) {
  selectedTable.value = table;
}
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Ordenes"/>
        <section class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6 ">
            <h2 class="text-white text-2xl font-bold text-center">Selecciona una mesa disponible para generar una orden</h2>

            <div class="flex w-full h-full">
                <TablesAvailables :selectedTable="selectedTable" :tables="tables" @tableSelected="tableSelected"/>

                <MakeOrder :selectedTable="selectedTable" :tables="tables" :orders="orders" @closeOptions="tableSelected(null)"/>
            </div>
        </section>
    </AuthenticatedLayout>
</template>