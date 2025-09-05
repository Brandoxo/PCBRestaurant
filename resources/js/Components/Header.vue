<script setup>
import ProfileImage from "./ProfileImage.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const title = computed(() => {
    switch (page.component) {
        case "Orders/Index":
            return "Órdenes";
        case "Tables/Index":
        case "Tables/Create":
            return "Mesas";
        case "Categories/Index":
        case "Categories/Create":
            return "Categorías";
        case "Products/Index":
            return "Productos";
        case "Menu/Index":
        case "Menu/Create":
            return "Menú";
        case "Users/Index":
            return "Usuarios";
        case "Profile/Edit":
            return "Perfil";
        default:
            return "Dashboard";
    }
});
</script>

<template>
    <header class="bg-primary shadow-md h-[72px] fixed w-full lg:ml-64 z-10">
        <div
            class="flex justify-between py-[15px] px-4 sm:px-6 lg:px-8 gap-4 lg:mr-64 "
        >
            <!--hamburger menu-->
            <div class="md:hidden flex items-center">
                <button @click="">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"
                        />
                    </svg>
                </button>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 hidden md:block">{{ title }}</h1>
            <div class="flex gap-4 items-center justify-center">
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
