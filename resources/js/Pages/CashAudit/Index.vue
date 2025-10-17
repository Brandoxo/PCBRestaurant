<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ListCashAudits from './Partials/ListCashAudits.vue';

const props = defineProps({
    cashAudits: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <section class="py-60 h-screen overflow-y-auto mx-auto p-6 space-y-6 pt-6">
            <div class="flex justify-between items-center">
                <h2 class="text-white text-2xl font-bold">Auditorías de Caja</h2>
            </div>
            <ListCashAudits :cashAudits="cashAudits" />
        </section>
    </AuthenticatedLayout>
</template>