<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

defineProps({
    sales: Array,
});

const deleteAll = () => {
    if(confirm("Barcha sotuvlar tarixini o'chirishga ishonchingiz komilmi? Mahsulotlar omborga qaytariladi.")) {
        router.delete(route('sales.destroyAll'));
    }
};

const deleteSale = (id) => {
    if(confirm("Ushbu sotuvni o'chirishni xohlaysizmi? Mahsulot omborga qaytariladi.")) {
        router.delete(route('sales.destroy', id));
    }
};
</script>

<template>
    <Head title="Sotuvlar Tarixi" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white tracking-tight">Sotuvlar Tarixi</h2>
            </div>
        </template>

        <!-- Glass Panel for Table -->
        <div class="relative group mt-6">
            <div class="absolute inset-0 bg-gradient-to-r from-green-600/20 to-blue-600/20 rounded-2xl blur-xl"></div>
            <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/10 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Barcha Sotuvlar</h3>
                    <button v-if="sales.length > 0" @click="deleteAll" class="px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 rounded-xl text-sm font-bold transition-all">
                        Hammasini O'chirish
                    </button>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-300">
                        <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Mahsulot nomi</th>
                                <th class="px-6 py-4">Soni</th>
                                <th class="px-6 py-4">Jami Summa</th>
                                <th class="px-6 py-4">Sotuvchi</th>
                                <th class="px-6 py-4">Vaqti</th>
                                <th class="px-6 py-4 text-right">Amal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-if="sales.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Hozircha savdolar mavjud emas</td>
                            </tr>
                            <tr v-for="sale in sales" :key="sale.id" class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4 font-bold text-white">{{ sale.product?.name ?? '—' }}</td>
                                <td class="px-6 py-4">{{ sale.quantity }} ta</td>
                                <td class="px-6 py-4 font-bold text-green-400">{{ Number(sale.total_price).toLocaleString() }} so'm</td>
                                <td class="px-6 py-4 text-gray-400">{{ sale.user?.name ?? '—' }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ sale.date }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="deleteSale(sale.id)" class="px-3 py-1 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-xs font-bold transition-all">
                                        O'chirish
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
