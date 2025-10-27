<script setup>
import { useToast } from 'vue-toastification';
import {  router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue'; 
const props = defineProps({
    users: Array,
    roles: Array,
    permissions: Array,
    me: Object,
});

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

const updateUserRole = (userId, roleName) => {
    console.log(`Actualizar rol para el usuario ${userId} al rol ${roleName}`);
    router.post(route('/Role/Update'), { user_id: userId, role: roleName }, {
        onSuccess: () => {
            useToast().success('Rol actualizado con éxito');
            console.log('Rol actualizado con éxito');
        },
        onError: (errors) => {
            console.error('Error al actualizar el rol:', errors);
        }
    });
};

const activateUserRole = (userId, roleName) => {
    console.log(`Activar rol para el usuario ${userId} al rol ${roleName}`);
    router.post(route('/Role/Activate'), { user_id: userId, role: roleName }, {
        onSuccess: () => {
            useToast().success('Rol activado con éxito');
            console.log('Rol activado con éxito');
        },
        onError: (errors) => {
            console.error('Error al activar el rol:', errors);
        }
    });
};

</script>

<template>
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
                                <button v-if="!user.roles.length" @click="activateUserRole(user.id, 'Cocinero')" class="uppercase bg-green-500 hover:bg-green-800 text-white font-bold p-2 flex justify-center rounded">
                                    Activar Rol
                                </button>
    <div v-for="role in user.roles" :key="role.id" class="grid grid-cols-1 gap-2">
        <select
            @change="editingUserId = user.id; editingRoleId = role.id; selectedRole.value = $event.target.value; console.log('Rol seleccionado:', selectedRole.value, 'Para el usuario:', user.id)"
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
            class="p-2 bg-blue-500 hover:bg-blue-800 transform duration-300 text-white rounded text-sm"
            @click="updateUserRole(user.id, selectedRole.value)"
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
                                                    <span @change="" v-for="permission in role.permissions" :key="permission.id" class=" font-extrabold text-sm  p-2 rounded-xl text-center uppercase bg-green-300 text-green-600 ">{{ permission.name }}</span>
                                                </template>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table> 
                    </div>

</template>