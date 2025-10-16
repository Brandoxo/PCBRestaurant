<script setup>
import { ref, onMounted, watch } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";


const sidebarOpen = ref(false);

function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value;
}

onMounted(() => {
    const saved = localStorage.getItem("sidebarOpen");
    sidebarOpen.value = saved === "true";
});

watch(sidebarOpen, (val) => {
    localStorage.setItem("sidebarOpen", val);
});
</script>

<template>
    <Header></Header>
    <div class="flex">

        <!-- Sidebar -->
        <button
            @click="toggleSidebar"
            :class="['absolute left-64 top-[50%] z-20 bg-secondary text-white py-4 rounded-r hover:bg-secondary-dark lg:block hidden transition-all duration-100 ease-in-out', sidebarOpen ? 'lg:left-64' : 'lg:left-20']"
        >
            <span v-if="sidebarOpen">⮜</span>
            <span v-else>⮞</span>
        </button>
         <aside
            :class="[
                'bg-secondary shadow hidden lg:flex flex-col transition-all duration-100 ease-in-out',
                sidebarOpen ? 'w-64' : 'w-20'
            ]"
        >
            <ul
                :class="[
                    'flex flex-col justify-start mt-6',
                    sidebarOpen ? 'lg:gap-2 2xl:gap-4' : 'gap-2'
                ]"
            >
                <li v-for="item in [
                    { route: 'dashboard', icon: 'home', label: 'Dashboard', active: route().current('dashboard') },
                    { route: 'Tables/Index', icon: 'table', label: 'Mesas', active: route().current('Tables/Index') || route().current('Tables/Create') },
                    { route: 'Sales/Index', icon: 'sales', label: 'Ventas', active: route().current('Sales/Index') },
                    { route: 'Categories/Index', icon: 'categories', label: 'Categorías', active: route().current('Categories/Index') || route().current('Categories/Create') },
                    { route: 'Menu/Index', icon: 'products', label: 'Menú', active: route().current('Menu/Index') || route().current('Menu/Create') },
                    { route: 'Orders/Index', icon: 'orders', label: 'Órdenes', active: route().current('Orders/Index') },
                    { route: 'Users/Index', icon: 'users', label: 'Usuarios', active: route().current('Users/Index') },
                    { route: 'Config/Index', icon: 'config', label: 'Configuración', active: route().current('Config/Index') },
                ]" :key="item.route">
                    <NavLink
                        :href="route(item.route)"
                        :active="item.active"
                        class="flex mx-auto lg:mx-0 items-center  text-base "
                    >
                        <img
                            :src="`/assets/icons/svg/menu/${item.icon}.svg`"
                            :alt="item.label"
                            class="w-6"
                        />
                        <span v-if="sidebarOpen" >{{ item.label }}</span>
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
