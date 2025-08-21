<script setup>
import { useForm } from '@inertiajs/vue3';
const form = useForm({ name:'' });
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl mb-4">Nueva Categoría</h1>
    <form @submit.prevent="form.post('/categories')">
      <input v-model="form.name" placeholder="Nombre" class="border p-2 block mb-2"/>
      <button type="submit" class="bg-green-600 text-white px-4 py-2">Guardar</button>
    </form>
  </div>
</template>
