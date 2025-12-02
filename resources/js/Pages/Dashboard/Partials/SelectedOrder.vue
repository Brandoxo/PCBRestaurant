<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import Swal from "sweetalert2";
import { router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";

const user = usePage().props.auth.user;

const props = defineProps({ order: Object, notes: Array });

console.log("notes in SelectedOrder:", props.notes);
console.log("order in:", props.order);

const confirmCancelOrder = () => {
    Swal.fire({
        title: "¿Estás seguro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, cancelar orden",
        cancelButtonText: "Cancelar",
        input: 'text',
        inputLabel: 'Motivo de la cancelación',
        inputPlaceholder: 'Escribe el motivo aquí...',
        inputValidator: (value) => {
            if (!value) {
                return '¡Necesitas escribir un motivo!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value;
            cancelOrder(reason);
            Swal.fire("¡Cancelada!", "La orden ha sido cancelada.", "success");
        }
    });
};

const cancelOrder = async (reason) => {
    try {
        await router.post(
            `/orders/update/${props.order.id}`,
            { status: "Cancelada", user_name: user.name, cancellation_reason: reason},
            {
                preserveState: true,
                onSuccess: () => {
                    props.order.status = "Cancelada";
                    console.log("Orden cancelada");
                    props.notes.cancellation_reason = reason;
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
    return (
        user && user.permissions && user.permissions.includes("cancel orders")
    );
});
console.log("User permissions:", user ? user.permissions : "No user");

const filteredNotes = computed(() => {
    if (!props.order || !props.notes) return [];
    return props.notes.filter(
        (note) => note.order_id === props.order.id   
    );
});
console.log("Filtered notes:", filteredNotes.value);

const noteContents = ref([]);
watch(
    filteredNotes,
    (newNotes) => {
        noteContents.value = newNotes.map((note) => note.content);
    }, 
    { immediate: true }
);

const saveNote = async (noteId, content) => {
    try {
        await router.put(
            `/Notes/Update/${noteId}`,
            { content: content },
            {
                preserveState: true,
                onSuccess: () => {
                    console.log("Nota guardada");
                },
                onError: (errors) => {
                    console.error("Error al guardar la nota:", errors);
                },
            }
        );
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }   

}
const labels = [{status: 'En curso', input: 'Notas:'}, {status: 'Cancelada', input: "Motivo:"}, {status: 'Completada', input: 'Notas:'}];  

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
        <div class="lg:flex justify-between items-center">
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
            <div v-for="note in filteredNotes" :key="note.id" class="flex items-center gap-2 mt-2 lg:mt-0 2xl:w-3/4">
                <p class="uppercase font-extrabold text-sm">{{ labels.find(label => label.status === order.status)?.input }}</p>
                <input
                type="text"
                :value="order.status === 'Cancelada' ? note.cancellation_reason : note.content"
                @input="note.content = $event.target.value"
                @blur="saveNote(note.id, note.content)"
                class="border border-gray-300 rounded-md p-2 w-full bg-yellow-50"
                :disabled="order.status === 'Cancelada' || order.status === 'Completada'"
                />
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 inline text-black"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h5m-1.5-10l4 4m0 0l4-4m-4 4V3"
                    />
                </svg>
            </div>
        </div>
        <div class="flex items-center justify-between gap-4 mt-4">
            <div class="flex-col">
                <h2 class="text-center text-3xl font-bold">
                    {{
                        order.table?.name ??
                        order.room?.name.slice(0, 3) + "." ??
                        "N/A"
                    }}
                    <span v-if="order.room"
                        >{{ order.room?.prefix }}-{{ order.room?.number }}</span
                    >
                    <span v-else-if="order.table">{{
                        order.table?.number
                    }}</span>
                </h2>
                <div class="flex text-sm mt-2 gap-2 justify-between">
                    <p class="text-center text-secondary">
                        {{
                            order.date_time
                                ? new Date(order.date_time)
                                      .toLocaleTimeString()
                                      .slice(0, 5)
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
                class="py-4 bg-dangerRed/30 max-w-96 p-2 rounded-md w-1/2 uppercase text-dangerRed tracking-widest font-extrabold hover:bg-dangerRed/50 transition"
            >
                Cancelar
            </button>
            <button class="gap-2 text-center">
                <img
                    src="assets/icons/svg/links/sheet.svg"
                    alt="Imagen de la orden"
                    class="w-6 mx-auto"
                />
                <p class="text-center text-midBlue font-bold">Export</p>
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
