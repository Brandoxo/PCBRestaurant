<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ListRooms from "@/Pages/Rooms/Partials/ListRooms.vue";
import { Head, Link } from "@inertiajs/vue3";
const props = defineProps({ rooms: Object });
console.log(props.rooms.data);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Rooms" />
        <section class="px-4 p-4 overflow-y-auto mx-auto scrollbar-hide">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-white text-2xl font-bold">Listado de Habitaciones</h2>
                <Link :href="route('Rooms/Create')" class="bg-softBlue hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Nueva Habitación</Link>
            </div>
            <ListRooms :rooms="rooms" />
        </section>
    </AuthenticatedLayout>
</template>