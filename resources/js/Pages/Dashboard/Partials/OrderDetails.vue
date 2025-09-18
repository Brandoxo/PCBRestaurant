<script setup>
import { router, usePage } from "@inertiajs/vue3";
import axios from "axios";
import Swal from "sweetalert2";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    order: Object,
});

const openModal = ref(false);

const showModal = () => {
    openModal.value = true;
};

const closeModal = () => {
    openModal.value = false;
};

const paymentMethod = ref("Efectivo");
const tip_percent = ref("0");
const otherTip = ref("");
const user = usePage().props.auth.user;

console.log("order prop in <OrderDetails>: ", props.order);

const foodCategories = [1, 2, 3, 4, 6, 7, 8, 20];
const drinkCategories = [5, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];

const createSale = () => {
    closeModal();
    Swal.fire({
        icon: "info",
        title: "Estás segur@ de cobrar la orden?",
        text: "Asegurate primero de haber cobrado el monto correcto.",
        showCancelButton: true,
        confirmButtonText: "Sí, cobrar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (
            result.isConfirmed &&
            props.order &&
            props.order.id &&
            props.order.order_details
        ) {
            router.post(`/Order/AddTip/${props.order.id}`, {
                tip_percent: tip_percent.value ? Number(tip_percent.value) : null,
                tip: otherTip.value ? Number(otherTip.value) : null,
            }, {
                onSuccess: () => {
                    console.log("Tip added successfully");
                },
                onError: (errors) => {
                    console.error("Error adding tip", errors);
                },
            });

            props.order.order_details.forEach((item) => {
                router.post(
                    `/Sales/Create`,
                    {
                        order_id: props.order.id,
                        product_id: item.product_id,
                        user_id: user.id,
                        cash_audit_id: null,
                        quantity: item.quantity,
                        unit_price:
                            item.unit_price ??
                            (item.product ? item.product.price : 0),
                        subtotal:
                            item.total ??
                            item.quantity *
                                (item.unit_price ??
                                    (item.product ? item.product.price : 0)),
                        payment_method: paymentMethod.value,
                        date_time: new Date()
                            .toISOString()
                            .slice(0, 19)
                            .replace("T", " "),
                    },

                    {
                        onSuccess: () => {
                            console.log(
                                "Sale created successfully for product",
                                item.product_id
                            );
                        },
                        onError: (errors) => {
                            console.error(
                                "Error creating sale for product",
                                item.product_id,
                                errors
                            );
                        },
                    }
                );
            });
            Swal.fire({
                icon: "success",
                title: "¡Venta creada exitosamente!",
                showConfirmButton: false,
                timer: 1500,
            });
        }
    });
};

const PrintTicket = () => {
    if (props.order && props.order.id) {
        const ticketData = {
            total: props.order.total,
            items: props.order.order_details.length,
            cash: props.order.total,
            change: 0,
            user_id: user.name,
            updated_at: new Date().toISOString().slice(0, 19).replace("T", " "),
            created_at: new Date().toISOString().slice(0, 19).replace("T", " "),
            id: props.order.id,
            user: {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role,
            },
        };

        const ticketItems = props.order.order_details.map((item) => ({
            price: item.unit_price ?? (item.product ? item.product.price : 0),
            quantity: item.quantity,
            name: item.product ? item.product.name : "",
        }));

        const businessInfo = {
            name: "Hotel Ronda Minerva",
            address:
                "Av. Adolfo López Mateos Sur 265, Jardines del Bosque, 44520 Guadalajara, Jal.",
            phone: "33 3121 4700",
        };

        // Cambia `data` por `params` para una petición GET
        axios
            .get("/print-ticket/", {
                params: {
                    data: {
                        ticketData: ticketData,
                        ticketItems: ticketItems, // Envía el array directamente
                        businessInfo: businessInfo,
                    },
                },
            })
            .then((response) => {
                // Verifica que la respuesta contenga la URL
                if (response.data && response.data.printData) {
                    const printUrl = "print://" + response.data.printData;
                    console.clear();
                    console.log(printUrl);

                    window.location.href = printUrl;
                    console.log(
                        "Ticket data sent successfully:",
                        response.data
                    );
                } else {
                    console.error("No print data received from server.");
                }
            })
            .catch((error) => {
                console.error("Error sending ticket data:", error);
            });
    } else {
        console.error("No order data available to print ticket.");
    }
};
</script>

