<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="API Hujjatlari" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-white">API Hujjatlari</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Content Panel -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-2xl blur-xl"></div>
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden p-8">
                        <h3 class="text-xl font-bold text-white mb-4">RESTful API Tizimi</h3>
                        <p class="text-gray-300">Bu yerda siz tashqi tizimlar bilan ishlash uchun tayyorlangan API lar ro'yxati bilan tanishishingiz mumkin.</p>
                        
                        <div class="mt-8 space-y-6 text-gray-300">
                            <!-- Auth -->
                            <div class="bg-black/30 p-4 rounded-xl border border-white/5">
                                <h4 class="text-emerald-400 font-bold mb-2">POST /api/auth/login</h4>
                                <p class="text-sm mb-2">Tizimga kirish va Access Token olish.</p>
                                <code class="text-xs bg-black/50 p-2 rounded block text-blue-300">Body: { "email": "...", "password": "..." }</code>
                            </div>

                            <!-- Members -->
                            <div class="bg-black/30 p-4 rounded-xl border border-white/5">
                                <h4 class="text-blue-400 font-bold mb-2">GET /api/members</h4>
                                <p class="text-sm mb-2">Barcha mijozlarni qidirish va filtrlash.</p>
                                <code class="text-xs bg-black/50 p-2 rounded block text-blue-300">Headers: { "Authorization": "Bearer {token}" }</code>
                            </div>

                            <!-- Attendance -->
                            <div class="bg-black/30 p-4 rounded-xl border border-white/5">
                                <h4 class="text-purple-400 font-bold mb-2">POST /api/attendance/check-in</h4>
                                <p class="text-sm mb-2">Abonement muddati tugamagan mijozlar uchun tashrif (check-in) belgilash.</p>
                                <code class="text-xs bg-black/50 p-2 rounded block text-blue-300">Body: { "client_id": 1 }</code>
                            </div>

                            <!-- Subscription Update -->
                            <div class="bg-black/30 p-4 rounded-xl border border-white/5">
                                <h4 class="text-yellow-400 font-bold mb-2">POST /api/subscriptions/update</h4>
                                <p class="text-sm mb-2">Mijoz abonementini uzaytirish.</p>
                                <code class="text-xs bg-black/50 p-2 rounded block text-blue-300">Body: { "client_id": 1, "amount": 150000, "months": 1 }</code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
