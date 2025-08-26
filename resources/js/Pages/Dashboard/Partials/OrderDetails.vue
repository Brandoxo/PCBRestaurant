<script setup>
import { ref } from "vue";

const source = ref([]);
</script>

<template>
    <h2 class="text-lg font-semibold mt-2 mb- text-center text-white">Detalles de la orden</h2>
    <div class="bg-white p-6 rounded-lg shadow-md flex">
        <div  class="h-screen overflow-auto w-1/2 flex flex-col gap-4 mt-12">
            <span><img src="assets/icons/svg/details/utensils.svg" alt="Food Icon" class="inline w-6 ml-2 cursor-pointer" /> 1 x Pollito con Papas</span>
            <span ><img src="assets/icons/svg/details/drink.svg" alt="Drink Icon" class="inline w-6 ml-2 cursor-pointer" /> 1 x Coca-Cola</span>
        </div>
        <div>
            <h2>Detalles de la cuenta</h2>
            <div v-for="value in source" class="h-screen overflow-auto w-1/2">
                <span v-if="value.drink"><img src="assets/icons/svg/details/drink.svg" alt="Copy Icon" class="inline w-6 ml-2 cursor-pointer" /></span>
                <span v-if="value.food"><img src="assets/icons/svg/details/utensils.svg" alt="Copy Icon" class="inline w-6 ml-2 cursor-pointer" /></span>
            </div> 
        </div>
        
        
    </div>
</template>