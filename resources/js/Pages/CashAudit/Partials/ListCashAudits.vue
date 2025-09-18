<script setup>
import { ref, computed } from "vue";
const props = defineProps({
    cashAudits: Array,
});
const searchQuery = ref("");
const filteredCashAudits = computed(() => {
    if (!searchQuery.value) {
        return props.cashAudits;
    }
    return props.cashAudits.filter((audit) =>
        audit.created_at.includes(searchQuery.value)
    );
});

const formatDate = (dateString) => {
    const options = { year: "numeric", month: "long", day: "numeric" };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>

<template>
<div class="bg-white p-4 rounded-2xl">
        <div class="flex gap-4 justify-between mx-auto">
            <input
                v-model="searchQuery"
                type="date"
                placeholder="Buscar Ventas por fecha"
                class="border p-2 rounded-lg w-full"
            />
        </div>
        <table class="w-full mt-2 border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-4">#</th>
                    <th class="p-4">Fecha de Corte</th>
                    <th class="p-4">Turno</th>
                    <!-- <th class="p-4">Fondo de Caja</th> -->
                    <th class="p-4">Total de Ventas</th>
                    <th class="p-4">Monto Final Total</th>
                    <th class="p-4">Total de Propinas</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="value in filteredCashAudits" :key="value.id">
                    <td class="border p-2 text-center">{{ value.id }}</td>
                    <td class="border p-2 text-center">
                        {{ formatDate(value.created_at) }}
                    </td>
                    <td class="border p-2 text-center">{{ value.shift }}</td>
                    <!-- <td class="border p-2 text-center">
                        ${{ (value.initial_amount) }}
                    </td> -->
                    <td class="border p-2 text-center">
                        ${{ (value.total_amount) }}
                    </td>
                    <td class="border p-2 text-center">
                        ${{ (value.final_amount) }}
                    </td>
                    <td class="border p-2 text-center">
                        ${{ (value.total_tips) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
