<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    histories: Array,
});

const deleteHistory = (id) => {
    router.delete(route('inventory.history.destroy', id), {
        preserveScroll: true
    });
};

const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return d.toLocaleDateString('uz-UZ') + ' ' + d.toLocaleTimeString('uz-UZ', { hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <Head title="Ombor Tarixi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-white">Ombor Tarixi</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Data Table -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-2xl blur-xl"></div>
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                        <div class="p-6 border-b border-white/10">
                            <h3 class="text-xl font-bold text-white">Kamaygan Mahsulotlar Tarixi</h3>
                            <p class="text-gray-400 text-sm mt-1">Sotilgan yoki ombordan olib tashlangan mahsulotlar ro'yxati</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-gray-300">
                                <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                                    <tr>
                                        <th class="px-6 py-4">Sana / Vaqt</th>
                                        <th class="px-6 py-4">Mahsulot Nomi</th>
                                        <th class="px-6 py-4">Kamaygan Soni</th>
                                        <th class="px-6 py-4">Sabab</th>
                                        <th class="px-6 py-4 text-right">Amallar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    <tr v-if="!histories || histories.length === 0">
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tarix bo'sh</td>
                                    </tr>
                                    <tr v-for="history in histories" :key="history.id" class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 text-gray-400 text-sm">{{ formatDate(history.created_at) }}</td>
                                        <td class="px-6 py-4 font-bold text-white">{{ history.product_name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="text-red-400 font-bold text-lg">-{{ history.quantity }}</span>
                                            <span class="text-gray-500 text-sm ml-1">dona</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-lg text-sm">{{ history.reason }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button @click="deleteHistory(history.id)" class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1 inline-flex active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Tarixni o'chirish
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
