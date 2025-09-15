<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { computed } from "vue";
import { useToast } from "vue-toastification";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import ReturnButton from "@/Components/ReturnButton.vue";

const props = defineProps({
    tables: Object,
    orders: Object,
    selectedTable: Object,
    currentOrder: Object,
    isEdit: Boolean,
});

const emit = defineEmits(["closeOptions"]);
function closeOptions() {
    emit("closeOptions");
    resetOrder();
    console.log(
        "Close Options clicked, order reset to normal state:",
        props.currentOrder
    );
}

const toast = useToast();

const saveOrder = () => {
    console.log("Save Order clicked with current order:", props.currentOrder);
    // return toast.info('Guardando orden...');
    if (!props.currentOrder.mesa_id || props.currentOrder.items.length === 0) {
        toast.error("No se puede guardar una orden vacía");
        return;
    } else {
        router.post("/Orders", props.currentOrder, props.currentTable, {
            onSuccess: (response) => {
                toast.success("Orden guardada exitosamente");
                emit("handleOrderSaved", response.props.order);
                console.log("Order saved successfully:", response.props.order);
            },
            onError: (error) => {
                toast.error("Error al guardar la orden");
                console.error("Error saving order:", error);
            },
            finally: () => {
                toast.info("Save order request completed.");
                console.log("Save order request completed.");
            },
        });
    }
    resetOrder();
    console.log("Order saved and reset to empty state:", props.currentOrder);
};

const confirmSaveOrder = () => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Generar orden",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            saveOrder();
            Swal.fire("¡Generada!", "La orden ha sido generada.", "success");
        }
    });
};

const resetOrder = () => {
    props.currentOrder.mesa_id = null;
    props.currentOrder.items = [];
    console.log("Order reset to empty state:", props.currentOrder);
};

const products = ref([]);
const showProducts = ref(false);

const selectedCategory = ref("all");

const filteredProducts = computed(() => {
    if (selectedCategory.value === "all") {
        return products.value.filter((product) => product.is_active);
    }
    return products.value.filter(
        (product) =>
            product.category.name === selectedCategory.value &&
            product.is_active
    );
});

// GetProducts
const GetProducts = async () => {
    try {
        const response = await axios.get(route("products.get"));
        products.value = response.data.data;
        showProducts.value = true;
        console.log("Products data fetched successfully:", products.value);
    } catch (error) {
        console.error("There was an error fetching the products data!", error);
    }
};

const AddToOrder = (product, _selectedTable) => {
    if (!props.currentOrder.mesa_id) {
        props.currentOrder.mesa_id = _selectedTable;
    }
    const existingItem = props.currentOrder.items.find(
        (item) => item.product_id === product.id
    );
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        props.currentOrder.items.push({
            product_id: product.id,
            quantity: 1,
            price: product.price,
            product: product,
        });
    }
    console.log("=========================================");
    console.log("Current Order:", props.currentOrder);
    console.log("Add to Order clicked");
    console.log("Product added to order:", product);
    console.log("Current Order:", props.currentOrder);
};

const RemoveToOrder = (productId) => {
    const existingItemIndex = props.currentOrder.items.findIndex(
        (item) => item.product_id === productId
    );
    if (existingItemIndex !== -1) {
        if (props.currentOrder.items[existingItemIndex].quantity > 1) {
            props.currentOrder.items[existingItemIndex].quantity -= 1;
        } else {
            props.currentOrder.items.splice(existingItemIndex, 1);
        }
    }
    console.log("Remove from Order clicked");
    console.log("Product removed from order:", productId);
    console.log("Current Order:", props.currentOrder);
};

onMounted(() => {
    console.log("Selected Table Prop:", props.selectedTable);
    props.currentOrder.mesa_id = props.selectedTable?.number
        ? props.selectedTable.number
        : null;
    GetProducts();
    console.log("Component mounted.");
});

const subtotal = computed(() => {
    return props.currentOrder.items.reduce(
        (acc, item) => acc + item.price * item.quantity,
        0
    );
});

const updateOrder = () => {
    const payload = {
        table_id: props.selectedTable?.number || props.currentOrder.table_id,
        status: props.currentOrder.status || "En curso",
        items: props.currentOrder.items,
        subtotal: subtotal.value,
    };
    router.put(`/Order/Update/${props.currentOrder.id}`, payload, {
        onSuccess: (response) => {
            console.log("Payload subtotal sent:", payload.subtotal);
            toast.success("Orden actualizada exitosamente");
            emit("handleOrderUpdated", response.props.order);
            console.log("Order updated successfully:", response.props.order);
        },
        onError: (error) => {
            toast.error("Error al actualizar la orden");
            console.error("Error updating order:", error);
        },
        finally: () => {
            toast.info("Update order request completed.");
            console.log("Update order request completed.");
        },
    });
    resetOrder();
    console.log("Order updated and reset to empty state:", props.currentOrder);
};

