<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    notes: String,
});

const emit = defineEmits(["update:notes"]);

const localNotes = ref(props.notes ?? "");
const openInput = ref(false);

watch(localNotes, (val) => {
    emit("update:notes", val);
});
</script>
<template>
    <div
        class="bg-white w-fit h-fit rounded-b-xl mx-auto items-center px-2 shadow-lg mt-1"
    >
        <button
            class="text-sm font-medium text-center text-blue-500 underline cursor-pointer flex items-center justify-center w-full uppercase"
            @click="openInput = !openInput"
        >
            <span v-if="!openInput">Añadir Nota</span>
            <span v-else>Ocultar Nota</span>
        </button>
        <transition
            enter-active-class="transition-all duration-300"
            enter-from-class="opacity-0 max-h-0"
            enter-to-class="opacity-100 max-h-40"
            leave-active-class="transition-all duration-200"
            leave-from-class="opacity-100 max-h-40"
            leave-to-class="opacity-0 max-h-0"
        >
            <div v-if="openInput" class="mt-2 overflow-hidden">
                <ul>
                    <li>
                        <textarea
                            v-model="localNotes"
                            class="w-64 h-32 p-2 border border-gray-300 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ej: Sin sal, extra picante, etc."
                        ></textarea>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>
