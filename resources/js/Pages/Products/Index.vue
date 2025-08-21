<script setup>
import { Link, usePage } from '@inertiajs/vue3';
const props = defineProps({ products: Object });
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Productos</h1>
    <Link href="/products/create" class="bg-blue-600 text-white px-3 py-1 rounded">Nuevo</Link>
    <table class="w-full mt-4 border">
      <thead>
        <tr class="bg-gray-100">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">Nombre</th>
          <th class="p-2 border">SKU</th>
          <th class="p-2 border">Precio</th>
          <th class="p-2 border">Categoría</th>
          <th class="p-2 border">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in products.data" :key="p.id">
          <td class="border p-2">{{ p.id }}</td>
          <td class="border p-2">{{ p.name }}</td>
          <td class="border p-2">{{ p.sku }}</td>
          <td class="border p-2">{{ p.price }}</td>
          <td class="border p-2">{{ p.category?.name }}</td>
          <td class="border p-2">
            <Link :href="`/products/${p.id}/edit`" class="text-blue-600">Editar</Link>
            <Link :href="`/products/${p.id}`" method="delete" as="button" class="text-red-600 ml-2">Eliminar</Link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
