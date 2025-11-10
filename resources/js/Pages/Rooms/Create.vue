<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReturnButton from '@/Components/ReturnButton.vue';

const props = defineProps({ mesas: Object });
console.log(props)

const newTable = ref({
    number: '',
    status: 'Libre',
    capacity: '4',
    created_at: '',
    updated_at: ''
});

const createTable = () => {
        //if the number is repeated on database
        if (newTable.value.number === props.mesas.find(table => table.number === newTable.value.number)?.number) {
            document.querySelector('.text-red-500').classList.remove('hidden');
            return;
        }
    router.post('/Tables', newTable.value, {
        onSuccess: () => {
            useToast().success('Mesa creada exitosamente');
            showModal.value = false;
            newTable.value = { number: '', status: '', capacity: '4' };
        },
        onError: (error) => {
            useToast().error('Error al crear la mesa');
            console.error('Error al crear mesa:', error);
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-6 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 space-y-6 ">
            <h2 class=" text-white mb-4 font-bold text-2xl">Registrar Nueva Habitación</h2>
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <form @submit.prevent="createTable" class="space-y-2 flex flex-col">
                    <label class="block text-sm font-medium text-black mb-2">Número de habitación</label>
                    <input type="number" class="border p-2 rounded-lg mb-4" v-model="newTable.number" />
                    <span class="text-red-500 hidden">El número de habitación ya está en uso.</span>
                <label class="block text-sm font-medium text-black mb-2">Estado</label>
                <select v-model="newTable.status" class="border p-2 rounded-lg">
                    <option value="" disabled selected>Selecciona un estado</option>
                    <option value="Libre">Libre</option>
                    <option value="Reservada">Reservada</option>
                </select>
                <button type="submit" class="bg-approveGreen hover:bg-green-700 text-white px-4 py-2 rounded-lg self-end">Guardar Habitación</button>
            </form>
            </div>
            <ReturnButton />
        </div>
    </AuthenticatedLayout>
</template>