<template>
    <h2 class="text-lg font-semibold mt-2 mb- text-center text-white">
        Detalles de la orden
    </h2>
    <div
        class="bg-white p-6 rounded-lg shadow-md flex overflow-y-auto justify-between max-h-64 hover:scale-[1.02] transition-all transform ease-in-out duration-300"
    >
        <div class="flex flex-col gap-2">
            <template
                v-if="
                    order && order.order_details && order.order_details.length
                "
            >
                <span v-for="item in order.order_details" :key="item.id">
                    <img
                        v-if="
                            item.product &&
                            foodCategories.includes(item.product.category_id)
                        "
                        src="assets/icons/svg/details/utensils.svg"
                        alt="Food Icon"
                        class="inline w-5 ml-2 cursor-pointer"
                    />
                    <img
                        v-else-if="
                            item.product &&
                            drinkCategories.includes(item.product.category_id)
                        "
                        src="assets/icons/svg/details/drink.svg"
                        alt="Drink Icon"
                        class="inline w-5 ml-2 cursor-pointer"
                    />
                    {{ item.quantity }} x
                    {{ item.product ? item.product.name : "" }}
                </span>
            </template>
            <span v-else class="text-gray-400"
                >No hay detalles para esta orden.</span
            >
        </div>

        <div
            v-if="order && order.order_details && order.order_details.length"
            class="h-fit overflow-auto flex flex-col gap-2 items-end"
        >
            <div v-for="item in order.order_details" :key="item.id">
                <span
                    class="text-center"
                    v-if="item.product && item.product.category_id === 2"
                ></span>

                $ {{ item.unit_price }}
            </div>
        </div>
        <span v-else class="text-gray-400"
            >No hay detalles para esta orden.</span
        >
    </div>
    <button
        @click="$inertia.get(`/Order/Edit/${order.id}`)"
        v-if="order?.status === 'En curso'"
        class="bg-orangeYellow hover:bg-yellow-600 transform transition-all duration-300 ease-in-out text-white font-bold py-2 px-4 rounded"
    >
        Editar Orden
    </button>
    <div
        class="bg-white p-6 rounded-lg shadow-md flex h-fit justify-between hover:scale-[1.02] transition-all transform ease-in-out duration-300"
    >
        <h3 class="font-bold text-3xl">Total:</h3>
        <div class="flex items-center gap-4">
            <span class="font-bold text-2xl 2xl:text-3xl">
                $ {{ props.order?.total ? props.order.total : "0.00" }}</span
            >
            <button
                @click="openModal = true"
                v-if="props.order?.status === 'En curso'"
                class="bg-approveGreen hover:bg-green-900 transition-all transform duration-300 ease-in-out text-white px-4 py-2 rounded-lg"
            >
                Cobrar
            </button>

            <button
                @click="PrintTicket"
                v-if="props.order?.status === 'En curso'"
                class="bg-approveGreen hover:bg-green-900 transition-all transform duration-300 ease-in-out text-white px-4 py-2 rounded-lg"
            >
                Imprimir Ticket
            </button>
        </div>

        <Modal v-model:show="openModal" @close="openModal = false">
            <div
                class="p-8 bg-gradient-to-br from-white via-gray-50 to-gray-200 rounded-2xl shadow-2xl mx-auto"
            >
                <h3
                    class="text-2xl font-extrabold text-gray-800 mb-2 flex items-center gap-2"
                >
                    <svg
                        class="w-7 h-7 text-approveGreen"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path d="M12 8v4l3 3"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                    Cobrar Orden
                </h3>
                <p class="mb-6 text-gray-600 text-base">
                    ¿Cuál será el
                    <span class="font-semibold text-approveGreen"
                        >método de pago</span
                    >
                    para esta orden?
                </p>
                <select
                    v-model="paymentMethod"
                    class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-approveGreen focus:outline-none transition mb-4"
                >
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                </select>

                <div v-if="paymentMethod === 'Tarjeta'" class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2"
                        >El cliente gusta dejar propina de:</label
                    >
                    <select
                        v-model="tip_percent"
                        class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-approveGreen focus:outline-none transition mb-2"
                    >
                        <option value="0">No dejar propina</option>
                        <option value="5">5%</option>
                        <option value="10">10%</option>
                        <option value="15">15%</option>
                        <option value="20">20%</option>
                        <option value="otherTip">Otro</option>
                    </select>
                    <div v-if="tip_percent === 'otherTip'" class="mt-2">
                        <label class="block text-gray-700 font-medium mb-1"
                            >Ingrese el monto de propina:</label
                        >
                        <input
                            v-model="otherTip"
                            type="number"
                            min="0"
                            class="border border-gray-300 rounded-lg p-3 w-full focus:ring-2 focus:ring-approveGreen focus:outline-none transition"
                            placeholder="Monto de propina"
                        />
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <button
                        @click="openModal = false"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg transition"
                    >
                        Cancelar
                    </button>
                    <button
                        @click="createSale"
                        class="bg-approveGreen hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition"
                    >
                        Sí, Cobrar
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>
