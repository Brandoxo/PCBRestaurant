<script setup>
import { ref } from "vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { defineProps } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ListProducts from "./Partials/ListProducts.vue";

const props = defineProps({ products: Object, categories: Object });

console.log(props.categories)
</script>

<template>
    <Head title="Menú" />
    <AuthenticatedLayout>
 <section class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-white text-2xl font-bold">Listado de Productos</h2>
                <Link :href="route('Menu/Create')" method="get" class="bg-softBlue text-white px-4 py-2 rounded-md">+ Nuevo Producto</Link>
            </div>
            <ListProducts :products="props.products" || :categories="props.categories"/>
        </section>
    </AuthenticatedLayout>
</template>