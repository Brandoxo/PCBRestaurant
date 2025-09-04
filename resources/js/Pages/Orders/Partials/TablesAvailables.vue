<script setup>
import { ref } from 'vue';
import GenerateOrderButton from '@/Components/GenerateOrderButton.vue';
import StatusBadge from '@/Components/StatusBadge.vue';
import { useToast } from 'vue-toastification';

const emit = defineEmits(['tableSelected']);
const props = defineProps({
  tables: Object,
});

// Define la función para emitir el evento
function openOptions(table) {
  emit('tableSelected', table);
  window.scrollTo({ top: '0%', behavior: 'smooth' });
  useToast().info(`Seleccionando orden para la Mesa #${table.number}`);
}
</script>

<template>
  <div class="grid gap-6 overflow-y-auto max-h-full scrollbar-hide scroll-smooth w-1/3">
    <div
      v-for="value in tables.filter(t => t.status === 'Libre' || t.status === 'Reservada')"
      :key="value.id"
      class="bg-white rounded-xl items-center p-4 space-y-2 w-full"
    >
      <div>
        <StatusBadge :status="value.status" />
      </div>
      <div class="flex justify-evenly gap-8 items-center">
          <h2 class="text-xl uppercase font-black">Mesa</h2>
          <img src="/assets/icons/svg/menu/table.svg" alt="Mesa" class="w-16" />
        <span class="font-extrabold text-4xl text-red-900">#{{ value.number }}</span>
      </div>
      <div class="flex justify-center items-center gap-2">
      <img src="/assets/icons/svg/visual/people.svg" alt="Capacidad" class="inline-block w-4 h-4" />
      <p class="text-center text-gray-500">Capacidad para <span class="font-bold text-lg text-gray-600">{{ value.capacity }}</span> personas</p>
      </div>
      <GenerateOrderButton @click="openOptions(value)">Generar Orden</GenerateOrderButton>
    </div>
  </div>
</template>