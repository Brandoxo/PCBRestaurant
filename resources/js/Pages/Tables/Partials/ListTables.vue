<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import Modal from '@/Components/Modal.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import CancelButton from '@/Components/CancelButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

const form = ref({ errors: {} });

const props = defineProps({ mesas: Object });
const openModal = ref(false);
const currentTable = ref({});

const closeModal = () => {
    openModal.value = false;
};

const showModal = (id) => {
    const foundTable = props.mesas.data.find((table) => table.id === id);
    currentTable.value = foundTable
        ? { ...foundTable }
        : { id, number: '', status: '', capacity: '' };
    openModal.value = true;
};

const editTable = (id) => {
    if (currentTable.value.number === props.mesas.data.find(table => table.number === currentTable.value.number && table.id !== id)?.number) {
        document.querySelector('.text-red-500').classList.remove('hidden');
        return;
    }
    router.put(`/Tables/edit/${id}`, currentTable.value, {
        onSuccess: () => {
            useToast().success('Mesa actualizada exitosamente');
            openModal.value = false;
        },
        onError: (error) => {
            useToast().error('Error al actualizar la mesa');
            console.error('Error al actualizar mesa:', error);
        }
    });
};

const deleteTable = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar esta mesa?')) {
        router.delete(`/Tables/${id}`, {
            onSuccess: () => {
                useToast().success('Mesa eliminada exitosamente');
            },
            onError: (error) => {
                useToast().error('Error al eliminar mesa');
                console.error('Error al eliminar mesa:', error);
            }
        });
    }
};

const searchTable = (query) => {
    const filtered = props.mesas.data.filter((table) =>
        table.number.toString().includes(query)
    );
    return filtered;
};

</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <form @submit.prevent="searchTable">
            <input type="text" placeholder="Buscar mesa..." class="border p-2 rounded-lg w-full mb-4" />
        </form>
        <table class="w-full mt-2 border">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-4">#</th>
                <th class="p-4">Numero</th>
                <th class="p-4">Estado</th>
                <th class="p-4">Capacidad</th>
                <th class="p-4">Acciones</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="value in mesas.data" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">{{ value.number }}</td>
                    <td class="border p-2 text-center"><StatusBadge :status="value.status" /></td>
                    <td class="border p-2 text-center">{{ value.capacity }} Personas</td>
                    <td class="border p-2 flex gap-2 justify-center">
                        <button @click="showModal(value.id)" class="bg-midBlue hover:bg-strongBlue text-white p-1 rounded"><img src="/assets/icons/svg/actions/edit.svg" alt="Editar" class="w-6"></button>
                        <button @click="deleteTable(value.id)" class="bg-dangerRed hover:bg-red-700 text-white p-1 rounded"><img src="/assets/icons/svg/actions/delete.svg" alt="Eliminar" class="w-6"></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

        <Modal v-model:show="openModal">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Editar Categoría</h3>
                <form class="flex flex-col">
                    <div>
                        <InputLabel for="number" value="Número de Mesa" class="mb-2"></InputLabel>
                        <input v-model="currentTable.number" type="text" placeholder="Numero de Mesa" class="border p-2 rounded-lg mb-4 w-full" />
                        <InputError :message="form.errors.number"></InputError>
                        <span class="text-red-500 hidden">Este número de mesa ya está en uso.</span>
                    </div>

                    <div>
                    <InputLabel class="block text-sm font-medium text-black mb-2">Estado</InputLabel>
                    <select v-model="currentTable.status" class="border p-2 rounded-lg mb-4">
                        <option value="" disabled selected>Selecciona un estado</option>
                        <option value="Libre">Libre</option>
                        <option value="Ocupada">Ocupada</option>
                        <option value="Reservada">Reservada</option>
                    </select>
                    <InputError :message="form.errors.status"></InputError>
                    </div>

                    <div>
                        <InputLabel for="capacity" value="Capacidad"></InputLabel>
                        <input v-model="currentTable.capacity" type="text" placeholder="Capacidad" class="border p-2 rounded-lg mb-4" />
                        <InputError :message="form.errors.capacity"></InputError>
                    </div>
                    <div class="flex justify-between">
                        <CancelButton @click="closeModal">Cancelar</CancelButton>
                        <button @click="editTable(currentTable.id)" type="button" class="bg-approveGreen hover:bg-green-600 text-white px-4 py-2 rounded-lg self-end">Guardar Cambios</button>
                    </div>
                    
                </form>
            </div>
        </Modal>
</template>