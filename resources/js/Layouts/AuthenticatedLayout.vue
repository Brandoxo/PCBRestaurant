<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>


<template>
    <div class="min-h-screen flex bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-secondary shadow border-r border-gray-200 flex flex-col fixed h-full">
            <div class="flex items-center mb-8 w-full bg-white py-4">
                <Link :href="route('dashboard')">
                    <ApplicationLogo class="h-10 w-auto fill-current text-gray-800" />
                </Link>
            </div>
            <div class="py-4 px-2">
            <nav class="flex-1">
                <ul class="space-y-10">
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </NavLink>
                    </li>
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Mesas
                        </NavLink>
                    </li>
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Ventas
                        </NavLink>
                    </li>
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Categorías
                        </NavLink>
                    </li>
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Ordenes
                        </NavLink>
                    </li>
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Configuración
                        </NavLink>
                    </li>
                </ul>
            </nav>
            </div>
            <div class="mt-auto py-4 px-2">
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                {{ $page.props.auth.user.name }}
                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </aside>

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
