<script setup>
import { ref } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TablesAvailables from "./Partials/TablesAvailables.vue";
import MakeOrder from "./Partials/MakeOrder.vue";
import OrderDetails from "./Partials/OrderDetails.vue";

const selectedTable = ref(null);
const { props } = usePage();
const tables = ref(props.tables);

const orders = ref(props.orders);
const currentOrder = ref({
    mesa_id: null,
    items: []
});

function handleCloseOrder() {
    selectedTable.value = null;
}

function handleOrderSaved(newOrder) {
  orders.value.push(newOrder);
  currentOrder.value = {
    mesa_id: null,
    items: []
  };
}

function tableSelected(table) {
  selectedTable.value = table;
}
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Ordenes"/>
        <section class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6 flex justify-center">
            <div class="flex flex-col">
                <TablesAvailables :selectedTable="selectedTable" :tables="tables" @tableSelected="tableSelected" />

                <OrderDetails v-if="selectedTable" :selectedTable="selectedTable" :orders="orders" :currentOrder="currentOrder"/>
            </div>

                <MakeOrder :selectedTable="selectedTable" :tables="tables" :orders="orders" :currentOrder="currentOrder" @closeOptions="handleCloseOrder" @handleOrderSaved="handleOrderSaved"/>
        </section>
    </AuthenticatedLayout>
</template>