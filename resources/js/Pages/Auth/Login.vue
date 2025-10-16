<script setup>
import { ref, onMounted } from "vue";
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const trayOpen = ref(false);
const showSmoke = ref(false);

const submit = () => {
    
        form.post(route("login"), {
            onFinish: () => form.reset("password"),
        });
    };

onMounted(() => {
    setTimeout(() => {
        trayOpen.value = true;
    }, 200);
});
</script>

<template>
    <div class="fixed inset-0 -z-10">
        <img src="/assets/images/fondo.png" alt="Fondo" class="object-cover w-full h-full" />
    </div>
    <div class="fixed inset-0 bg-black/20 z-0"></div>
    <div class="relative h-screen flex flex-col items-center justify-center ">
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/30 z-10"></div>

        <img
            src="/assets/images/trayUp.png"
            alt="Logo"
            class="hidden 2xl:block w-[50%] ml-40 z-20 grayscale filter blur-[2px] transition-transform duration-700"
            :class="trayOpen ? 'translate-y-0' : '-translate-y-40 opacity-0'"
        />

        <div class="smoke smoke-top">
            <div class="smoke-circle"></div>
            <div class="smoke-circle"></div>
            <div class="smoke-circle"></div>
        </div>

        <GuestLayout>
            <Head title="Iniciar Sesión" />
            <div class="flex items-center justify-center bg-white/90 rounded-lg shadow-lg p-8 border-8 border-gray-200 z-20 relative">
                <div class="w-full max-w-md bg-white/90 rounded-lg shadow-lg p-8 border border-green-200 z-20 relative">
                    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                        {{ status }}
                    </div>
                    <form @submit.prevent="submit">
                        <div>
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="password" value="Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div class="mt-4 block">
                            <label class="flex items-center">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                            </label>
                        </div>
                        <div class="mt-4 flex items-center justify-end">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Olvidaste tu contraseña?
                            </Link>
                            <PrimaryButton
                                class="ms-4"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Iniciar Sesión
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </GuestLayout>
        <img
            src="/assets/images/trayDown.png"
            alt="Logo"
            class="hidden 2xl:block w-[50%] mt-2 ml-26 h-auto z-20 grayscale filter blur-[2px] transition-transform duration-700"
            :class="trayOpen ? 'translate-y-0' : 'translate-y-40 opacity-0'"
        />
    </div>
</template>

<style scoped>
.smoke {
    position: absolute;
    left: 60%;
    top: 28%; 
    transform: translateX(-50%);
    width: 120px;
    height: 120px;
    pointer-events: none;
    z-index: 30;
}
.smoke-circle {
    position: absolute;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(200,200,200,0.7) 60%, transparent 100%);
    border-radius: 50%;
    opacity: 0.7;
    animation: smokeUp 1.2s ease-out infinite;
}
.smoke-circle:nth-child(2) {
    left: 30px;
    top: 20px;
    width: 80px;
    height: 80px;
    opacity: 0.5;
    animation-delay: 0.2s;
}
.smoke-circle:nth-child(3) {
    left: -20px;
    top: 40px;
    width: 60px;
    height: 60px;
    opacity: 0.4;
    animation-delay: 0.4s;
}
@keyframes smokeUp {
    0% { opacity: 0.7; transform: scale(0.8) translateY(0);}
    80% { opacity: 0.4; transform: scale(1.2) translateY(-40px);}
    100% { opacity: 0; transform: scale(1.4) translateY(-60px);}
}
</style>