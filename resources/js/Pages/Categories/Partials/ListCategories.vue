<script setup>
import { ref } from 'vue';

const search = ref('');
const props = defineProps({ categories: Object });
</script>

<template>
    <div class="bg-white p-4">
        <table class="w-full mt-2 border">
            <thead>
            <tr class="bg-gray-100">
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="value in categories.data" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">{{ value.NAME }}</td>
                    <td class="border p-2 text-center">{{ value.DESCRIPTION }}</td>
                    <td class="border p-2 flex gap-2 justify-center">
                        <button class="bg-blue-500 text-white p-1 rounded">Editar</button>
                        <button class="bg-red-500 text-white p-1 rounded">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>