<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ListCashAudits from './Partials/ListCashAudits.vue';

const props = defineProps({
    cashAudits: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <section class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-white text-2xl font-bold">Auditorías de Caja</h2>
            </div>
            <ListCashAudits :cashAudits="cashAudits" />
        </section>
    </AuthenticatedLayout>
</template>