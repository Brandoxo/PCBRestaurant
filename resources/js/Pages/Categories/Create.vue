<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from 'vue-toastification';
import ReturnButton from '@/Components/ReturnButton.vue';

const props = defineProps({categories: Object})
console.log(props)
const form = useForm({ name:'' });

const newCategory = ref({
  name: '',
  description: '',
  is_active: true,
  create_at: '',
  update_at: ''
})


const createCategory = () =>{
  router.post('/Categories', newCategory.value, {
    onSuccess: () =>{
      useToast().success('Categoria creada Exitosamente');
      newCategory.value = {
        name: '',
        description: '',
      }
    },
    onError: () =>{
      useToast().error('Error al crear la Categoría');
      console.error('error: ', error);
    }
  })
}

</script>

<template>
  <AuthenticatedLayout>
  <section class="py-60 h-screen overflow-y-auto mx-auto p-6 space-y-6 pt-6">
    <h1 class="text-2xl text-white font-bold mb-4">Registrar Nueva Categoría</h1>
    <div class="bg-white p-6 rounded-2xl">
    <form @submit.prevent="createCategory" class="space-y-2 flex flex-col">
      <label class="block font-medium text-black mb-2 text-sm">Nombre</label>
      <input v-model="newCategory.name" class="border p-2 block mb-2 rounded-lg w-full"/>
      <label class="block font-medium text-black mb-2 text-sm ">Descripción breve</label>
      <input v-model="newCategory.description" class="border p-2 block mb-2 rounded-lg w-full"></input>
      <button type="submit" class="bg-green-600 text-white px-4 py-2 self-end rounded-lg">Guardar</button>
    </form>
    </div>
<ReturnButton />
  </section>
  </AuthenticatedLayout>
</template>
