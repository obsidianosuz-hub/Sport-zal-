<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { isSystemUnlocked } from '@/store.js';

const props = defineProps({
    histories: Array
});

const clearHistory = () => {
    if (confirm('Barcha kassa tarixini o\'chirishga ishonchingiz komilmi?')) {
        router.delete(route('cashier.history.destroyAll'));
    }
};

const markAsLeft = (id) => {
    router.put(route('cashier.history.leave', id));
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('uz-UZ') + ' ' + d.toLocaleTimeString('uz-UZ', { hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <Head title="Kassa tarixi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-white">Kassa tarixi</h2>
                <button v-if="histories.length > 0" @click="clearHistory" class="bg-red-500/10 text-red-400 hover:bg-red-500/20 hover:text-red-300 px-4 py-2 rounded-lg font-medium transition-colors border border-red-500/20 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Tarixni tozalash
                </button>
            </div>
        </template>

        <div class="py-6 h-full flex flex-col" v-if="isSystemUnlocked">
            <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
                    <!-- Main Table -->
                    <div class="lg:col-span-3 backdrop-blur-xl bg-[#1a1a2e]/80 rounded-xl shadow-xl border border-white/10 overflow-hidden">
                        <div class="p-5 border-b border-white/10">
                            <h3 class="text-lg font-bold text-white">Barcha to'lovlar</h3>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-semibold text-gray-400">
                                    <tr>
                                        <th class="px-6 py-4">Mijoz (Yashil)</th>
                                        <th class="px-6 py-4">Kelgan Vaqti (Qizil)</th>
                                        <th class="px-6 py-4">Ketgan Vaqti</th>
                                        <th class="px-6 py-4 text-right">To'lagan Summasi (Sariq)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/10">
                                    <tr v-for="history in histories" :key="history.id" class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-emerald-400">{{ history.client?.name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-red-400 font-medium">{{ formatDate(history.arrived_at) }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div v-if="history.left_at" class="text-gray-400 font-medium">
                                                {{ formatDate(history.left_at) }}
                                            </div>
                                            <button v-else @click="markAsLeft(history.id)" class="text-xs font-bold text-red-400 bg-red-500/10 hover:bg-red-500/20 px-3 py-1.5 rounded border border-red-500/20 transition-colors">
                                                Chiqib ketish
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="font-bold text-amber-400">{{ history.amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} so'm</div>
                                        </td>
                                    </tr>
                                    <tr v-if="histories.length === 0">
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            Hozircha kassa tarixi bo'sh.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1 backdrop-blur-xl bg-[#1a1a2e]/80 rounded-xl shadow-xl border border-white/10 overflow-hidden">
                        <div class="p-4 border-b border-white/10 bg-white/5">
                            <h3 class="font-bold text-white">Mijozlar Holati</h3>
                        </div>
                        <div class="divide-y divide-white/10 max-h-[600px] overflow-y-auto">
                            <div v-for="history in histories" :key="'side-' + history.id" class="p-4 flex flex-col hover:bg-white/5 transition-colors">
                                <div class="font-bold text-emerald-400 text-sm mb-1.5">{{ history.client?.name }}</div>
                                <div class="flex justify-between items-center text-xs">
                                    <div class="font-medium">
                                        <span class="text-gray-500">Kirgan:</span>
                                        <span class="text-white ml-1">{{ formatDate(history.arrived_at) }}</span>
                                    </div>
                                    <div class="font-medium">
                                        <span v-if="history.left_at" class="text-gray-400">{{ formatDate(history.left_at) }}</span>
                                        <span v-else class="text-blue-400 animate-pulse">Zalda</span>
                                    </div>
                                </div>
                            </div>
                            <div v-if="histories.length === 0" class="p-4 text-center text-sm text-gray-500">
                                Mijozlar yo'q
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
