<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ users: Array, roles: Array, permissions: Array });
const me = usePage().props.auth.user;

const userPermissions = props.users.map(user => user.permissions).flat();

const editingUserId = ref(null);
const editingRoleId = ref(null);
const selectedRole = ref({});

console.log('Yo merengues', me);
console.log('Usuarios', props.users);
console.log('Usuario con roles)', props.users.roles);
console.log('Permisos de usuarios', userPermissions);
console.log('Roles', props.roles);

</script>

<template>
    <Head title="Usuarios" />

    <AuthenticatedLayout>
        <div class="md:ml-64 items-center py-24 h-screen overflow-y-auto">
            <div class="mx-auto max-w-7xl space-y-6 px-6 lg:px-8">
                <h1>Listado de Usuarios</h1>
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Nombre
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Email
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Rol
                                </th>
                                <th
                                    scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                >
                                    Permisos
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="user in props.users" :key="user.id">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ user.name }} <span v-if="user.id === me.id" class="text-blue-500 font-extrabold ">(Tú)</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap ">
    <div v-for="role in user.roles" :key="role.id" style="display: inline-block;">
        <select
            @change="editingUserId = user.id; editingRoleId = role.id; selectedRole.value = $event.target.value"
        >
            <option disabled selected>{{ role.name }}</option>
            <option
                v-for="r in props.roles.filter(r => !user.roles.some(ur => ur.id === r.id))"
                :key="r.id"
                :value="r.name"
            >
                {{ r.name }}
            </option>
        </select>
        <button
            v-if="editingUserId === user.id && editingRoleId === role.id"
            class="ml-2 px-2 py-1 bg-blue-500 text-white rounded"
            @click="alert(`Cambiar rol de ${user.name} a ${selectedRole.value}`); editingUserId = null; editingRoleId = null; selectedRole.value = null;"
        >
            Guardar Cambios
        </button>
    </div>
</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <ul>
                                            <li v-for="role in user.roles" :key="role.id" class="grid grid-cols-1 2xl:grid-cols-3 gap-2">
                                                <template v-if="role.name === 'Admin'">
                                                    <span class="  font-extrabold text-center uppercase p-2 bg-green-300 text-green-600 text-sm rounded-xl">All</span>
                                                </template>
                                                <template v-else>
                                                    <span v-for="permission in role.permissions" :key="permission.id" class=" font-extrabold text-sm  p-2 rounded-xl text-center uppercase bg-green-300 text-green-600 ">{{ permission.name }}</span>
                                                </template>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>