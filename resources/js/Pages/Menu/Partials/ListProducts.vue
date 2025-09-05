<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import Modal from "@/Components/Modal.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import CancelButton from "@/Components/CancelButton.vue";

const props = defineProps({ products: Object, categories: Object });
const openModal = ref(false);
const currentProduct = ref({});

function showModal(id) {
    openModal.value = true;
    const found = props.products.data.find((prod) => prod.id === id)
    currentProduct.value = {...found};
    console.log(found.name)   
}

function closeModal() {
    openModal.value = false;
}

const editProduct = (id) => {

}

const deleteProduct = (id) => { 
    if(confirm('Estás seguro que quieres Elimar este Producto?')){
        router.delete(`/Menu/${id}`,{
            onSuccess: () =>{
                useToast().success('Producto Eliminado Correctamente.')
            },
            onError: (error) => {
                useToast().error('El producto no se pudo eliminar')
                console.error('El producto no se Eliminó. ', error)
            }
            
        });
    }
}

</script>


<template>
    <div class="bg-white p-4 rounded-2xl">
        <form @submit.prevent="searchTable">
            <input type="text" placeholder="Buscar Producto..." class="border p-2 rounded-lg w-full mb-4" />
        </form>
        <table class="w-full mt-2 border">
            <thead>
            <tr class="bg-gray-100">
                <th class="p-4">Categoría</th>
                <th class="p-4">Nombre</th>
                <th class="p-4">SKU</th>
                <th class="p-4">Precio</th>
                <!-- <th class="p-4">Impuesto</th> -->
                <th class="p-4">Disponible</th>
                <th class="p-4">Fecha de Creación</th>
                <th class="p-4">Fecha de Actualización</th>
                <th class="p-4">Acciones</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="value in props.products" :key="value.id">
                    <td class="border p-2 text-center">{{ value.category_id }}</td>
                    <td class="border p-2 text-center">{{ value.name }}</td>
                    <td class="border p-2 text-center">{{ value.sku }}</td>
                    <td class="border p-2 text-center">{{ value.price }}</td>
                    <!-- <td class="border p-2 text-center">{{ value.tax }}</td> -->
                    <td class="border p-2 text-center"><StatusBadge :status="value.is_active" /></td>
                    <td class="border p-2 text-center">{{ value.created_at.slice(0, 10) }}</td>
                    <td class="border p-2 text-center">{{ value.updated_at.slice(0, 10) }}</td>
                    <td class="border p-2 flex gap-2 justify-center">
                        <button @click="showModal(value.id)" class="bg-midBlue hover:bg-strongBlue text-white p-1 rounded"><img src="/assets/icons/svg/actions/edit.svg" alt="Editar" class="w-6"></button>
                        <button @click="deleteProduct(value.id)" class="bg-dangerRed hover:bg-red-700 text-white p-1 rounded"><img src="/assets/icons/svg/actions/delete.svg" alt="Eliminar" class="w-6"></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

      <Modal v-model:show="openModal">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Editar Producto</h3>
                <form class="flex flex-col">
                    
                    <div>
                        <InputLabel class="block text-sm font-medium text-black mb-2">Nombre</InputLabel>
                        <input v-model="currentProduct.name" type="text"></input>
                        <div>
                            <InputLabel for="description" value="Descripción"></InputLabel>
                            <textarea v-model="currentProduct.description" placeholder="Descripción" class="border p-2 rounded-lg mb-4"></textarea>
                        </div>
                        <div>
                            <InputLabel for="categories" value="Categoría" class="mb-2"></InputLabel>
                            <select v-model="currentProduct.category_id" type="text" placeholder="" class="border p-2 rounded-lg mb-4 w-full">
                                <option value="">Selecciona un Categoría</option>
                                <option v-for="category in props.categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                            
                        </div>
                    </div>

                    <div>
                        <InputLabel for="sku" value="SKU"></InputLabel>
                        <input v-model="currentProduct.sku" type="text" placeholder="SKU" class="border p-2 rounded-lg mb-4" />
                    </div>


                    <div>
                        <InputLabel for="price" value="Precio"></InputLabel>
                        <input v-model="currentProduct.price" type="number" placeholder="Precio" class="border p-2 rounded-lg mb-4" />
                    </div>

                    <div>
                        <InputLabel for="cost" value="Costo"></InputLabel>
                        <input v-model="currentProduct.cost" type="number" placeholder="Costo" class="border p-2 rounded-lg mb-4" />
                    </div>

                    <div>
                        <InputLabel for="tax" value="Impuesto"></InputLabel>
                        <input v-model="currentProduct.tax" type="number" placeholder="Impuesto" class="border p-2 rounded-lg mb-4" />
                    </div>
                    <div class="flex justify-between">
                        <CancelButton @click="closeModal">Cancelar</CancelButton>
                        <button @click="editTable(currentProduct.id)" type="button" class="bg-approveGreen hover:bg-green-600 text-white px-4 py-2 rounded-lg self-end">Guardar Cambios</button>
                    </div>
                    
                </form>
            </div>
        </Modal>  
</template>