<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? " items-center p-4 text-md font-medium leading-5 text-gray-900 focus:outline-none  transition duration-150 ease-in-out bg-white rounded-xl w-3/4 text-center"
        : " items-center  font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out text-center bg-white p-4 rounded-xl w-3/4"
);
</script>

<template>
    <div class="flex justify-center">
        <Link :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
