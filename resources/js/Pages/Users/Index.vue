<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ListUsers from './Partials/ListUsers.vue';

const props = defineProps({ users: Array, roles: Array, permissions: Array });
const me = usePage().props.auth.user;


</script>

<template>
    <Head title="Usuarios" />

    <AuthenticatedLayout>
        <div class="px-4 p-4 overflow-y-auto h-screen pt-4 py-60">
            <div class="mx-auto max-w-7xl space-y-6 px-6 lg:px-8 mb-4">
                <ListUsers
                    :users="users"
                    :roles="roles"
                    :permissions="permissions"
                    :me="me"
                    @update-user-role="updateUserRole"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>