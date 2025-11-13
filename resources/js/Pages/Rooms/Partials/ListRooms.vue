<script setup>
import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import Swal from "sweetalert2";
import Modal from "@/Components/Modal.vue";
import StatusBadge from "@/Components/StatusBadge.vue";
import CancelButton from "@/Components/CancelButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { computed, watch } from "vue";

const form = ref({ errors: {} });

const props = defineProps({ rooms: Object });
console.log("ROOMS:", props.rooms);
const openModal = ref(false);
const currentRoom = ref({});
const user = usePage().props.auth.user;
const isAdmin = computed(() => {
    console.log('DETAILS:', user);
    return user && user.roles && user.roles.includes('Admin');
})

const closeModal = () => {
    openModal.value = false;
};

const showModal = (id) => {
    const foundRoom = props.rooms.find((room) => room.id === id);
    currentRoom.value = foundRoom
        ? { ...foundRoom }
        : { id, number: "", status: "", capacity: "" };
    openModal.value = true;
};

const editRoom = (id) => {
    if (
        currentRoom.value.number ===
        props.rooms.find(
            (room) =>
                room.number === currentRoom.value.number && room.id !== id
        )?.number
    ) {
        document.querySelector(".text-red-500").classList.remove("hidden");
        return;
    }
    router.put(`/Rooms/edit/${id}`, currentRoom.value, {
        onSuccess: () => {
            useToast().success("Habitación actualizada exitosamente");
            openModal.value = false;
        },
        onError: (error) => {
            useToast().error("Error al actualizar la habitación");
            console.error("Error al actualizar habitación:", error);
        },
    });
};

const deleteRoom = (id) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar habitación",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/Rooms/${id}`, {
                onSuccess: () => {
                    useToast().success("habitación eliminada exitosamente");
                },
                onError: (error) => {
                    useToast().error("Error al eliminar habitación");
                    console.error("Error al eliminar habitación:", error);
                },
            });
        }
    });
};

const searchQuery = ref("");
const filteredRooms = computed(() => {
    if (!searchQuery.value) return props.rooms;
    return props.rooms.filter((room) =>
        room.number.toString().includes(searchQuery.value)
    );
});
</script>

<template>
    <div
        class="bg-white p-4 rounded-2xl w-full max-w-96 sm:max-w-[40rem] lg:max-w-full mx-auto"
    >
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar habitación..."
            class="border p-2 rounded-lg w-full mb-4"
        />
        <div class="overflow-y-auto lg:py-60 lg:h-screen lg:pt-0 scrollbar-hide">
        <table class="w-full mt-2 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">#</th>
                    <th class="p-4">Prefijo</th>
                    <th class="p-4">Número</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4">Capacidad</th>
                    <th v-if="isAdmin" class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in filteredRooms" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">{{ value.prefix }}</td>
                    <td class="border p-2 text-center">{{ value.number }}</td>
                    <td class="border p-2 text-center">
                        <StatusBadge :status="value.status" />
                    </td>
                    <td class="border p-2 text-center">
                        {{ value.capacity }} Personas
                    </td>
                    <td
                        v-if="isAdmin &&
                            value.status === 'Libre' ||
                            value.status === 'Reservada'
                        "
                        class="border p-2 flex gap-2 justify-center"
                    >
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
                            @click="deleteRoom(value.id)"
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
    </div>

    <Modal v-model:show="openModal">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Editar Categoría</h3>
            <form class="flex flex-col">
                <div>
                    <InputLabel
                        for="number"
                        value="Número de habitación"
                        class="mb-2"
                    ></InputLabel>
                    <input
                        v-model="currentRoom.number"
                        type="text"
                        placeholder="Numero de habitación"
                        class="border p-2 rounded-lg mb-4 w-full"
                    />
                    <InputError :message="form.errors.number"></InputError>
                    <span class="text-red-500 hidden"
                        >Este número de habitación ya está en uso.</span
                    >
                </div>

                <div class="flex justify-between">
                    <div>
                        <InputLabel
                            for="prefix"
                            value="Prefijo"
                            class="m-2"
                        ></InputLabel>
                        <input
                            v-model="currentRoom.prefix"
                            type="text"
                            placeholder="Prefijo"
                            class="border p-2 rounded-lg mb-4"
                        />
                        <InputError :message="form.errors.prefix"></InputError>
                    </div>
                    <div>
                        <InputLabel
                            for="status"
                            value="Estado"
                            class="m-2"
                        ></InputLabel>
                        <select
                            v-model="currentRoom.status"
                            class="border p-2 rounded-lg mb-4"
                        >
                            <option value="" disabled selected>
                                Selecciona un estado
                            </option>
                            <option value="Libre">Libre</option>
                            <option value="Reservada">Reservada</option>
                        </select>
                        <InputError :message="form.errors.status"></InputError>
                    </div>
                    <div>
                        <InputLabel
                            for="capacity"
                            value="Capacidad"
                            class="m-2"
                        ></InputLabel>
                        <input
                            v-model="currentRoom.capacity"
                            type="text"
                            placeholder="Capacidad"
                            class="border p-2 rounded-lg mb-4"
                        />
                        <InputError
                            :message="form.errors.capacity"
                        ></InputError>
                    </div>
                </div>
                <div class="flex justify-between">
                    <CancelButton @click="closeModal">Cancelar</CancelButton>
                    <button
                        @click="editRoom(currentRoom.id)"
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
