<script setup>
import { Link, Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Header from "@/Components/Header.vue";
import ListCategories from "./Partials/ListCategories.vue";
const props = defineProps({ categories: Object });
</script>

<template>
    <Head title="Categorías" />
    <AuthenticatedLayout>
        <section class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-white text-2xl font-bold">Listado de Categorías</h2>
                <Link :href="route('Categories/Create')" method="get" class="bg-softBlue text-white px-4 py-2 rounded-md">+ Nueva Categoría</Link>
            </div>
            <ListCategories :categories="categories" />
        </section>
    </AuthenticatedLayout>
</template>
