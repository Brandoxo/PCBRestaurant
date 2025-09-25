<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-transparent">
        <div>
            <Link href="/">
                <ApplicationLogo class="h-28 text-gray-300" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white/80 shadow-md sm:max-w-md sm:rounded-lg z-20"
        >
            <slot />
        </div>  
    </div>
</template>
