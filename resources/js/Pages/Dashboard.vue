<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const stats = [
    { name: 'Jami Mijozlar', value: '1,240', change: '+12%', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Bugungi Tashriflar', value: '142', change: '+5%', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
    { name: 'BAR Savdosi', value: '3.4M so\'m', change: '+18%', icon: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Ombor Xarajatlari', value: '1.2M so\'m', change: '-4%', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
];

const viewMode = ref('monthly');
const selectedMonth = ref(null);

const monthlyData = [
    {m:'Yanvar', v:45, label:'450'}, 
    {m:'Fevral', v:60, label:'600'}, 
    {m:'Mart', v:50, label:'500'}, 
    {m:'Aprel', v:85, label:'850'}, 
    {m:'May', v:70, label:'700'}, 
    {m:'Iyun', v:90, label:'900'}, 
    {m:'Iyul', v:100, label:'1240'},
    {m:'Avgust', v:100, label:'1300'},
    {m:'Sentabr', v:80, label:'820'},
    {m:'Oktabr', v:95, label:'1100'},
    {m:'Noyabr', v:75, label:'750'},
    {m:'Dekabr', v:98, label:'1250'}
];

const weeklyData = ref([]);

const showWeekly = (month) => {
    selectedMonth.value = month;
    viewMode.value = 'weekly';
    weeklyData.value = [
        {w:'1-Hafta', v: Math.floor(Math.random() * 40) + 40, label: Math.floor(Math.random() * 200) + 200},
        {w:'2-Hafta', v: Math.floor(Math.random() * 40) + 40, label: Math.floor(Math.random() * 200) + 200},
        {w:'3-Hafta', v: Math.floor(Math.random() * 40) + 40, label: Math.floor(Math.random() * 200) + 200},
        {w:'4-Hafta', v: Math.floor(Math.random() * 40) + 40, label: Math.floor(Math.random() * 200) + 200},
    ];
};

const showMonthly = () => {
    viewMode.value = 'monthly';
    selectedMonth.value = null;
};
</script>

<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-extrabold tracking-tight text-white flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-lg shadow-emerald-400"></span>
                Sport Zal / Oshxona Taomlari
            </h2>
        </template>
        
        <template #actions>
            <button class="px-4 py-2 rounded-xl bg-emerald-500 text-black font-bold text-xs hover:bg-emerald-400 transition-all shadow-lg shadow-emerald-500/20">
                + Yangi Qo'shish
            </button>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-6">
            <div class="p-5 rounded-2xl bg-[#0B1626]/80 border border-emerald-500/10 backdrop-blur-xl">
                <span class="text-xs text-slate-400">Bugungi Tushum</span>
                <h3 class="text-2xl font-black text-emerald-400 mt-1">1,450,000 UZS</h3>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1626]/80 border border-emerald-500/10 backdrop-blur-xl">
                <span class="text-xs text-slate-400">Faol Mijozlar</span>
                <h3 class="text-2xl font-black text-cyan-400 mt-1">42 ta</h3>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1626]/80 border border-emerald-500/10 backdrop-blur-xl">
                <span class="text-xs text-slate-400">Protein Sotuvi</span>
                <h3 class="text-2xl font-black text-amber-400 mt-1">18 porsiya</h3>
            </div>
            <div class="p-5 rounded-2xl bg-[#0B1626]/80 border border-emerald-500/10 backdrop-blur-xl">
                <span class="text-xs text-slate-400">Xodimlar Holati</span>
                <h3 class="text-2xl font-black text-indigo-400 mt-1">8 / 10 faol</h3>
            </div>
        </div>

        <!-- Jadval qismi -->
        <div class="bg-[#0B1626]/60 backdrop-blur-xl border border-emerald-500/10 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-emerald-500/10 flex items-center justify-between">
                <h3 class="font-bold text-white">Parhez Taomlar va Proteinlar Ro'yxati</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-emerald-500/10 text-[11px] uppercase tracking-wider text-slate-400 bg-emerald-500/[0.02]">
                            <th class="py-4 px-6 font-semibold">Mahsulot Nomi</th>
                            <th class="py-4 px-6 font-semibold">Kategoriya</th>
                            <th class="py-4 px-6 font-semibold">Narxi</th>
                            <th class="py-4 px-6 font-semibold">Omborda</th>
                            <th class="py-4 px-6 font-semibold text-right">Holat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-500/5 text-sm text-slate-300">
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-4 px-6 font-medium text-white">Iso-Protein 100%</td>
                            <td class="py-4 px-6 text-emerald-400">Protein</td>
                            <td class="py-4 px-6">35,000 UZS</td>
                            <td class="py-4 px-6">14 porsiya</td>
                            <td class="py-4 px-6 text-right"><span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs">Mavjud</span></td>
                        </tr>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-4 px-6 font-medium text-white">Tovuq go'shti (Parhez) + Guruch</td>
                            <td class="py-4 px-6 text-cyan-400">Parhez taom</td>
                            <td class="py-4 px-6">45,000 UZS</td>
                            <td class="py-4 px-6">8 porsiya</td>
                            <td class="py-4 px-6 text-right"><span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs">Mavjud</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
