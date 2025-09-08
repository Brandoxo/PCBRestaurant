<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from 'vue-toastification';

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
        <div class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6 ">
            <h2 class=" text-white mb-4 font-bold text-2xl">Registrar Nueva Mesa</h2>
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <form @submit.prevent="createTable" class="space-y-2 flex flex-col">
                    <label class="block text-sm font-medium text-black mb-2">Número de mesa</label>
                    <input type="number" class="border p-2 rounded-lg mb-4" v-model="newTable.number" />
                    <span class="text-red-500 hidden">El número de mesa ya está en uso.</span>
                <label class="block text-sm font-medium text-black mb-2">Estado</label>
                <select v-model="newTable.status" class="border p-2 rounded-lg">
                    <option value="" disabled selected>Selecciona un estado</option>
                    <option value="Libre">Libre</option>
                    <option value="Reservada">Reservada</option>
                </select>
                <button type="submit" class="bg-approveGreen hover:bg-green-700 text-white px-4 py-2 rounded-lg self-end">Guardar Mesa</button>
            </form>
            </div>
            <div>
            <Link href="/Tables" class="bg-red-500 hover:bg-red-700 text-white p-2 rounded-lg px-8"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 0l3-3m-3 3l3 3" /></svg>Volver Atrás</Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>