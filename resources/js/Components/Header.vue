<script setup>
import ProfileImage from "./ProfileImage.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const title = computed(() => {
    if (page.url.startsWith("/categorias")) {
        return "Categorías";
    } else if (page.url.startsWith("/productos")) {
        return "Productos";
    } else if (page.url.startsWith("/ordenes")) {
        return "Órdenes";
    } else if (page.url.startsWith("/profile")) {
        return "Perfil";
    } else if (page.url.startsWith("/Categories")) {
        return "Categorías";
    } else {
        return "Dashboard";
    }
});
</script>

<template>
    <header class="bg-primary shadow">
        <div
            class="flex justify-between items-center mx-auto py-4 px-4 sm:px-6 lg:px-8 ml-64 gap-4"
        >
            <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
            <div class="flex gap-4">
                <form
                    v-if="title === 'Dashboard' || title === 'Órdenes'"
                    class="relative"
                >
                    <input
                        type="text"
                        placeholder="Buscar orden ID"
                        class="border rounded-xl p-2 pl-10 py-2"
                    />
                    <span
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <circle
                                cx="11"
                                cy="11"
                                r="8"
                                stroke="currentColor"
                                stroke-width="2"
                                fill="none"
                            />
                            <line
                                x1="21"
                                y1="21"
                                x2="16.65"
                                y2="16.65"
                                stroke="currentColor"
                                stroke-width="2"
                            />
                        </svg>
                    </span>
                </form>
                <ProfileImage />
            </div>
        </div>
    </header>
</template>
