<template>
    <div
        :class="`px-2 py-1 text-xs font-semibold rounded-full w-28 text-center flex items-center justify-center ${statusClasses[status]}`"
    >
        <span>{{ status }}</span>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const statusClasses = {
    Libre: "bg-approveGreen/70 text-white mx-auto",
    Reservada: "bg-goodYellow text-white mx-auto",
    Ocupada: "bg-dangerRed text-white mx-auto",
    Completada: "bg-approveGreen/70 text-white",
    "En curso": "bg-yellow-500 text-white",
    Cancelada: "bg-red-500 text-white",
    Activo: "bg-approveGreen text-white mx-auto",
    Inactivo: "bg-dangerRed text-white mx-auto",
};
</script>
