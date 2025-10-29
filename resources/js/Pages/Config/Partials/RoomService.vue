<script setup>
import { ref } from 'vue';
import Switch from '@/Components/Switch.vue';

const isRoomServiceEnabled = ref(false);
const roomServiceCharge = ref(0);
</script>
<template>
    <div class="bg-white shadow-md rounded-b p-6">
        <h3 class="text-lg font-semibold">Room Service</h3>
        <p class="mb-6 text-sm text-gray-600">Configura las opciones de Room Service en el sistema.</p>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Habilitar Room Service

            <input
                type="checkbox"
                class="sr-only peer relative"
                v-model="isRoomServiceEnabled"
                id="is_active"
            />

            <Switch v-model="isRoomServiceEnabled" />
            </label>

        </div>

        <div v-if="isRoomServiceEnabled">
            <label class="block text-sm font-medium text-gray-700">Cargo Adicional por Room Service (%)</label>
            <input
                type="number"
                min="0"
                max="100"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model.number="roomServiceCharge"
            />
        </div>
    </div>
</template>