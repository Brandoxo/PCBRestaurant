<script setup>
import { onMounted, ref } from "vue";
import GenerateOrderButton from "@/Components/GenerateOrderButton.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import { useToast } from "vue-toastification";

const emit = defineEmits(["tableSelected"]);
const props = defineProps({
    tables: Object,
    selectedTable: Object,
});

function openOptions(table) {
    emit("tableSelected", table);
    props.selectedTable = table;
    window.scrollTo({ top: "0%", behavior: "smooth" });
    useToast().info(`Seleccionando orden para la Mesa #${table.number}`);
    console.log("Tables data:", props.selectedTable);
}

const hiddeDiv = (div) => {
    if (!props.selectedTable) {
        return false;
    }
    return props.selectedTable && props.selectedTable.id !== div.id;
};

onMounted(() => {
    console.log("Tables data:", props.tables);
});
</script>

<template>
    <div
        :class="[
            props.selectedTable
                ? 'animate-pulse pointer-events-none transition-transform duration-300 ease-in-out min-w-80 2xl:w-1/4'
                : 'grid sm:grid-cols-2  lg:grid-cols-3 2xl:grid-cols-4 gap-6 2xl:gap-10 overflow-y-auto scrollbar-hide scroll-smooth w-full',
        ]"
    >
        <div
            v-for="value in tables.filter(
                (t) => t.status === 'Libre' || t.status === 'Reservada'
            )"
            :hidden="hiddeDiv(value)"
            :block="!hiddeDiv(value)"
            :key="value.id"
            :class="[
                'bg-white rounded-xl items-center p-4 space-y-2 shadow-lg cursor-pointer hover:scale-[1.02] transform transition-transform duration-300 ease-in-out',
                props.selectedTable && props.selectedTable.id === value.id
                    ? 'pointer-events-auto'
                    : '',
            ]"
        >
            <div>
                <StatusBadge :status="value.status" />
            </div>
            <div class="flex justify-between gap-8 items-center mb-4">
                <h2 class="text-xl uppercase font-black">Mesa</h2>
                <img
                    src="/assets/icons/svg/menu/table.svg"
                    alt="Mesa"
                    class="w-16"
                />
                <span class="font-extrabold text-4xl text-red-900"
                    >#{{ value.number }}</span
                >
            </div>
            <div class="flex justify-center items-center gap-2">
                <img
                    src="/assets/icons/svg/visual/people.svg"
                    alt="Capacidad"
                    class="inline-block w-4 h-4"
                />
                <p class="text-center text-gray-500">
                    Capacidad para
                    <span class="font-bold text-lg text-gray-600">{{
                        value.capacity
                    }}</span>
                    personas
                </p>
            </div>
            <GenerateOrderButton @click="openOptions(value)"
                >Generar Orden</GenerateOrderButton
            >
        </div>
    </div>
</template>
