<script setup>
import { ref } from 'vue';

const props = defineProps({
  tables: Object,
  orders: Object,
  selectedTable: Object
});
const emit = defineEmits(['closeOptions']);
function closeOptions() {
  emit('closeOptions');
}

console.log(props)
</script>


<template>
    <div class="w-2/3 p-8" v-if="selectedTable">
      <div class="bg-gray-100 p-8 rounded-xl shadow-lg ">
        <h3 class="text-2xl font-bold mb-4">Orden para la Mesa #{{ selectedTable.number }}</h3>
        <p>Selecciona una opción...</p>
        <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded" @click="closeOptions">Cerrar</button>
      </div>
    </div>
</template>