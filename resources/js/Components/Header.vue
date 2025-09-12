<script setup>
import ProfileImage from "./ProfileImage.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

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
        case "Sales/Index":
            return "Ventas";
        default:
            return "Dashboard";
    }
});

const showAside = ref(false);
const openSidebar = () => {
    showAside.value = true;
};
const closeSidebar = () => {
    showAside.value = false;
};
</script>

<template>
    <header class="bg-primary shadow-md h-[72px] fixed w-full lg:ml-64 z-10">
        <div
            class="flex justify-between py-[15px] px-4 sm:px-6 lg:px-8 gap-4 lg:mr-64"
        >
            <!--hamburger menu-->
            <div class="lg:hidden flex items-center">
                <button @click="openSidebar">
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
            <h1 class="text-2xl font-bold text-gray-900 hidden md:block">
                {{ title }}
            </h1>
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

            <!--Menu responsive-->
            <transition name="fade">
                <aside
                    v-show="showAside"
                    class="fixed top-0 left-0 h-screen w-full z-50 lg:hidden"
                    @click.self="closeSidebar"
                >
                    <div
                        class="absolute inset-0 bg-black/80 transition-opacity duration-300"
                    />
                    <transition name="slide">
                        <section
                            v-show="showAside"
                            class="relative bg-white h-screen shadow-2xl w-72 max-w-[90vw] transition-transform duration-300 ease-in-out transform"
                            :class="
                                showAside
                                    ? 'translate-x-0'
                                    : '-translate-x-full'
                            "
                        >
                            <button
                                class="absolute top-4 right-4 z-50 hover:bg-gray-100 rounded-full p-1 transition"
                                @click="closeSidebar"
                            >
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
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                            <ul class="pt-16">
                                <li
                                    class="relative hover:bg-gray-100 hover:text-primary text-gray-700 border-b border-gray-200 cursor-pointer transition-colors group"
                                >
                                    <a
                                        class="text-lg font-light block"
                                        href="/dashboard"
                                        ><p class="p-4">Dashboard</p></a
                                    >
                                </li>
                                <li>
                                    <a
                                        class="relative hover:bg-gray-100 hover:text-primary text-gray-700 border-b border-gray-200 cursor-pointer transition-colors group text-lg font-light block"
                                        href="/Tables"
                                    >
                                        <p class="p-4">Mesas</p>
                                    </a>
                                </li>
                                <li
                                    class="relative hover:bg-gray-100 hover:text-primary text-gray-700 border-b border-gray-200 cursor-pointer transition-colors group"
                                >
                                    <a
                                        class="text-lg font-light block"
                                        href="/sales"
                                        ><p class="p-4">Ventas</p></a
                                    >
                                </li>
                                <li
                                    class="relative hover:bg-gray-100 hover:text-primary text-gray-700 border-b border-gray-200 cursor-pointer transition-colors group"
                                    id="liGourmet"
                                >
                                    <a
                                        class="text-lg font-light block"
                                        href="/Categories"
                                        ><p class="p-4">Categorías</p></a
                                    >
                                </li>
                                <li
                                    class="relative hover:bg-gray-100 hover:text-primary text-gray-700 border-b border-gray-200 cursor-pointer transition-colors group"
                                >
                                    <a
                                        class="text-lg font-light block"
                                        href="/Menu"
                                        ><p class="p-4">Menú</p></a
                                    >
                                </li>
                                <li
                                    class="relative hover:bg-salmon/10 hover:text-salmon text-gray-700 border-b border-gray-200 cursor-pointer transition-colors group"
                                >
                                    <a
                                        class="text-lg font-light block"
                                        href="Orders"
                                        ><p class="p-4">Ordenes</p></a
                                    >
                                </li>
                                <li
                                    class="relative hover:bg-salmon/10 hover:text-salmon text-gray-700 border-b border-gray-200 cursor-pointer transition-colors group"
                                >
                                    <a class="text-lg font-light block" href="#"
                                        ><p class="p-4">Configuración</p></a
                                    >
                                </li>
                            </ul>
                        </section>
                    </transition>
                </aside>
            </transition>
        </div>
    </header>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.slide-enter-from,
.slide-leave-to {
    transform: translateX(-100%);
}
</style>
