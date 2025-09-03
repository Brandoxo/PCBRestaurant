<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen flex bg-tertiary">
        <!-- Sidebar -->
        <aside class="w-64 bg-secondary shadow hidden lg:flex flex-col fixed h-full">
            <div class="flex items-center mb-8 w-full bg-white py-4">
                <Link :href="route('dashboard')">
                    <ApplicationLogo
                        class="h-10 w-auto fill-current text-gray-800"
                    />
                </Link>
            </div>
            <div class="py-4 px-2 h-full">
                <nav class="flex-1">
                    <ul class="space-y-6 2xl:space-y-10">
                        <li>
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                                ><img src="/assets/icons/svg/menu/home.svg" alt="Inicio" class="w-6" />
                                Dashboard
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('Tables/Index')"
                                :active="route().current('Tables/Index') || route().current('Tables/Create')"
                            ><img src="/assets/icons/svg/menu/table.svg" alt="Inicio" class="w-6" />
                                Mesas
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('Sales/Index')"
                            ><img src="/assets/icons/svg/menu/sales.svg" alt="Ventas" class="w-6" />
                                Ventas
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('Categories/Index')"
                                :active="route().current('Categories/Index')"
                            ><img src="/assets/icons/svg/menu/categories.svg" alt="Productos" class="w-6" />
                                Categorías
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('Orders/Index')"
                                :active="route().current('Orders/Index')"
                            ><img src="/assets/icons/svg/menu/orders.svg" alt="Órdenes" class="w-6" />
                                Órdenes
                            </NavLink>
                        </li>
                        <li>
                            <NavLink
                                :href="route('Config/Index')"
                                :active="route().current('Config/Index')"
                            ><img src="/assets/icons/svg/menu/config.svg" alt="Configuración" class="w-6" />
                                Configuración
                            </NavLink>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <Header></Header>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Page Heading -->
            <header class="bg-primary shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl ml-64 px-2 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
