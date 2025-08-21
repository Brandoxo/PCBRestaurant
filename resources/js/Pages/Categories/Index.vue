<script setup>
import { Link } from '@inertiajs/vue3';
const props = defineProps({ categories: Object });
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Categorías</h1>
    <Link href="/categories/create" class="bg-blue-600 text-white px-3 py-1 rounded">Nueva</Link>
    <table class="w-full mt-4 border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Nombre</th>
          <th class="p-2 border">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="c in categories.data" :key="c.id">
          <td class="border p-2">{{ c.id }}</td>
          <td class="border p-2">{{ c.name }}</td>
          <td class="border p-2">
            <Link :href="`/categories/${c.id}/edit`" class="text-blue-600">Editar</Link>
            <Link :href="`/categories/${c.id}`" method="delete" as="button" class="text-red-600 ml-2">Eliminar</Link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
