<script setup>
import Header from "@/Components/Header.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ListSales from "./Partials/ListSales.vue";
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    sales: Object,
});
const user = usePage().props.auth.user

const canViewCutOff = computed(() => {
    return user && user.permissions && user.permissions.includes('view cutoff')
})

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
                <Link :href="route('CashAudit/Index')"
                v-if="canViewCutOff"
                class="bg-blue-500 text-white px-4 py-2 rounded">Ver lista de Cortes</Link>
            </div>

            <ListSales :sales="sales" />
        </section>
    </AuthenticatedLayout>
</template>
