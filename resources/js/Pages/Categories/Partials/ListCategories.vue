<script setup>
import { ref, computed, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import CancelButton from "@/Components/CancelButton.vue";

const form = ref({ errors: {} });
const currentCategory = ref({});
const openModal = ref(false);
const props = defineProps({ categories: Object });

const showModal = (id) => {
    openModal.value = true;
    currentCategory.value = props.categories.data.find(
        (category) => category.id === id
    );
};
const closeModal = () => {
    openModal.value = false;
};

const editCategory = (id) => {
    if (
        currentCategory.value.name ===
        props.categories.data.find(
            (category) =>
                category.name === currentCategory.value.name &&
                category.id !== id
        )?.name
    ) {
        document.querySelector(".text-red-500").classList.remove("hidden");
        return;
    }
    router.put(`/Categories/edit/${id}`, currentCategory.value, {
        preserveState: false,
        replace: true,
        onSuccess: () => {
            useToast().success("Categoría actualizada exitosamente");
            openModal.value = false;
        },
        onError: (error) => {
            useToast().error("Error al actualizar la categoría");
            console.error("Error al actualizar categoría:", error);
        },
    });
};

const deleteCategory = (id) => {
    if (confirm("¿Estás seguro de que deseas eliminar esta categoría?")) {
        router.delete(`/Categories/${id}`, {
            onSuccess: () => {
                useToast().success("Categoría eliminada exitosamente");
            },
            onError: (error) => {
                useToast().error("Error al eliminar la categoría");
                console.error("Error al eliminar categoría:", error);
            },
        });
    }
};

const searchQuery = ref("");
const filteredCategories = computed(() => {
    if (!searchQuery.value) return props.categories.data;
    return props.categories.data.filter((category) =>
        category.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <div class="bg-white p-4 rounded-2xl">
        <form>
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Buscar Categoría..."
                class="border p-2 rounded-lg w-full mb-4"
            />
        </form>
        <table class="w-full mt-2 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">#</th>
                    <th class="p-4">Nombre</th>
                    <th class="p-4">Descripción</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in filteredCategories" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">{{ value.name }}</td>
                    <td class="border p-2 text-center">
                        {{ value.description }}
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
                            @click="deleteCategory(value.id)"
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
            <h3 class="text-lg font-bold mb-4">Editar Categoría</h3>
            <form class="flex flex-col">
                <div>
                    <InputLabel for="name" value="Nombre"></InputLabel>
                    <input
                        v-model="currentCategory.name"
                        type="text"
                        placeholder="Nombre de Categoría"
                        class="border p-2 rounded-lg mb-4"
                    />
                    <InputError :message="form.errors.name"></InputError>
                    <span class="text-red-500 hidden"
                        >Este nombre ya está en uso</span
                    >
                </div>

                <div>
                    <InputLabel
                        for="description"
                        value="Descripción"
                    ></InputLabel>
                    <input
                        v-model="currentCategory.description"
                        type="text"
                        placeholder="Descripción"
                        class="border p-2 rounded-lg mb-4"
                    />
                    <InputError :message="form.errors.description"></InputError>
                </div>
                <div class="flex justify-between">
                    <button
                        @click="closeModal"
                        class="bg-dangerRed hover:bg-red-700 text-white px-4 py-2 rounded-lg"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="editCategory(currentCategory.id)"
                        type="button"
                        class="bg-approveGreen hover:bg-green-600 text-white px-4 py-2 rounded-lg self-end"
                    >
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>
