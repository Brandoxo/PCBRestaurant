<script setup>
import { ref, watch } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TablesAvailables from "./Partials/TablesAvailables.vue";
import MakeOrder from "./Partials/MakeOrder.vue";
import OrderDetails from "./Partials/OrderDetails.vue";

const { props } = usePage();
const selectedTable = ref(null);
const tables = ref(props.tables);
const rooms = ref(props.rooms);
const orders = ref(props.orders);
// --- ADDED ---
const localNotes = ref(props.notes ?? "");
const currentOrder = ref({
    mesa_id: null,
    items: [],
    notes: "",
});

function handleCloseOrder() {
    selectedTable.value = null;
}

function handleOrderSaved(newOrder) {
    orders.value.push(newOrder);
    currentOrder.value = {
        mesa_id: null,
        items: [],
        notes: "",
    };
    selectedTable.value = null;
}

function tableSelected(table) {
    selectedTable.value = table;
}
</script>
<template>
    <AuthenticatedLayout>
        <Head title="Ordenes" />
        <section
            class="px-4 p-8 overflow-y-auto scrollbar-hide lg:py-60 lg:h-screen lg:pt-5"
        >
            <div
                class="lg:flex lg:space-x-6 lg:items-start lg:justify-center lg:w-full mx-auto"
            >
                <div class="lg:flex-col lg:mx-auto">
                    <TablesAvailables
                        :selectedTable="selectedTable"
                        :tables="tables"
                        :rooms="rooms"
                        @tableSelected="tableSelected"
                        v-model:notes="localNotes"
                    />
                    <Notes :notes="props.notes" />
                    <OrderDetails
                        v-if="selectedTable"
                        :selectedTable="selectedTable"
                        :orders="orders"
                        :currentOrder="currentOrder"
                    />
                </div>
                <MakeOrder
                    :selectedTable="selectedTable"
                    :tables="tables"
                    :orders="orders"
                    :currentOrder="currentOrder"
                    :localNotes="localNotes"
                    @closeOptions="handleCloseOrder"
                    @handleOrderSaved="handleOrderSaved"
                />
            </div>
        </section>
    </AuthenticatedLayout>
</template>
