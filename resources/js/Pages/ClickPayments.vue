<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Click To'lovlari" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-white">Click To'lovlari</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Content Panel -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-sky-600/20 rounded-2xl blur-xl"></div>
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="bg-blue-600 p-3 rounded-xl inline-flex shadow-lg shadow-blue-500/30">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-white">Click Integratsiyasi</h3>
                                <p class="text-gray-400 text-sm">Mijozlar to'lovlarini avtomatik qabul qilish va boshqarish</p>
                            </div>
                        </div>
                        
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-300">
                            <!-- Info Card 1 -->
                            <div class="bg-black/30 p-6 rounded-xl border border-white/5 hover:border-blue-500/30 transition-colors">
                                <h4 class="text-blue-400 font-bold mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    Tezkor To'lovlar
                                </h4>
                                <p class="text-sm leading-relaxed text-gray-400">Click orqali to'lovlarni real vaqtda qabul qiling. Mijozlaringiz QR kod orqali yoki to'g'ridan-to'g'ri karta orqali sport zal to'lovlarini amalga oshirishi mumkin.</p>
                            </div>

                            <!-- Info Card 2 -->
                            <div class="bg-black/30 p-6 rounded-xl border border-white/5 hover:border-blue-500/30 transition-colors">
                                <h4 class="text-sky-400 font-bold mb-3 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Avtomatlashtirish
                                </h4>
                                <p class="text-sm leading-relaxed text-gray-400">To'lov muvaffaqiyatli o'tganida mijozning abonement muddati tizim tomonidan avtomat tarzda uzaytiriladi va unga SMS/Xabarnoma yuboriladi.</p>
                            </div>
                        </div>

                        <!-- Settings Section -->
                        <div class="mt-8 pt-8 border-t border-white/10">
                            <h4 class="text-lg font-bold text-white mb-4">Ulanish Sozlamalari</h4>
                            <div class="bg-black/40 rounded-xl p-6 border border-white/5 space-y-4">
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <div>
                                        <p class="text-sm font-semibold text-white">Merchant ID</p>
                                        <p class="text-xs text-gray-500">Click tizimidagi do'kon ID raqami</p>
                                    </div>
                                    <span class="text-blue-400 text-sm font-mono bg-blue-400/10 px-3 py-1 rounded">12456</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <div>
                                        <p class="text-sm font-semibold text-white">Secret Key</p>
                                        <p class="text-xs text-gray-500">API so'rovlarini tasdiqlash kaliti</p>
                                    </div>
                                    <span class="text-gray-400 text-sm font-mono bg-white/5 px-3 py-1 rounded">••••••••••••••••</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                    <div>
                                        <p class="text-sm font-semibold text-white">Endpoint URL</p>
                                        <p class="text-xs text-gray-500">Click uchun Webhook manzili</p>
                                    </div>
                                    <span class="text-gray-400 text-sm font-mono bg-white/5 px-3 py-1 rounded">https://sportzal.uz/api/click/webhook</span>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg shadow-lg shadow-blue-500/30 transition-all">
                                    Sozlamalarni O'zgartirish
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
