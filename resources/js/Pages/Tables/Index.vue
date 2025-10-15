<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ListTables from './Partials/ListTables.vue';

const props = defineProps({ mesas: Object });
console.log(props.mesas.data);

const page = usePage();


</script>

<template>
    <AuthenticatedLayout>
        <Head title="Mesas" />
        <section class="px-4 p-4 overflow-y-auto mx-auto scrollbar-hide">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-white text-2xl font-bold">Listado de Mesas</h2>
                <Link :href="route('Tables/Create')" class="bg-softBlue hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Nueva Mesa</Link>
            </div>
            <ListTables :mesas="mesas" />
        </section>
    </AuthenticatedLayout>
</template>
