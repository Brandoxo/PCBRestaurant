<script setup>
import { ref } from "vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { defineProps } from "vue";
import { useToast } from "vue-toastification";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ReturnButton from "@/Components/ReturnButton.vue";
import Switch from "@/Components/Switch.vue";

const props = defineProps({ products: Array, categories: Array });
const imageFile = ref(null);
const handleImageChange = (event) => {
    imageFile.value = event.target.files[0];
    console.log("Selected image file:", imageFile.value);
};

const newProduct = ref({
    category_id: "",
    name: "",
    sku: "PRDCT00" + "-" + (props.products.length + 1),
    image: getDefaultImageByCategory(""),
    description: "",
    price: "",
    cost: "",
    tax: "",
    is_active: true,
    created_at: "",
    updated_at: "",
});

function getDefaultImageByCategory(categoryId) {
    // Busca la categoría por ID
    const category = props.categories.find((cat) => cat.id == categoryId);
    if (!category) return "/assets/uploads/default-food.png";
    const drinkCategories = [
        "Bebidas Calientes y Frías",
        "Bebidas sin Alcohol",
        "Cervezas",
        "Tequila",
        "Whisky",
        "Vinos",
        "Ron",
        "Brandy & Coñac",
        "Mezcal",
        "Ginebra",
        "Vodka",
        "Copas",
    ];
    if (drinkCategories.includes(category.name)) {
        return "/assets/uploads/default-drink.png";
    }
    return "/assets/uploads/default-food.png";
}

import { watch } from "vue";
// Observa cambios en la categoría seleccionada y actualiza la imagen por defecto si no hay imagen personalizada
watch(
    () => newProduct.value.category_id,
    (newVal) => {
        if (!imageFile.value) {
            newProduct.value.image = getDefaultImageByCategory(newVal);
        }
    }
);

const createProduct = () => {
    const formData = new FormData();
    formData.append("category_id", newProduct.value.category_id);
    formData.append("name", newProduct.value.name);
    formData.append("sku", newProduct.value.sku);
    formData.append("image", newProduct.value.image);
    formData.append("description", newProduct.value.description);
    formData.append("price", newProduct.value.price);
    formData.append("cost", 0);
    formData.append("tax", 0);
    formData.append("is_active", newProduct.value.is_active ? 1 : 0);
    if (imageFile.value) {
        formData.append("image", imageFile.value);
    }

    router.post("/Menu", formData, {
        onSuccess: () => {
            useToast().success("Producto creado exitosamente");
            newProduct.value = {
                category_id: "",
                name: "",
                sku: "PRDCT00" + "-" + (props.products.length + 1),
                image: "",
                description: "",
                price: "",
                cost: "",
                tax: "",
                is_active: true,
                created_at: "",
                updated_at: "",
            };
            router.visit("/Menu");
        },
        onError: (error) => {
            console.error("Error creating product:", error);
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Crear Producto" />
        <div class="py-24 h-screen mx-auto sm:px-6 lg:px-8 lg:ml-64 space-y-6">
            <h2 class="text-white mb-4 font-bold text-2xl">
                Registrar Nueva Producto
            </h2>
            <div
                class="bg-white p-6 rounded-2xl shadow-md flex max-w-6xl mx-auto lg:flex-row flex-col"
            >
                <form
                    @submit.prevent="createProduct"
                    class="space-y-2 flex-1 lg:flex gap-4 self-center"
                >
                    <div class="text-center">
                        <InputLabel for="image" value="Imagen del Producto" />
                        <div
                            class="w-full pr-4 border-r flex flex-col items-center justify-center border-dashed border-2 border-gray-300 h-64 self-center p-6"
                        >
                            <!-- only image file function -->
                            <input
                                @change="handleImageChange"
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
                    <div class="flex-1 lg:fle mx-auto">
                        <div class="pr-4 border-r">
                            <InputLabel
                                for="name"
                                value="Nombre del Producto"
                            />
                            <input
                                v-model="newProduct.name"
                                id="name"
                                type="text"
                                class="border p-2 rounded-lg mb-4 w-full"
                            />
                            <InputError class="mt-2" />
                            <InputLabel
                                for="description"
                                value="Descripción del Producto"
                            />
                            <textarea
                                v-model="newProduct.description"
                                id="description"
                                class="border p-2 rounded-lg mb-4 w-full"
                            ></textarea>
                            <InputError class="mt-2" />
                        </div>
                        <div class="pr-4">
                            <InputLabel for="category_id" value="Categoría" />
                            <select
                                v-model="newProduct.category_id"
                                id="category_id"
                                class="border p-2 rounded-lg mb-4 w-full"
                            >
                                <option value="">Seleccionar categoría</option>
                                <option
                                    v-for="category in props.categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.name }}
                                </option>
                            </select>
                            <InputLabel for="price" value="Precio" />
                            <input
                                v-model="newProduct.price"
                                id="price"
                                type="number"
                                class="border p-2 rounded-lg mb-4 w-full"
                            />
                            <!-- <InputError class="mt-2" />
                            <InputLabel for="cost" value="Costo" />
                            <input
                                v-model="newProduct.cost"
                                id="cost"
                                type="number"
                                class="border p-2 rounded-lg mb-4 w-full"
                            />
                            <InputError class="mt-2" /> -->

                            <!-- <InputLabel for="tax" value="Impuesto" />
                            <input
                                v-model="newProduct.tax"
                                id="tax"
                                type="number"
                                class="border p-2 rounded-lg mb-4 w-full"
                            />
                            <InputError class="mt-2" /> -->

                            <InputLabel for="is_active" value="Disponible" />
                            <label
                                class="inline-flex items-center cursor-pointer"
                            >
                                <input
                                    type="checkbox"
                                    class="sr-only peer"
                                    v-model="newProduct.is_active"
                                    id="is_active"
                                />
                                <Switch />
                            </label>
                            <InputError class="mt-2" />
                        </div>
                    </div>
                </form>
                <button
                    @click="createProduct"
                    class="bg-approveGreen hover:bg-green-700 text-white px-4 py-2 rounded-lg self-end"
                >
                    Guardar Producto
                </button>
            </div>
            <ReturnButton />
        </div>
    </AuthenticatedLayout>
</template>
