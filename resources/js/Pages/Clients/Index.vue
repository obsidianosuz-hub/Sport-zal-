<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    clients: Array,
});

const showModal = ref(false);
const activeTab = ref('list'); // 'list' or 'visits'

const form = useForm({
    name: '',
    phone: '+998 ',
    subscription_type: 'oddiy',
    subscription_expires_at: '',
});

const submit = () => {
    form.post(route('clients.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
            form.phone = '+998 ';
        },
    });
};

const deleteClient = (id) => {
    if (confirm("Ushbu mijozni o'chirishni xohlaysizmi?")) {
        router.delete(route('clients.destroy', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Mijozlar" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white tracking-tight">Mijozlar va Tashriflar</h2>
                <button @click="showModal = true" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)] text-white font-bold rounded-xl shadow-[0_0_15px_rgba(139,92,246,0.5)] transition-all transform hover:-translate-y-0.5">
                    + Yangi Mijoz
                </button>
            </div>
        </template>

        <!-- Glass Panel for Table -->
        <div class="relative group mt-6">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-2xl blur-xl"></div>
            <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <!-- Search and Filter -->
                <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-96">
                        <input type="text" placeholder="Ism yoki telefon orqali qidirish..." class="w-full pl-10 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-300">
                        <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                            <tr>
                                <th class="px-6 py-4">F.I.O</th>
                                <th class="px-6 py-4">Telefon</th>
                                <th class="px-6 py-4">Obuna Turi</th>
                                <th class="px-6 py-4">Obuna Tugashi</th>
                                <th class="px-6 py-4 text-right">Amallar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-if="clients.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">Hozircha mijozlar mavjud emas</td>
                            </tr>
                            <tr v-for="client in clients" :key="client.id" class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-white text-sm">{{ client.name.charAt(0) }}</div>
                                        <p class="font-bold text-white">{{ client.name }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm">{{ client.phone ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span :class="client.subscription_type === 'vip' ? 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30' : 'bg-blue-500/20 text-blue-400 border-blue-500/30'" class="px-3 py-1 rounded-full text-xs font-bold border uppercase">{{ client.subscription_type }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ client.subscription_expires_at ?? '—' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end">
                                        <button class="text-green-400 hover:text-green-300 mx-2 font-medium transition-colors border border-green-400/30 px-3 py-1 rounded-lg bg-green-400/10">Tashrif yozish</button>
                                        <button @click="deleteClient(client.id)" class="text-red-400 hover:text-red-300 p-1.5 transition-colors border border-red-400/30 rounded-lg bg-red-400/10 hover:bg-red-400/20 ml-2" title="Mijozni o'chirish">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Client Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-gray-900 border border-white/10 rounded-2xl w-full max-w-md p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6">Yangi Mijoz Qo'shish</h3>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Ism va Familiya *</label>
                        <input v-model="form.name" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-blue-500/50" required>
                        <p v-if="form.errors.name" class="text-red-400 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Telefon raqami</label>
                        <input v-model="form.phone" type="tel" placeholder="+998 90 000 00 00" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-blue-500/50">
                        <p v-if="form.errors.phone" class="text-red-400 text-xs mt-1">{{ form.errors.phone }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Obuna Turi</label>
                            <select v-model="form.subscription_type" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-blue-500/50">
                                <option value="oddiy">Oddiy</option>
                                <option value="vip">VIP</option>
                            </select>
                            <p v-if="form.errors.subscription_type" class="text-red-400 text-xs mt-1">{{ form.errors.subscription_type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Obuna Tugashi</label>
                            <input v-model="form.subscription_expires_at" type="date" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-blue-500/50">
                            <p v-if="form.errors.subscription_expires_at" class="text-red-400 text-xs mt-1">{{ form.errors.subscription_expires_at }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-white/10">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">Bekor qilish</button>
                        <button type="submit" :disabled="form.processing || !form.name || !form.phone || !form.subscription_expires_at" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-500 hover:to-purple-500 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)] disabled:opacity-50 disabled:cursor-not-allowed">
                            Saqlash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
