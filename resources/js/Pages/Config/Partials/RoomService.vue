<script setup>
import { ref } from "vue";
import Switch from "@/Components/Switch.vue";
import { router } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const toast = useToast();
const props = defineProps({
    roomServiceConfig: Array,
});

const is_active = props.roomServiceConfig[0]?.is_active || false;
const service_cost = props.roomServiceConfig[0]?.service_cost || 0;

const isRoomServiceEnabled = ref(is_active);
const roomServiceCharge = ref(service_cost);

if (!isRoomServiceEnabled.value) {
    roomServiceCharge.value = 0;
}

async function saveChanges(isRoomServiceEnabled, roomServiceCharge) {
    try {
        await router.post("/Config/Update-Room-Service", {
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
                />

                <Switch v-model="isRoomServiceEnabled" />
            </label>
        </div>

        <div v-if="isRoomServiceEnabled">
            <label class="block text-sm font-medium text-gray-700"
                >Cargo Adicional por Room Service (%)</label
            >
            <input
                type="number"
                min="0"
                max="100"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                v-model.number="roomServiceCharge"
            />
        </div>
        <button
            @click="saveChanges(isRoomServiceEnabled, roomServiceCharge)"
            v-if="
                isRoomServiceEnabled !==
                    props.roomServiceConfig[0]?.is_active ||
                roomServiceCharge !== props.roomServiceConfig[0]?.service_cost
            "
            class="mt-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
        >
            Guardar Cambios
        </button>
    </div>
</template>