console.log(props);
</script>

<template>
    <div class="w-full px-8" v-if="props.selectedTable">
        <div
            class="bg-gray-100 p-8 rounded-xl shadow-lg hover:scale-[1.01] transform transition-transform duration-300 ease-in-out"
        >
            <p class="text-center">Selecciona una categoría del Menú</p>

            <div v-if="showProducts" class="mt-4">
                <select
                    v-model="selectedCategory"
                    class="border p-2 rounded-lg w-full mb-4"
                >
                    <option disabled value="">Seleccionar categoría</option>
                    <option value="all">Todas las categorías</option>
                    <option
                        v-for="category in [
                            ...new Set(products.map((p) => p.category.name)),
                        ]"
                        :key="category"
                        :value="category"
                    >
                        {{ category }}
                    </option>
                </select>

                <div
                    v-if="filteredProducts.length > 0"
                    class="text-center text-gray-500"
                >
                    <h4 class="text-xl font-semibold mb-2">
                        Productos Disponibles:
                    </h4>
                    <ul
                        class="list-disc list-inside max-h-48 2xl:max-h-[30rem] overflow-y-auto grid grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4"
                    >
                        <li
                            v-for="product in filteredProducts"
                            :key="product.id"
                            class="mb-1 flex flex-col"
                        >
                            <div
                                class="bg-white rounded shadow flex justify-between items-center flex-col"
                            >
                                <img
                                    :src="product.image"
                                    alt="Product Image"
                                    class="h-40 w-full 2xl:max-w-40 object-cover mb-2"
                                />
                                <span class="font-bold">{{ product.name }}</span
                                ><span
                                    class="font-extrabold text-black text-lg mb-"
                                    >${{ product.price }}</span
                                >
                                <div
                                    class="flex justify-evenly w-full m-4 text-center items-center"
                                >
                                    <button v-if="!isEdit"
                                        class="text-sm rounded-md px-4 py-1 uppercase font-extrabold bg-red-600/80 text-white hover:bg-red-600"
                                        @click="RemoveToOrder(product.id)"
                                    >
                                        -
                                    </button>
                                    <button v-if="isEdit" disabled
                                        class="text-sm rounded-md px-4 py-1 uppercase font-extrabold bg-gray-400/80 text-white cursor-not-allowed"                                        
                                    >
                                        -
                                    </button>
                                    <span
                                        class="text-lg font-bold text-gray-700"
                                        >{{
                                            currentOrder.items.find(
                                                (item) =>
                                                    item.product_id ===
                                                    product.id
                                            )?.quantity || 0
                                        }}</span
                                    >
                                    <button
                                        class="text-sm rounded-md px-4 py-1 uppercase font-extrabold bg-approveGreen/80 text-white hover:bg-approveGreen"
                                        @click="
                                            AddToOrder(
                                                product,
                                                selectedTable.number
                                            )
                                        "
                                    >
                                        +
                                    </button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button
                    v-if="!isEdit"
                    class="mt-4 px-8 py-2 bg-dangerRed hover:bg-red-800 text-white rounded-lg transition-all duration-300 transform ease-in-out"
                    @click="closeOptions"
                >
                    Cerrar
                </button>
                <button
                    v-if="!isEdit"
                    class="mt-4 px-10 py-2 bg-softBlue hover:bg-blue-800 transition-all transform duration-300 ease-in-out text-white rounded-lg"
                    @click="confirmSaveOrder"
                >
                    Guardar orden
                </button>
                <ReturnButton
                    v-if="isEdit"
                    class="mt-4 px-8 py-2 bg-dangerRed hover:bg-red-800 text-white rounded-lg transition-all duration-300 transform ease-in-out"
                ></ReturnButton>
                <button
                    v-if="isEdit"
                    class="mt-4 px-10 py-2 bg-softBlue hover:bg-blue-800 transition-all transform duration-300 ease-in-out text-white rounded-lg"
                    @click="updateOrder"
                >
                    Actualizar orden
                </button>
            </div>
        </div>
    </div>
</template>
