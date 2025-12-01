<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
const { props } = usePage();
const notes = props.notes;

const open = ref(false);


</script>
<template>
    <div class="bg-white w-fit h-fit rounded-b mx-auto items-center px-2">
        <button class="text-md font-medium text-center text-blue-500 underline cursor-pointer flex items-center justify-center" @click="open = !open">
            <span v-if="!open">Añadir Notas</span>
            <span v-else>Ocultar Notas</span>
        </button>
        <div v-if="open" class="mt-2 transition-all duration-300 ease-in-out">
            <input
                type="text"
                v-for="note in notes"
                :key="note.id"
                :value="note.content"
                readonly
                class="w-full px-3 py-2 mb-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
        </div>
    </div>
</template>