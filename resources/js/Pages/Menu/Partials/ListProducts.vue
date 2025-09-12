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
const imageFile = ref(null);
const handleImageUpdate = (event) => {
    imageFile.value = event.target.files[0];
    console.log("Selected image file:", imageFile.value);
};

function showModal(id) {
    openModal.value = true;
    const found = props.products.find((prod) => prod.id === id);
    currentProduct.value = { ...found };
    console.log(found.name);
}

function closeModal() {
    openModal.value = false;
}

const editProduct = (id) => {
    const formData = new FormData();
    formData.append("category_id", currentProduct.value.category_id ?? "");
    formData.append("name", currentProduct.value.name ?? "");
    formData.append("sku", currentProduct.value.sku ?? "");
    formData.append("description", currentProduct.value.description ?? "");
    formData.append("price", currentProduct.value.price ?? "");
    formData.append("cost", currentProduct.value.cost ?? "");
    formData.append("tax", currentProduct.value.tax ?? "");
    formData.append("is_active", currentProduct.value.is_active);
    if (imageFile.value) {
        formData.append("image", imageFile.value);
    }
    router.post(`/Menu/edit/${id}`, formData, {
        onSuccess: () => {
            useToast().success("Producto Actualizado Correctamente.");
            closeModal();
            currentProduct.value = {};
        },
        onError: (error) => {
            useToast().error("El producto no se pudo actualizar");
            console.error("El producto no se Actualizó. ", error);
        },
    });
};

