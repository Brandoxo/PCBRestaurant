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
        <section class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6 ">
            <div class="flex justify-between items-center">
                <h2 class="text-white text-2xl font-bold">Listado de Mesas</h2>
                <Link :href="route('Tables/Create')" class="bg-softBlue hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Nueva Mesa</Link>
            </div>
            <ListTables :mesas="mesas" />
        </section>
    </AuthenticatedLayout>
</template>
