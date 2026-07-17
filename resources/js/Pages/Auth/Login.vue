<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    pin_code: '',
    password: '',
    remember: false,
});

const isAdmin = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: (errors) => {
            if (errors.password) {
                isAdmin.value = true;
            }
        }
    });
};

const checkPin = () => {
    if (form.pin_code === '7777') {
        isAdmin.value = true;
    } else {
        isAdmin.value = false;
        form.password = '';
    }
};
</script>

<template>
    <Head title="Tizimga Kirish" />

    <div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gray-900">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="/images/gym-bg.jpg" alt="Gym Background" class="w-full h-full object-cover opacity-60" />
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/40 via-purple-900/40 to-black/80 mix-blend-multiply"></div>
        </div>

        <!-- Animated glowing orbs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-600/30 rounded-full mix-blend-screen filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-600/30 rounded-full mix-blend-screen filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

        <!-- Glassmorphism Card -->
        <div class="relative z-10 w-full max-w-md p-8 sm:p-10 backdrop-blur-xl bg-white/5 border border-white/10 rounded-3xl shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] transition-all duration-300 hover:bg-white/10">
            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 tracking-tight mb-2">
                    SPORT ZAL
                </h1>
                <p class="text-gray-300 text-sm font-medium tracking-wide">BOSHQRUV TIZIMIGA XUSH KELIBSIZ</p>
            </div>

            <div v-if="status" class="mb-4 text-sm font-medium text-green-400 bg-green-400/10 p-3 rounded-lg border border-green-400/20 text-center">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- PIN Code Input -->
                <div class="space-y-2 relative group">
                    <label for="pin_code" class="block text-sm font-semibold text-gray-200 tracking-wider">PIN KOD</label>
                    <div class="relative">
                        <input
                            id="pin_code"
                            type="text"
                            v-model="form.pin_code"
                            @input="checkPin"
                            class="block w-full px-5 py-4 bg-black/40 border border-gray-600/50 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-all duration-300 backdrop-blur-sm text-center text-2xl tracking-[0.5em] font-mono"
                            placeholder="••••"
                            required
                            autofocus
                        />
                    </div>
                    <p v-if="form.errors.pin_code" class="text-red-400 text-xs font-semibold mt-1">{{ form.errors.pin_code }}</p>
                </div>

                <!-- Password Input (Animated visibility) -->
                <div class="space-y-2 overflow-hidden transition-all duration-500 ease-in-out" :class="isAdmin ? 'max-h-32 opacity-100' : 'max-h-0 opacity-0'">
                    <label for="password" class="block text-sm font-semibold text-gray-200 tracking-wider">PAROL (Admin)</label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="block w-full px-5 py-3 bg-black/40 border border-gray-600/50 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-300 backdrop-blur-sm text-center tracking-widest"
                        placeholder="Parolni kiriting..."
                        :required="isAdmin"
                    />
                    <p v-if="form.errors.password" class="text-red-400 text-xs font-semibold mt-1">{{ form.errors.password }}</p>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mt-4">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.remember" class="w-4 h-4 rounded border-gray-600/50 bg-black/40 text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-900 transition-colors" />
                        <span class="ms-3 text-sm text-gray-300 font-medium">Meni eslab qol</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full relative inline-flex items-center justify-center px-8 py-4 overflow-hidden font-bold text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl group focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900 disabled:opacity-50 transition-all hover:shadow-[0_0_20px_rgba(139,92,246,0.5)] transform hover:-translate-y-1 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)]"
                    >
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-full group-hover:h-56 opacity-10"></span>
                        <span class="relative flex items-center gap-2 text-lg tracking-wider">
                            <span v-if="form.processing">Kirilmoqda...</span>
                            <span v-else>TIZIMGA KIRISH</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
/* Custom animations for the blobs */
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
</style>
