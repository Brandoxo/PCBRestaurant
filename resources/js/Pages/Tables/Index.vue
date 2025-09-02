<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Head, Link } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ListTables from './Partials/ListTables.vue';
import Modal from '@/Components/Modal.vue';


const props = defineProps({ mesas: Object });
const page = usePage();

const showModal = ref(false);
const clickOutside = (event) => {
    const modal = document.getElementById('modal');
    if (modal && !modal.contains(event.target)) {
        showModal.value = false;
    }
};

const createTable = () => {
    // Aquí puedes agregar la lógica para crear una nueva mesa
    console.log('Crear nueva mesa:', newTable.value);
    showModal.value = false; // Cerrar el modal después de crear la mesa
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Mesas" />
            <Header></Header>
        <section class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-white text-2xl font-bold">Listado de Mesas</h2>
                <button @click="showModal = true" class="bg-blue-500 text-white px-4 py-2 rounded">+ Nueva Mesa</button>
            </div>
            <ListTables :mesas="mesas" />
        </section>
    </AuthenticatedLayout>

    <Modal v-model:show="showModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Registrar Nueva Mesa</h2>
            <form @submit.prevent="createTable" class="space-y-2 flex flex-col">
                <label class="block text-sm font-medium text-gray-700 mb-2">Numero de mesa</label>
                <input type="number" class="border p-2 rounded-lg mb-4" />
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select class="border p-2 rounded-lg">
                    <option value="available">Libre</option>
                    <option value="occupied">Ocupada</option>
                    <option value="reserved">Reservada</option>
                </select>
                <label class="block text-sm font-medium text-gray-700 mb-2">Capacidad</label>
                <input type="number" class="border p-2 rounded-lg mb-4" />
                <div class="flex justify-between ">
                <button type="button" @click="showModal = false" class="bg-red-500 text-white px-4 py-2 rounded w-1/4">Cancelar</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-1/4 self-end">Guardar Mesa</button>
                </div>
            </form>
        </div>
    </Modal>

</template>
