<script setup>
import { ref } from "vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { defineProps } from "vue";
import { useToast } from "vue-toastification";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";


const props = defineProps({ products: Array, categories: Array });

const newProduct = ref({
    category_id: '',
    name: '',
    sku: '',
    description: '',
    price: '',
    cost: '',
    tax: '',
    is_active: false,
    created_at: '',
    updated_at: ''
});

const createProduct = () => {
    router.post('/Menu', newProduct.value,
    {
        onSuccess: () => {
            useToast().success('Producto creado exitosamente');
            newProduct.value = {
                category_id: '',
                name: '',
                sku: '',
                description: '',
                price: '',
                cost: '',
                tax: '',
                is_active: false,
                created_at: '',
                updated_at: ''
            };
            router.visit('/Menu');
        },
        onError: (error) => {
            console.error('Error creating product:', error);
        }
    }
    );
};

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Crear Producto" />
    <div class="py-24 h-screen overflow-y-auto mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6 ">
            <h2 class=" text-white mb-4 font-bold text-2xl">Registrar Nueva Producto</h2>
            <div class="bg-white p-6 rounded-2xl shadow-md flex">
                <form @submit.prevent="createProduct" class="space-y-2 w-full flex gap-4">
                <div class="w-1/2 pr-4 border-r flex flex-col items-center justify-center border-dashed border-2 border-gray-300 h-64 self-center">
                    <span>Arrastra y suelta una imagen aquí</span>
                </div>
                <div class="w-1/2 pr-4 border-r self-center">
                    <InputLabel for="name" value="Nombre del Producto" />
                    <input v-model="newProduct.name" id="name" type="text" class="border p-2 rounded-lg mb-4 w-full" />
                    <InputError class="mt-2" />
                    <InputLabel for="description" value="Descripción del Producto" />
                    <textarea v-model="newProduct.description" id="description" class="border p-2 rounded-lg mb-4 w-full"></textarea>
                    <InputError class="mt-2" />
                    <InputLabel for="category_id" value="Categoría" />
                    <select v-model="newProduct.category_id" id="category_id" class="border p-2 rounded-lg mb-4 w-full">
                        <option value="">Seleccionar categoría</option>
                        <option v-for="category in props.categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                    </select>
                    <InputLabel for="is_active" value="Disponible" />
                    <label class="inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            class="sr-only peer"
                            v-model="newProduct.is_active"
                            id="is_active"
                        />
                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                    </label>
                    <InputError class="mt-2" />
                </div>
                <div class="w-1/2 pl-4">
                    <InputLabel for="price" value="Precio" />
                    <input v-model="newProduct.price" id="price" type="number" class="border p-2 rounded-lg mb-4 w-full" />
                    <InputError class="mt-2" />
                    <InputLabel for="cost" value="Costo" />
                    <input v-model="newProduct.cost" id="cost" type="number" class="border p-2 rounded-lg mb-4 w-full" />
                    <InputError class="mt-2" />     
                    <InputLabel for="tax" value="Impuesto" />
                    <input v-model="newProduct.tax" id="tax" type="number" class="border p-2 rounded-lg mb-4 w-full" />
                    <InputError class="mt-2" />
                    <InputError class="mt-2" />
                    <InputLabel for="sku" value="SKU" />
                    <input v-model="newProduct.sku" id="sku" type="text" class="border p-2 rounded-lg mb-4 w-full" />
                    <InputError class="mt-2" />
                </div>
                <button type="submit" class="bg-approveGreen hover:bg-green-700 text-white px-4 py-2 rounded-lg self-end">Guardar Producto</button>
            </form>
        </div>
            <div>
            <Link href="/Menu" class="bg-red-500 hover:bg-red-700 text-white p-2 rounded-lg px-8"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 0l3-3m-3 3l3 3" /></svg>Volver Atrás</Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>