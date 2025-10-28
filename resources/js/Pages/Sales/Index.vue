<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ListSales from "./Partials/ListSales.vue";
import { Link, usePage, Head } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    sales: Object,
    cashFloat: Object,
    shifts: Object,
});
const user = usePage().props.auth.user

const canViewCutOff = computed(() => {
    return user && user.permissions && user.permissions.includes('view cutoff')
})

console.log(props.sales);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Ventas" />
        <section class="px-4 p-4 overflow-y-auto mx-auto scrollbar-hide">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-white text-2xl font-bold">Listado de Ventas</h2>
                <Link :href="route('CashAudit/Index')"
                v-if="canViewCutOff"
                class="bg-blue-500 text-white px-4 py-2 rounded">Ver lista de Cortes</Link>
            </div>

            <ListSales :sales="sales" :cashFloat="cashFloat" :shifts="shifts" />
        </section>
    </AuthenticatedLayout>
</template>
