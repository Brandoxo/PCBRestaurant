<script setup>
import { useForm, Link } from '@inertiajs/vue3';
const props = defineProps({ categories: Array });
const form = useForm({ name:'', sku:'', price:'', category_id:'' });
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl mb-4">Nuevo Producto</h1>
    <form @submit.prevent="form.post('/products')">
      <input v-model="form.name" placeholder="Nombre" class="border p-2 block mb-2"/>
      <input v-model="form.sku" placeholder="SKU" class="border p-2 block mb-2"/>
      <input v-model="form.price" placeholder="Precio" type="number" step="0.01" class="border p-2 block mb-2"/>
      <select v-model="form.category_id" class="border p-2 block mb-2">
        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
      </select>
      <button type="submit" class="bg-green-600 text-white px-4 py-2">Guardar</button>
    </form>
  </div>
</template>
