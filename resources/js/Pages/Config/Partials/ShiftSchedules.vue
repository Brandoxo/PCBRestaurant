<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="font-semibold text-lg">Horarios de Turno</h3>
        <p class="mb-6 text-sm text-gray-600">Configura los horarios de los turnos para los empleados.</p>

        <h3>Horario Matutino</h3>
        <div class="mt-2 mb-6">
            <label class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
            <input
                type="time"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model="morningShiftStart"
            />
        </div>

        <h3>Horario Vespertino</h3>
        <div class="mt-2 mb-6">
            <label class="block text-sm font-medium text-gray-700">Hora de Inicio</label>
            <input
                type="time"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model="eveningShiftStart"
            />
        </div>
    </div>
</template>