const deleteProduct = (id) => {
    if (confirm("Estás seguro que quieres Elimar este Producto?")) {
        router.delete(`/Menu/${id}`, {
            onSuccess: () => {
                useToast().success("Producto Eliminado Correctamente.");
            },
            onError: (error) => {
                useToast().error("El producto no se pudo eliminar");
                console.error("El producto no se Eliminó. ", error);
            },
        });
    }
};
</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <form @submit.prevent="searchTable">
            <input
                type="text"
                placeholder="Buscar Producto..."
                class="border p-2 rounded-lg w-full mb-4"
            />
        </form>
        <table class="w-full mt-2 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">Categoría</th>
                    <th class="p-4">Imagen</th>
                    <th class="p-4">Nombre</th>
                    <th class="p-4">SKU</th>
                    <th class="p-4">Precio</th>
                    <!-- <th class="p-4">Impuesto</th> -->
                    <th class="p-4">Disponible</th>
                    <th class="p-4 hidden lg:block">Fecha de Creación</th>
                    <th class="p-4">Fecha de Actualización</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in props.products" :key="value.id">
                    <td class="border p-2 text-center">
                        {{ value.category_id }}
                    </td>
                    <td class="border p-2 text-center">
                        <img
                            :src="value.image"
                            alt="Product Image"
                            class="w-16 h-16 object-cover mx-auto"
                        />
                    </td>
                    <td class="border p-2 text-center">{{ value.name }}</td>
                    <td class="border p-2 text-center">
                        {{ value.sku.slice(4, 10) }}
                    </td>
                    <td class="border p-2 text-center">$ {{ value.price }}</td>
                    <!-- <td class="border p-2 text-center">{{ value.tax }}</td> -->
                    <td class="border p-2 text-center">
                        <StatusBadge
                            :status="value.is_active ? 'Activo' : 'Inactivo'"
                        />
                    </td>
                    <td class="border p-2 text-center hidden lg:block">
                        {{ value.created_at.slice(0, 10) }}
                    </td>
                    <td class="border p-2 text-center">
                        {{ value.updated_at.slice(0, 10) }}
                    </td>
                    <td class="border p-2 flex gap-2 justify-center">
                        <button
                            @click="showModal(value.id)"
                            class="bg-midBlue hover:bg-strongBlue text-white p-1 rounded"
                        >
                            <img
                                src="/assets/icons/svg/actions/edit.svg"
                                alt="Editar"
                                class="w-6"
                            />
                        </button>
                        <button
                            @click="deleteProduct(value.id)"
                            class="bg-dangerRed hover:bg-red-700 text-white p-1 rounded"
                        >
                            <img
                                src="/assets/icons/svg/actions/delete.svg"
                                alt="Eliminar"
                                class="w-6"
                            />
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Modal v-model:show="openModal">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Editar Producto</h3>
            <form class="flex-col">
                <div class="text-center">
                    <InputLabel for="image" value="Imagen del Producto" />
                    <div
                        class="pr-4 border-r flex flex-col items-center justify-evenly border-dashed border-2 border-gray-300 self-center p-6"
                    >
                        <input
                            @change="handleImageUpdate"
                            id="image"
                            type="file"
                            accept="image/png, image/jpeg"
                            class="mt-2"
                        />
                    </div>
                    <span class="text-sm text-gray-500 mt-2"
                        >(Solo archivos PNG o JPG)</span
                    >
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <InputLabel
                            for="name"
                            class="block text-sm font-medium text-black mb-2"
                            >Nombre</InputLabel
                        >
                        <input
                            v-model="currentProduct.name"
                            type="text"
                            placeholder="Nombre"
                            class="border p-2 rounded-lg w-full mb-2"
                        />
                    </div>
                    <div>
                        <InputLabel
                            for="categories"
                            value="Categoría"
                            class="mb-2"
                        ></InputLabel>
                        <select
                            v-model="currentProduct.category_id"
                            type="text"
                            placeholder=""
                            class="border rounded-lg mb-2 w-full"
                        >
                            <option value="">Selecciona un Categoría</option>
                            <option
                                v-for="category in props.categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <InputLabel
                            for="description"
                            value="Descripción"
                        ></InputLabel>
                        <textarea
                            v-model="currentProduct.description"
                            placeholder="Descripción"
                            class="border p-2 rounded-lg w-full mb-2"
                        ></textarea>
                    </div>

                    <div>
                        <InputLabel for="sku" value="SKU"></InputLabel>
                        <input
                            v-model="currentProduct.sku"
                            type="text"
                            placeholder="SKU"
                            class="border p-2 rounded-lg w-full mb-2"
                        />
                    </div>

                    <div>
                        <InputLabel for="price" value="Precio"></InputLabel>
                        <input
                            v-model="currentProduct.price"
                            type="number"
                            placeholder="Precio"
                            class="border p-2 rounded-lg w-full mb-2"
                        />
                    </div>

                    <div>
                        <InputLabel for="cost" value="Costo"></InputLabel>
                        <input
                            v-model="currentProduct.cost"
                            type="number"
                            placeholder="Costo"
                            class="border p-2 rounded-lg w-full mb-2"
                        />
                    </div>

                    <div>
                        <InputLabel class="block text-sm font-medium text-black"
                            >Disponible</InputLabel
                        >
                        <select
                            v-model="currentProduct.is_active"
                            class="border p-2 rounded-lg w-full mb-2"
                        >
                            <option value="" disabled selected>
                                Selecciona una opción
                            </option>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <div>
                        <InputLabel for="tax" value="Impuesto"></InputLabel>
                        <input
                            v-model="currentProduct.tax"
                            type="number"
                            placeholder="Impuesto"
                            class="border p-2 rounded-lg w-full mb-2"
                        />
                    </div>
                </div>
                <div class="flex justify-between mt-2">
                    <CancelButton @click="closeModal">Cancelar</CancelButton>
                    <button
                        @click="editProduct(currentProduct.id)"
                        type="button"
                        class="bg-approveGreen hover:bg-green-600 text-white px-4 py-2 rounded-lg self-end uppercase flex items-center text-sm font-bold"
                    >
                        <img
                            src="/assets/icons/svg/actions/save.svg"
                            class="w-8 inline-flex mr-2"
                            alt="Guardar Cambios"
                        />Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>
