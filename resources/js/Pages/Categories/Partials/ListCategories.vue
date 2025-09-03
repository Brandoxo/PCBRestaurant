<script setup>
import { ref } from 'vue';

const search = ref('');
const props = defineProps({ categories: Object });
</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <table class="w-full mt-2 border">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-4">#</th>
                <th class="p-4">Nombre</th>
                <th class="p-4">Descripción</th>
                <th class="p-4">Acciones</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="value in categories.data" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">{{ value.name }}</td>
                    <td class="border p-2 text-center">{{ value.description }}</td>
                    <td class="border p-2 flex gap-2 justify-center">
                        <button @click="showModal(value.id)" class="bg-midBlue hover:bg-strongBlue text-white p-1 rounded"><img src="/assets/icons/svg/actions/edit.svg" alt="Editar" class="w-6"></button>
                        <button @click="deleteTable(value.id)" class="bg-dangerRed hover:bg-red-700 text-white p-1 rounded"><img src="/assets/icons/svg/actions/delete.svg" alt="Eliminar" class="w-6"></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>