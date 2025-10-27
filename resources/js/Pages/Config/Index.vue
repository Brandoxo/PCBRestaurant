<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ShiftSchedules from './Partials/ShiftSchedules.vue';
import Discounts from './Partials/Discounts.vue';
import CashFloat from './Partials/CashFloat.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    shifts: Array,
    cashFloats: Array,
});


</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight ">
                Configuración
            </h2>
        </template>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-screen overflow-y-auto py-60 pt-0">
                <div class=" overflow-hidden shadow-sm sm:rounded-lg gap-6 flex flex-col">
                    <CashFloat :cashFloats="props.cashFloats" />
                    <ShiftSchedules :shifts="props.shifts"/>
                    <Discounts />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>