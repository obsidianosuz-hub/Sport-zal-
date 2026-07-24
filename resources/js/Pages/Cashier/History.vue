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
                <h2 class="font-bold text-2xl text-gray-800">Kassa tarixi</h2>
                <button v-if="histories.length > 0" @click="clearHistory" class="bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 px-4 py-2 rounded-lg font-medium transition-colors border border-red-200 shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Tarixni tozalash
                </button>
            </div>
        </template>

        <div class="py-6 h-full flex flex-col" v-if="isSystemUnlocked">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800">Barcha to'lovlar</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500">
                                <tr>
                                    <th class="px-6 py-4">Mijoz (Yashil)</th>
                                    <th class="px-6 py-4">Kelgan Vaqti (Qizil)</th>
                                    <th class="px-6 py-4">Ketgan Vaqti</th>
                                    <th class="px-6 py-4 text-right">To'lagan Summasi (Sariq)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="history in histories" :key="history.id" class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-emerald-600">{{ history.client?.name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-red-500 font-medium">{{ formatDate(history.arrived_at) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div v-if="history.left_at" class="text-gray-500 font-medium">
                                            {{ formatDate(history.left_at) }}
                                        </div>
                                        <button v-else @click="markAsLeft(history.id)" class="text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded border border-red-200 transition-colors">
                                            Chiqib ketish
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="font-bold text-amber-500">{{ history.amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} so'm</div>
                                    </td>
                                </tr>
                                <tr v-if="histories.length === 0">
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        Hozircha kassa tarixi bo'sh.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
