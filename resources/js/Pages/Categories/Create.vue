<script setup>
import { ref } from 'vue';
import { useForm, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useToast } from 'vue-toastification';

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
  <div class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6 ">
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
    <div>
      <Link href="/Categories" class="px-8 p-2 bg-dangerRed hover:bg-red-700 rounded-lg text-white"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 0l3-3m-3 3l3 3" /></svg>Volver Atrás</Link>
    </div>
  </div>
  </AuthenticatedLayout>
</template>
