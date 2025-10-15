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
        <section class="px-4 p-4 overflow-y-auto mx-auto scrollbar-hide">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-white text-2xl font-bold">Listado de Productos</h2>
                <Link :href="route('Menu/Create')" method="get" class="bg-softBlue text-white px-4 py-2 rounded-md">+ Nuevo Producto</Link>
            </div>
            <ListProducts :products="props.products" || :categories="props.categories"/>
        </section>
    </AuthenticatedLayout>
</template>