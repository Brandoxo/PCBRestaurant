<script setup>
import { ref } from 'vue';

const search = ref('');
const props = defineProps({ mesas: Object });
</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <form>
            <input type="text" placeholder="Buscar mesa..." class="border p-2 rounded-lg mb-4" />
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
                    <td class="border p-2 text-center">{{ value.num }}</td>
                    <td class="border p-2 text-center">{{ value.status }}</td>
                    <td class="border p-2 text-center">{{ value.capacity }}</td>
                    <td class="border p-2 flex gap-2 justify-center">
                        <button class="bg-blue-500 text-white p-1 rounded">Editar</button>
                        <button class="bg-red-500 text-white p-1 rounded">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>