<script setup>
import { ref } from 'vue';
import Dropdown from './Dropdown.vue';
import DropdownLink from './DropdownLink.vue';
</script>

<template>
    <Dropdown align="right" width="48">
        <template #trigger>
            <button class="flex items-center">
                <img
                    src="assets/images/profile.png"
                    alt="Profile Image"
                    class="h-10 w-10 rounded-full border-2 border-gray-300 p-1"
        />
        
        <span
            class="absolute bottom-0 right-0 h-3 w-3 bg-green-500 border-2 border-white rounded-full"
        ><span class="absolute bottom-0  right-0 w-full h-full animate-ping rounded-full bg-green-500"></span></span>
            </button>
    </template>
    <template #content>
        <DropdownLink href="/profile">Perfil</DropdownLink>
        <DropdownLink href="/logout" method="post" as="button">Cerrar Sesión</DropdownLink>
    </template>
    </Dropdown>
</template>
