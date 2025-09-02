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
                    src="/path/to/profile-image.jpg"
                    alt="Profile Image"
                    class="h-10 w-10 rounded-full border-2 border-white"
        />
        
        <span
            class="absolute bottom-0 right-0 h-3 w-3 bg-green-500 border-2 border-white rounded-full"
        ><span class="absolute bottom-0  right-0 w-full h-full animate-ping rounded-full bg-green-500"></span></span>
            </button>
    </template>
    <template #content>
        <DropdownLink href="/profile">Perfil</DropdownLink>
        <DropdownLink href="/logout">Cerrar Sesión</DropdownLink>
    </template>
    </Dropdown>
</template>
