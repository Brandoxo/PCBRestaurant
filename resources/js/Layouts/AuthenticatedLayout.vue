<script setup>
import { ref, onMounted, watch, computed, onBeforeUnmount } from "vue";
import NavLink from "@/Components/NavLink.vue";
import Header from "@/Components/Header.vue";
import { usePage } from "@inertiajs/vue3";
import { isRoomServiceEnabled } from "@/utils/isRoomServiceEnabled";
import { isAdmin } from "@/utils/isAdmin";

const page = usePage();

const sidebarOpen = ref(false);
const showLabels = ref(false);
let labelTimer = null;

function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value;
    showLabels.value = false;
}

function showNav(item) {
    if (item.route === "Users/Index") return isAdmin();
    if (item.route === "Rooms/Index") return isRoomServiceEnabled.value;
    return true;
}

onMounted(() => {
    const saved = localStorage.getItem("sidebarOpen");
    sidebarOpen.value = saved === "true";
    showLabels.value = false;
});

watch(sidebarOpen, (val) => {
    localStorage.setItem("sidebarOpen", val);
    clearTimeout(labelTimer);
    if (val) {
        labelTimer = setTimeout(() => {
            showLabels.value = true;
        }, 100);
    } else {
        showLabels.value = false;
    }
});
</script>

<template>
    <Header></Header>
    <div class="flex">
        <!-- Sidebar -->
        <button
            @click="toggleSidebar"
            :class="[
                'absolute left-64 top-[50%] z-20 bg-secondary text-white py-4 rounded-r hover:bg-secondary-dark lg:block hidden transition-all duration-500 ease-in-out',
                sidebarOpen ? 'md:left-56' : 'md:left-20',
            ]"
        >
            <span v-if="sidebarOpen">⮜</span>
            <span v-else>⮞</span>
        </button>
        <aside
            :class="[
                'bg-secondary shadow hidden lg:flex flex-col transition-all duration-500 ease-in-out h-svh',
                sidebarOpen ? 'w-56' : 'w-20 min-w-20',
            ]"
        >
            <ul
                :class="[
                    'flex flex-col justify-start ',
                    sidebarOpen ? 'p-2' : 'p-2',
                ]"
            >
                <li
                    v-for="item in [
                        {
                            route: 'dashboard',
                            icon: 'home',
                            label: 'Dashboard',
                            active: route().current('dashboard'),
                        },
                        {
                            route: 'Tables/Index',
                            icon: 'table',
                            label: 'Mesas',
                            active:
                                route().current('Tables/Index') ||
                                route().current('Tables/Create'),
                        },
                        {
                            route: 'Rooms/Index',
                            icon: 'rooms',
                            label: 'Habitaciones',
                            active:
                                route().current('Rooms/Index') ||
                                route().current('Rooms/Create'),
                        },
                        {
                            route: 'Sales/Index',
                            icon: 'sales',
                            label: 'Ventas',
                            active: route().current('Sales/Index'),
                        },
                        {
                            route: 'Categories/Index',
                            icon: 'categories',
                            label: 'Categorías',
                            active:
                                route().current('Categories/Index') ||
                                route().current('Categories/Create'),
                        },
                        {
                            route: 'Menu/Index',
                            icon: 'products',
                            label: 'Menú',
                            active:
                                route().current('Menu/Index') ||
                                route().current('Menu/Create'),
                        },
                        {
                            route: 'Orders/Index',
                            icon: 'orders',
                            label: 'Órdenes',
                            active: route().current('Orders/Index'),
                        },
                        {
                            route: 'Users/Index',
                            icon: 'users',
                            label: 'Usuarios',
                            active: route().current('Users/Index'),
                        },
                        {
                            route: 'Config/Index',
                            icon: 'config',
                            label: 'Configuración',
                            active: route().current('Config/Index'),
                        },
                    ]"
                    :key="item.route"
                >
                    <NavLink
                        v-if="showNav(item)"
                        :href="route(item.route)"
                        :active="item.active"
                        class="flex mx-auto items-center text-lg py-1 min-w-16"
                    >
                        <img
                            :src="`/assets/icons/svg/menu/${item.icon}.svg`"
                            :alt="item.label"
                            :class="[
                                'w-7 h-7',
                                sidebarOpen
                                    ? 'bg-white rounded-full p-1'
                                    : 'mx-auto bg-white rounded-full p-1',
                            ]"
                        />
                        <span v-if="showLabels">{{ item.label }}</span>
                    </NavLink>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
