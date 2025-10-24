    <script setup>
    import { ref } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import { useToast } from 'vue-toastification';
    const props = defineProps({
        users: Array,
        cashFloats: Array,
    });

    const user = usePage().props.auth.user;
    console.log('User in CashFloat component', user);


    const toast = useToast();
    const cashFloatAmount = ref(0);

    const createCashFloat = () => {
        console.log('Creando nuevo fondo de caja con monto:', cashFloatAmount.value, 'por el usuario ID:', user.id);
        router.post('/CashFloat/Store', {
            amount: cashFloatAmount.value,
            user_id: user.id,
        }, {
            onSuccess: () => {
                toast.success('Fondo de caja creado correctamente.');
            },
            onError: () => {
                console.error('Error al crear el fondo de caja');
                toast.error('Error al crear el fondo de caja.');
            }
        });
    };
    
    const saveCashFloat = () => {
        console.log('Monto del flotante de caja guardado:', cashFloatAmount.value, 'por el usuario ID:', user.id);
        router.post('/CashFloat/Store', {
            amount: cashFloatAmount.value,
            user_id: user.id,
        }, {
            onSuccess: () => {
                toast.success('Fondo de caja guardado correctamente.');
            },
            onError: () => {
                console.error('Error al guardar el fondo de caja');
                toast.error('Error al guardar el fondo de caja.');
            }
        });
    };


</script>

<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <h3 class="font-semibold text-lg">Fondo de Caja</h3>
        <p class="mb-6 text-sm text-gray-600">Configura el monto inicial del fondo de caja.</p>

        <div class="flex items-center">
            <label class="block text-sm font-medium text-gray-700 mr-4">Monto Inicial:</label>
            <input
                type="number"
                class="mt-1 block w-1/4 border border-gray-300 rounded-md shadow-sm p-2"
                v-model="cashFloatAmount"
                min="0"
            />
            <button
            v-if="props.cashFloats.length"
                class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md"
                @click="saveCashFloat"
            >
                Guardar
            </button>
            <button
                v-else
                class="ml-4 px-4 py-2 bg-green-400 text-white rounded-md"
                @click="createCashFloat"
            >
                Crear
            </button>
        </div>
    </div>
</template>

