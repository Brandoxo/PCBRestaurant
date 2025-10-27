<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

const toast = useToast();

const props = defineProps({
    shifts: Array,
});
console.log('from the component',props);

// refs que contienen strings en formato "HH:mm"
const morningShiftStart = ref('');
const morningShiftEnd = ref('');
const eveningShiftStart = ref('');
const eveningShiftEnd = ref('');

// sincronizar con props.shifts cuando cambie
watch(
    () => props.shifts,
    (newShifts = []) => {
        const m = (newShifts || []).find(shift => shift.name_shift === 'Matutino') || {};
        const e = (newShifts || []).find(shift => shift.name_shift === 'Vespertino') || {};

        morningShiftStart.value = m.start_time || '';
        morningShiftEnd.value = m.end_time || '';
        eveningShiftStart.value = e.start_time || '';
        eveningShiftEnd.value = e.end_time || '';
    },
    { immediate: true, deep: true }
)

async function updateShift(shiftName, startTime, endTime) {
    try {
        await router.post('/Config/Update-Shift', {
            name_shift: shiftName,
            start_time: startTime,
            end_time: endTime,
        });
        toast.success(`Horario de ${shiftName} actualizado correctamente.`);
    } catch (err) {
        console.error('Error updating shift', shiftName, err);
        toast.error(`Error al actualizar horario de ${shiftName}.`);
    }
}       

function saveChanges() {
    updateShift('Matutino', morningShiftStart.value, morningShiftEnd.value);
    updateShift('Vespertino', eveningShiftStart.value, eveningShiftEnd.value);
}
</script>

<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="font-semibold text-lg">Horarios de Turno</h3>
        <p class="mb-6 text-sm text-gray-600">Configura los horarios de los turnos para los empleados.</p>

        <section class="flex flex-col items-center">
        <h3 class="font-bold text-xl">Horario Matutino</h3>
        <div class="mt-2 mb-6 flex justify-evenly w-full">
            <div class="flex-col flex">
            <label class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
            <input
                type="time"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model="morningShiftStart"
            />
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 self-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <div class="flex flex-col">
            <label class="block text-sm font-medium text-gray-700 ">Hora de Fin</label>
            <input
                type="time"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model="morningShiftEnd"
            />
        </div>
        </div>

        <h3 class="font-bold text-xl">Horario Vespertino</h3>
        <div class="mt-2 mb-6 flex justify-evenly w-full">
            <div class="flex flex-col">
            <label class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
            <input
                type="time"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model="eveningShiftStart"
            />
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 self-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <div class="flex flex-col">
            <label class="block text-sm font-medium text-gray-700 ">Hora de Fin</label>
            <input
                type="time"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model="eveningShiftEnd"
            />
            </div>
        </div>

        <button v-if="morningShiftStart && morningShiftEnd && eveningShiftStart && eveningShiftEnd"
            class="px-4 py-2 bg-blue-600 text-white rounded-md self-end"
            @click="saveChanges"
        >
            Guardar Cambios
        </button>
         </section>
    </div>
</template>
