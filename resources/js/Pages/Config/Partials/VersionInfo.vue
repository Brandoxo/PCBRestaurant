<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
version: String,
});

const page = usePage();
const version = computed(() => page.props.version);
</script>

<template>
    <div class="p-2 bg-white rounded-lg shadow-md text-center">
        <h3 class="text-lg font-semibold mb-2">Versión del Sistema</h3>
        <p class="text-gray-600">{{ version ?? 'Desconocida' }}</p>
    </div>
</template>
