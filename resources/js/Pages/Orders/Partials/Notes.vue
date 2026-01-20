<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    notes: String,
});

const emit = defineEmits(["update:notes"]);

const notesList = ref(props.notes ? props.notes.split("\n").filter(n => n.trim()) : []);
const currentInput = ref("");
const openInput = ref(false);

watch(notesList, (val) => {
    emit("update:notes", val.join("\n"));
}, { deep: true });

const addNote = () => {
    if (currentInput.value.trim()) {
        notesList.value.push(currentInput.value.trim() + ", " + "\n");
        currentInput.value = "";
    }
};

const removeNote = (index) => {
    notesList.value.splice(index, 1);
};

const editNote = (index) => {
    currentInput.value = notesList.value[index];
    notesList.value.splice(index, 1);
};

const handleKeyDown = (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        addNote();
    }
};
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
            enter-to-class="opacity-100 max-h-96"
            leave-active-class="transition-all duration-200"
            leave-from-class="opacity-100 max-h-96"
            leave-to-class="opacity-0 max-h-0"
        >
            <div v-if="openInput" class="mt-2 overflow-hidden">
                <input
                    v-model="currentInput"
                    type="text"
                    class="w-64 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ej: Sin sal, extra picante, etc."
                    @keydown="handleKeyDown"
                />
                <ul class="mt-3 space-y-1">
                    <li
                        v-for="(note, index) in notesList"
                        :key="index"
                        class="flex items-center justify-between bg-blue-50 p-2 rounded border border-blue-200"
                    >
                        <span class="text-sm text-gray-700">{{ note }}</span>
                        <div>
                        <button
                            @click="editNote(index)"
                            class="ml-2 text-red-500 hover:text-red-700 font-bold text-sm"
                        >✎
                        </button>
                        <button
                            @click="removeNote(index)"
                            class="ml-2 text-red-500 hover:text-red-700 font-bold text-sm"
                        >
                            X
                        </button>
                        </div>
                    </li>
                </ul>
            </div>
        </transition>
    </div>
</template>
