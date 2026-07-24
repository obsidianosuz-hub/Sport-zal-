<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const isLight = computed(() => user.value?.ui_settings?.theme === 'light');
</script>

<template>
    <Head title="Click To'lovlari" />

    <AuthenticatedLayout>
        <template #header>
            <h2 :class="['font-bold text-2xl', isLight ? 'text-gray-800' : 'text-white']">Click To'lovlari</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Settings Card -->
                <div :class="[isLight ? 'bg-white border-gray-200 shadow-sm' : 'bg-[#1e2330] border-white/5 shadow-2xl', 'rounded-2xl border overflow-hidden p-6 sm:p-8']">
                    
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center border border-blue-500/20">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <h3 :class="['text-xl font-bold mb-1', isLight ? 'text-gray-900' : 'text-white']">Click Merchant Integratsiyasi</h3>
                                <p :class="['text-sm', isLight ? 'text-gray-500' : 'text-gray-400']">Click to'lov shlyuzining hisob ma'lumotlarini sozlash</p>
                            </div>
                        </div>
                        <div class="px-4 py-1.5 bg-blue-600 rounded-full font-bold text-white text-sm shadow-lg shadow-blue-600/30">
                            CLICK
                        </div>
                    </div>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Service ID -->
                        <div>
                            <label :class="['block text-xs font-bold mb-2 uppercase tracking-wide', isLight ? 'text-gray-600' : 'text-gray-400']">Click Service ID</label>
                            <input type="text" placeholder="Masalan: 32912" :class="['w-full rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all font-mono text-sm', isLight ? 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' : 'bg-[#151923] border-transparent text-gray-300 placeholder-gray-600']" />
                        </div>
                        <!-- Merchant ID -->
                        <div>
                            <label :class="['block text-xs font-bold mb-2 uppercase tracking-wide', isLight ? 'text-gray-600' : 'text-gray-400']">Click Merchant ID</label>
                            <input type="text" autocomplete="off" placeholder="Masalan: 25983" :class="['w-full rounded-xl px-4 py-3.5 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all font-mono text-sm', isLight ? 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' : 'bg-[#151923] border-transparent text-gray-300 placeholder-gray-600']" />
                        </div>
                        <!-- Secret Key -->
                        <div class="relative">
                            <label :class="['block text-xs font-bold mb-2 uppercase tracking-wide', isLight ? 'text-gray-600' : 'text-gray-400']">Click Secret Key</label>
                            <input type="password" autocomplete="new-password" placeholder="Secret Key" :class="['w-full rounded-xl pl-4 pr-10 py-3.5 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all font-mono text-sm', isLight ? 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' : 'bg-[#151923] border-transparent text-gray-300 placeholder-gray-600']" />
                            <button class="absolute right-4 top-[38px] text-gray-500 hover:text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <!-- API Token -->
                        <div class="relative">
                            <label :class="['block text-xs font-bold mb-2 uppercase tracking-wide', isLight ? 'text-gray-600' : 'text-gray-400']">Merchant API Token</label>
                            <input type="password" autocomplete="new-password" placeholder="API Token" :class="['w-full rounded-xl pl-4 pr-10 py-3.5 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all font-mono text-sm', isLight ? 'bg-gray-50 border-gray-200 text-gray-900 placeholder-gray-400' : 'bg-[#151923] border-transparent text-gray-300 placeholder-gray-600']" />
                            <button class="absolute right-4 top-[38px] text-gray-500 hover:text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Callback URL -->
                    <div class="mb-8">
                        <label :class="['block text-xs font-bold mb-2 uppercase tracking-wide', isLight ? 'text-gray-600' : 'text-gray-400']">Click Merchant Kabinetiga Kiritiladigan Tayyor URL (Callback URL):</label>
                        <div class="flex gap-3">
                            <input type="text" readonly value="http://localhost:5173/api/payment/click" :class="['flex-1 rounded-xl px-4 py-3.5 font-mono text-sm focus:outline-none', isLight ? 'bg-gray-100 border-gray-200 text-gray-900' : 'bg-[#0b0e14] border-transparent text-gray-300']" />
                            <button :class="['px-4 py-3.5 rounded-xl transition-colors border flex-shrink-0', isLight ? 'bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 border-gray-200' : 'bg-[#151923] hover:bg-[#202634] text-gray-400 hover:text-white border-transparent hover:border-white/10']">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                        <p class="mt-3 flex items-start gap-2 text-xs text-gray-500">
                            <svg class="w-4 h-4 flex-shrink-0 text-blue-500/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Click tizimi tranzaksiyalar bo'yicha server bilan bog'lanishi uchun ushbu URL ishlatiladi.</span>
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-white/5">
                        <button class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            SAQLASH
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
