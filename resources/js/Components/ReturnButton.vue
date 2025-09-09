   <script setup>
function goBack() {
  window.history.back();
}
    </script>

   <template>

<button @click="goBack" class="px-8 p-2 bg-dangerRed hover:bg-red-700 rounded-lg text-white"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 0l3-3m-3 3l3 3" /></svg>Volver Atrás</button>
    </template>