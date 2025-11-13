<script setup>
import { computed, onMounted, ref, toRefs } from "vue";
import GenerateOrderButton from "@/Components/GenerateOrderButton.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import { useToast } from "vue-toastification";
import { isRoomServiceEnabled } from "@/utils/isRoomServiceEnabled";

const emit = defineEmits(["tableSelected"]);
const props = defineProps({
    tables: Object,
    selectedTable: Object,
    rooms: Object
});
console.log('Aqui tan las rooms sr', props.rooms)
const roomServiceEnabled = isRoomServiceEnabled;
const { tables, rooms } = toRefs(props);

const combinedList = computed(() => {
    const t = (tables.value) || {};
    const r = (rooms.value) || {};
    
     const showRooms =
        roomServiceEnabled && roomServiceEnabled.value !== undefined
            ? roomServiceEnabled.value
            : !!roomServiceEnabled;
        
     const tablesList = Object.keys(t).map((key) => ({
        id: key,
        isRoom: false,
        // display label para la UI
        label: (t[key] && t[key].room && t[key].room.name) ? t[key].room.name : 'Mesa',
        ...t[key],
    }));

    const roomsList = showRooms
        ? Object.keys(r).map((key) => ({
              // prefija id para evitar colisiones con mesas
              id: `room-${key}`,
              isRoom: true,
              label: r[key].name || `Habitación ${r[key].number || key}`,
              // normaliza propiedades esperadas por la plantilla
              number: r[key].number || r[key].name || '',
              capacity: r[key].capacity || 1,
              status: r[key].status || 'Libre',
              ...r[key],
          }))
        : [];

    return [...tablesList, ...roomsList];
 });
console.log('Lista combinada',combinedList.value);


function openOptions(table) {
    // Evitar seleccionar una mesa que ya tiene una orden activa
    if (table.status !== "Libre" && table.status !== "Reservada") {
        useToast().error("No puedes seleccionar una mesa ocupada.");
        return;
    }
    emit("tableSelected", table);
    window.scrollTo({ top: "0%", behavior: "smooth" });
    useToast().info(`Seleccionando orden para la ${table.isRoom ? 'Habitación' : 'Mesa'} #${table.number}`);
    console.log("Tables data:", table);
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
            v-for="value in combinedList"
            v-show="!hiddeDiv(value)"
            :key="value.id"
            :class="[
                'bg-white rounded-xl items-center p-4 space-y-2 shadow-lg',
                value.status !== 'Libre' && value.status !== 'Reservada'
                    ? 'opacity-50 pointer-events-none scale-90'
                    : 'cursor-pointer hover:scale-[1.02] pointer-events-auto',
                props.selectedTable && props.selectedTable.id === value.id
                    ? 'pointer-events-auto'
                    : '',
            ]"
        >
            <div>
                <StatusBadge :status="value.status" />
            </div>
            <div class="flex justify-evenly items-center mb-4 gap-8">
                <h2 class="text-m uppercase font-black">{{ value.isRoom ? 'Habitación' : 'Mesa' }}</h2>
                <img
                    :src="value.isRoom ? '/assets/icons/svg/menu/rooms.svg' : '/assets/icons/svg/menu/table.svg'"
                    :alt="value.isRoom ? 'Habitación' : 'Mesa'"
                    class="w-16"
                />
                <span class="font-extrabold text-xl text-red-900"
                    >#{{isRoomServiceEnabled ? value.prefix : ''}}-{{ value.number }}</span
                >
            </div>
            <div
            v-if="!value.isRoom"
            class="flex justify-center items-center gap-2">
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
            <GenerateOrderButton
                @click="openOptions(value)"
                :disabled="
                    value.status !== 'Libre' && value.status !== 'Reservada'
                "
            >
                Generar Orden
            </GenerateOrderButton>
        </div>
    </div>
</template>
