<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex flex-col sm:justify-center items-center p-64 pt-0 pb-0">
        
        
        <div>
            <Link href="/">
                <ApplicationLogo class="h-24 rounded-lg" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white/80 shadow-md sm:max-w-md sm:rounded-lg z-20"
        >
            <slot />
        </div>  
    </div>
</template>
