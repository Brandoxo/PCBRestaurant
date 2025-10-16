<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import Swal from "sweetalert2";
import { router, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const user = usePage().props.auth.user;

const props = defineProps({ order: Object });

console.log("order in:", props.order);

const confirmCancelOrder = () => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cancelar orden",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            cancelOrder();
            Swal.fire("¡Cancelada!", "La orden ha sido cancelada.", "success");
        }
    });
};

const cancelOrder = async () => {
    try {
        await router.post(
            `/orders/update/${props.order.id}`,
            { status: "Cancelada", user_name: user.name },
            {
                preserveState: true,
                onSuccess: () => {
                    props.order.status = "Cancelada";
                    console.log("Orden cancelada");
                },
                onError: (errors) => {
                    console.error("Error al cancelar la orden:", errors);
                },
            }
        );
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
};

const canCancelOrder = computed(() => {
    return user && user.permissions && user.permissions.includes("cancel orders");
});
console.log("User permissions:", user ? user.permissions : "No user");
</script>

<template>
    <div
        class="bg-white shadow-md rounded-lg p-2 px-4 w-3/4 lg:w-full mt-4 mx-auto hover:scale-[1.02] transition-all transform ease-in-out duration-300"
        v-if="order"
    >
        <div
            v-if="order.status === 'Cancelada'"
            class="flex bg-red-100 text-center text-red-600 py-1 rounded-full text-sm font-semibold w-full mt-2 lg:mt-0"
        >
            <span class="mx-auto">
                Cancelada por {{ order.cancelled_by ?? "Admin" }}
            </span>
        </div>
        <div
            v-if="order.status === 'Completada'"
            class="flex bg-approveGreen/30 text-center text-approveGreen py-1 rounded-full text-sm font-semibold w-full mt-2 lg:mt-0"
        >
            <span class="mx-auto"> Venta Finalizada </span>
        </div>
        <div class="lg:flex justify-between items-center mx-auto">
            <h2 class="md:text-lg lg:text-xl">
                Orden
                <span class="font-extrabold"
                    >ID #
                    {{ order.id ?? "0000" }}
                </span>
                <img
                    src="assets/icons/svg/links/copy.svg"
                    alt="Copy Icon"
                    class="inline w-6 ml-2 cursor-pointer"
                />
            </h2>
            <StatusBadge class="" />
        </div>
        <div class="flex items-center justify-between gap-4 mt-4">
            <div class="flex-col">
                <h2 class="text-center text-3xl font-bold">
                    Mesa
                    {{ order.table.number ?? "7" }}
                </h2>
                <div class="flex text-sm mt-2">
                    <p class="text-center text-secondary">
                        {{
                            order.date_time
                                ? new Date(order.date_time)
                                      .toLocaleTimeString()
                                      .slice(0, -1)
                                : "-"
                        }}
                    </p>
                    <p class="text-center text-secondary">
                        {{
                            order.date_time
                                ? new Date(order.date_time).toLocaleDateString()
                                : "-"
                        }}
                    </p>
                </div>
            </div>
            <button
                v-if="order.status === 'En curso' && canCancelOrder"
                @click="confirmCancelOrder"
                class="mt-4 bg-dangerRed/30 max-w-96 p-2 rounded-md w-full uppercase text-dangerRed tracking-widest font-extrabold hover:bg-dangerRed/50 transition"
            >
                Cancelar
            </button>
            <button class="gap-2 text-center">
                <img
                    src="assets/icons/svg/links/sheet.svg"
                    alt="Imagen de la orden"
                    class="w-6 mx-auto"
                />
                <p class="text-center text-midBlue font-bold">
                    Export
                </p>
                <p class="text-center text-midBlue font-bold">
                   #{{ order.id ?? "" }}
                </p>
            </button>
        </div>
    </div>

    <!-- wait for order details -->
    <div v-else class="mx-auto w-3/4 bg-white rounded-lg p-4 mt-4">
        <div class="flex animate-pulse space-x-4">
            <div class="size-10 rounded-full bg-gray-200"></div>
            <div class="flex-1 space-y-6 py-1">
                <div class="h-2 rounded bg-gray-200"></div>
                <div class="space-y-3">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2 h-2 rounded bg-gray-200"></div>
                        <div class="col-span-1 h-2 rounded bg-gray-200"></div>
                    </div>
                    <div class="h-2 rounded bg-gray-200"></div>
                </div>
            </div>
        </div>
    </div>
</template>
