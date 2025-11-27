<script setup>
import { computed, ref } from "vue";
import Switch from "@/Components/Switch.vue";
import { router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";
import { isAdmin } from "@/utils/isAdmin";

const toast = useToast();
const props = defineProps({
    roomServiceConfig: Array,
});

const initialIsActive =
    props.roomServiceConfig.length > 0
        ? props.roomServiceConfig[0].is_active
        : false;
const isRoomServiceEnabled = ref(initialIsActive === 1);
const roomServiceCharge = ref(
    props.roomServiceConfig.length > 0
        ? props.roomServiceConfig[0].service_cost
        : 0
);

async function saveChanges(isRoomServiceEnabled, roomServiceCharge) {
    try {
        await router.post("/config/update-room-service", {
            is_active: isRoomServiceEnabled,
            service_cost: roomServiceCharge,
        });
        toast.success(
            `Configuración de Room Service actualizada correctamente.`
        );
    } catch (err) {
        console.error("Error updating Room Service configuration", err);
        toast.error(`Error al actualizar configuración de Room Service.`);
    }
}
</script>
<template>
    <div class="bg-white shadow-md rounded-b p-6">
        <h3 class="text-lg font-semibold">Room Service</h3>
        <p class="mb-6 text-sm text-gray-600">
            Configura las opciones de Room Service en el sistema.
        </p>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700"
                >Habilitar Room Service

                <input
                    type="checkbox"
                    class="sr-only peer relative"
                    v-model="isRoomServiceEnabled"
                    id="is_active"
                    :disabled="!isAdmin()"
                />

                <Switch v-model="isRoomServiceEnabled" />
            </label>
        </div>

        <div v-if="isRoomServiceEnabled">
            <label class="text-sm font-medium text-gray-700 flex"
                >Cargo Adicional por Room Service (%)</label
            >
            <input
                type="number"
                min="0"
                max="100"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model="roomServiceCharge"
                :disabled="!isAdmin()"
            />
        </div>
        <button
            @click="saveChanges(isRoomServiceEnabled, roomServiceCharge)"
            v-if="
            isAdmin() &&
            isRoomServiceEnabled !== (props.roomServiceConfig.length > 0 ? props.roomServiceConfig[0].is_active === 1 : false) ||
            roomServiceCharge !== (props.roomServiceConfig.length > 0 ? props.roomServiceConfig[0].service_cost : 0) 
            "
            class="mt-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 w-full self-end  "
        >
            Guardar Cambios
        </button>
        </div>
</template>
