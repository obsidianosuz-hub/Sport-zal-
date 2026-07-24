<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { isSystemUnlocked } from '@/store.js';

const props = defineProps({
    clients: Array,
    recent_histories: Array,
});

const isLight = computed(() => {
    // Assuming dark/light mode depends on a setting, for now default to light
    return true; 
});

const form = useForm({
    client_id: '',
    amount: ''
});

const submitPayment = () => {
    form.post(route('cashier.store'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return d.toLocaleDateString('uz-UZ') + ' ' + d.toLocaleTimeString('uz-UZ', { hour: '2-digit', minute: '2-digit' });
};

</script>

<template>
    <Head title="Kassa" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-gray-800">Kassa</h2>
            </div>
        </template>

        <div class="py-6 h-full flex flex-col" v-if="isSystemUnlocked">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1">
                
                <!-- Payment Input Form (Black Box Area in screenshot) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <form @submit.prevent="submitPayment" class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Mijozni tanlang</label>
                            <select v-model="form.client_id" required class="w-full rounded-lg border-gray-200 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled hidden>Mijozni tanlang...</option>
                                <option v-for="client in clients" :key="client.id" :value="client.id">
                                    {{ client.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.client_id" class="text-red-500 text-xs mt-1">{{ form.errors.client_id }}</div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">To'lov summasi</label>
                            <div class="relative">
                                <input v-model="form.amount" type="number" required min="0" placeholder="Masalan: 50000" class="w-full rounded-lg border-gray-200 focus:ring-blue-500 focus:border-blue-500 pr-12" />
                                <span class="absolute right-4 top-2.5 text-gray-400 text-sm">so'm</span>
                            </div>
                            <div v-if="form.errors.amount" class="text-red-500 text-xs mt-1">{{ form.errors.amount }}</div>
                        </div>
                        <div>
                            <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg transition-colors shadow-lg shadow-blue-500/30 whitespace-nowrap disabled:opacity-50">
                                To'lovni tasdiqlash
                            </button>
                        </div>
                    </form>
                </div>

                <div class="flex flex-col lg:flex-row gap-6">
                    
                    <!-- Main Table (Green, Red, Yellow areas) -->
                    <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-gray-800">Tashriflar va To'lovlar</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50/50 border-b border-gray-100 text-xs uppercase font-semibold text-gray-500">
                                    <tr>
                                        <th class="px-6 py-4">Mijoz (Yashil)</th>
                                        <th class="px-6 py-4">Kelgan Vaqti (Qizil)</th>
                                        <th class="px-6 py-4 text-right">To'lagan Summasi (Sariq)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <!-- Example Data, you can loop through real visit history later -->
                                    <tr v-for="client in clients.slice(0, 5)" :key="client.id" class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">{{ client.name }}</div>
                                            <div class="text-xs text-gray-500">{{ client.phone }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ formatDate(client.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="font-bold text-green-600">0 so'm</span>
                                        </td>
                                    </tr>
                                    <tr v-if="!clients || clients.length === 0">
                                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">Hech qanday ma'lumot yo'q</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sidebar (Blue area) -->
                    <div class="w-full lg:w-80 flex flex-col gap-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-gray-800">Barcha Mijozlar</h3>
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ clients?.length || 0 }} ta</span>
                            </div>
                            
                            <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                                <div v-for="client in clients" :key="client.id" class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 cursor-pointer transition-all">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                        {{ client.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ client.name }}</div>
                                        <div class="text-xs text-gray-500">{{ client.subscription_type === 'vip' ? 'VIP' : 'Oddiy' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Oxirgi tashriflar / Bugungi tarix (Red Area in screenshot) -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-gray-800">Oxirgi tashriflar</h3>
                                <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ recent_histories?.length || 0 }} ta bugun</span>
                            </div>
                            
                            <div class="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                <div v-for="history in recent_histories" :key="history.id" class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                        {{ history.client?.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-gray-900 text-sm truncate">{{ history.client?.name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="text-red-500 font-medium">{{ new Date(history.arrived_at).toLocaleTimeString('uz-UZ', { hour: '2-digit', minute: '2-digit' }) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="!recent_histories || recent_histories.length === 0" class="text-center py-4 text-sm text-gray-500">
                                    Bugun hali hech kim kelmadi.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #E5E7EB;
    border-radius: 20px;
}
</style>
