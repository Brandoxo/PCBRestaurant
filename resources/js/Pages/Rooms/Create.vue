<script setup>
import { ref } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ReturnButton from '@/Components/ReturnButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Input } from 'postcss';

const props = defineProps({ rooms: Object });
console.log(props)

const newRoom = ref({
    number: '',
    prefix: '',
    name: 'Habitación',
    capacity: '4',
    status: 'Libre',
    created_at: '',
    updated_at: ''
});

const createTable = () => {
        //if the number is repeated on database
        if (newRoom.value.number === props.rooms.find(table => table.number === newRoom.value.number)?.number) {
            document.querySelector('.text-red-500').classList.remove('hidden');
            return;
        }
    router.post('/Rooms', newRoom.value, {
        onSuccess: () => {
            useToast().success('Habitación creada exitosamente');
            showModal.value = false;
            newRoom.value = { number: '', prefix: '', name: '', status: 'Libre', capacity: '4' };
        },
        onError: (error) => {
            useToast().error('Error al crear la habitación');
            console.error('Error al crear habitación:', error);
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
                        <InputLabel class="block text-sm font-medium text-black mb-2" value="Prefijo" />
                        <input type="text" class="border p-2 rounded-lg mb-4" v-model="newRoom.prefix" />   
                        <InputLabel class="block text-sm font-medium text-black mb-2" value="Número de habitación" />
                        <input type="number" class="border p-2 rounded-lg mb-4" v-model="newRoom.number" />
                        <span class="text-red-500 hidden">El número de habitación ya está en uso.</span>

                    <!-- <InputLabel class="block text-sm font-medium text-black mb-2" value="Nombre" />
                    <input type="text" class="border p-2 rounded-lg mb-4" v-model="newRoom.name" /> -->
                <InputLabel class="block text-sm font-medium text-black mb-2" value="Estado" />
                <select v-model="newRoom.status" class="border p-2 rounded-lg">
                    <option value="" disabled selected>Selecciona un estado</option>
                    <option value="Libre">Libre</option>
                    <option value="Reservada">Reservada</option>
                </select>
                <button @click="submit" class="bg-approveGreen hover:bg-green-700 text-white px-4 py-2 rounded-lg self-end">Guardar Habitación</button>
            </form>
            </div>
            <ReturnButton />
        </div>
    </AuthenticatedLayout>
</template>