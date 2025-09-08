<template>

    <div :class="`px-2 py-1 text-xs font-semibold rounded-full w-28 text-center mx-auto ${statusClasses[status]}`">
        <span>{{ status }}</span>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

 const props = defineProps({
     status: {
         type: String,
         required: true
     }
 });

 const statusClasses = {
     'Libre': 'bg-approveGreen/70 text-white',
     'Reservada': 'bg-goodYellow text-white',
     'Ocupada': 'bg-dangerRed text-white',
     'Completada': 'bg-approveGreen/70 text-white',
     'En curso': 'bg-yellow-500 text-white',
     'Cancelada': 'bg-red-500 text-white',
     'Activo': 'bg-approveGreen text-white' ,
     'Inactivo': 'bg-dangerRed text-white'
 };


</script>