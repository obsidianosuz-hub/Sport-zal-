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
            <div class="flex justify-between items-center w-full">
                <h2 class="text-2xl font-bold text-white tracking-tight">Umumiy Statistika</h2>
            </div>
        </template>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div v-for="stat in stats" :key="stat.name" class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                <div class="relative p-6 backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl flex flex-col justify-between overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-400 mb-1">{{ stat.name }}</p>
                            <h3 class="text-3xl font-bold text-white tracking-tight">{{ stat.value }}</h3>
                        </div>
                        <div class="p-3 bg-white/10 rounded-xl">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm">
                        <span :class="stat.change.startsWith('+') ? 'text-green-400' : 'text-red-400'" class="font-bold flex items-center gap-1">
                            <svg v-if="stat.change.startsWith('+')" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                            {{ stat.change }}
                        </span>
                        <span class="text-gray-500 ml-2">o'tgan oydan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Tables Area (Placeholders) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Chart Area -->
            <div class="lg:col-span-2 relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-20 transition duration-500"></div>
                <div class="relative p-6 backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl h-96 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 v-if="viewMode === 'monthly'" class="text-lg font-bold text-white">Oylik O'sish Statistikasi</h3>
                            <h3 v-else class="text-lg font-bold text-white">{{ selectedMonth?.m }} Oyi - Haftalik Statistika</h3>
                            <p class="text-sm text-gray-400 mt-1">Tashriflar statistikasi va faollik</p>
                        </div>
                        <button v-if="viewMode === 'weekly'" @click="showMonthly" class="px-4 py-2 bg-green-500/10 hover:bg-green-500/20 text-sm font-bold text-green-600 rounded-lg transition-colors flex items-center gap-2 border border-green-500/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Orqaga
                        </button>
                        <button v-else class="px-4 py-2 bg-green-500/10 hover:bg-green-500/20 text-sm font-bold text-green-600 rounded-lg transition-colors border border-green-500/30">
                            Batafsil
                        </button>
                    </div>
                    
                    <!-- Custom CSS Bar Chart -->
                    <div class="flex-1 flex items-end gap-2 sm:gap-6 mt-2 pt-14 pb-6 relative w-full h-full overflow-x-auto scrollbar-hide">
                        <!-- Y-axis horizontal lines -->
                        <div class="absolute inset-x-0 inset-y-0 pt-14 pb-6 flex flex-col justify-between pointer-events-none min-w-[600px]">
                            <div class="border-b border-white/5 w-full h-0 flex items-center justify-start"><span class="text-[10px] text-gray-500 -mt-3 absolute sticky left-0">1500</span></div>
                            <div class="border-b border-white/5 w-full h-0 flex items-center justify-start"><span class="text-[10px] text-gray-500 -mt-3 absolute sticky left-0">1000</span></div>
                            <div class="border-b border-white/5 w-full h-0 flex items-center justify-start"><span class="text-[10px] text-gray-500 -mt-3 absolute sticky left-0">500</span></div>
                            <div class="border-b border-white/10 w-full h-0 flex items-center justify-start"><span class="text-[10px] text-gray-500 -mt-3 absolute sticky left-0">0</span></div>
                        </div>
                        
                        <!-- Chart Bars -->
                        <template v-if="viewMode === 'monthly'">
                            <div v-for="month in monthlyData" :key="month.m" @click="showWeekly(month)" class="relative flex-1 flex flex-col items-center justify-end h-full z-10 group cursor-pointer pl-6 min-w-[60px]">
                                <div class="w-full max-w-[48px] bg-gradient-to-t from-emerald-600 to-green-400 rounded-t-lg transition-all duration-500 group-hover:from-emerald-500 group-hover:to-green-300 group-hover:shadow-[0_0_20px_rgba(52,211,153,0.4)] relative" :style="`height: ${month.v}%`">
                                    <!-- Tooltip -->
                                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:-translate-y-1 whitespace-nowrap pointer-events-none shadow-xl border border-white/10 z-50">
                                        {{ month.label }} ta
                                    </div>
                                </div>
                                <span class="absolute -bottom-6 text-xs text-gray-400 font-medium group-hover:text-white transition-colors pl-6 whitespace-nowrap">{{ month.m }}</span>
                            </div>
                        </template>
                        <template v-else>
                            <div v-for="week in weeklyData" :key="week.w" class="relative flex-1 flex flex-col items-center justify-end h-full z-10 group cursor-pointer pl-6 min-w-[80px]">
                                <div class="w-full max-w-[60px] bg-gradient-to-t from-blue-600 to-purple-400 rounded-t-lg transition-all duration-500 group-hover:from-blue-500 group-hover:to-purple-300 group-hover:shadow-[0_0_20px_rgba(168,85,247,0.4)] relative" :style="`height: ${week.v}%`">
                                    <!-- Tooltip -->
                                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-xs font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:-translate-y-1 whitespace-nowrap pointer-events-none shadow-xl border border-white/10 z-50">
                                        {{ week.label }} ta
                                    </div>
                                </div>
                                <span class="absolute -bottom-6 text-xs text-gray-400 font-medium group-hover:text-white transition-colors pl-6 whitespace-nowrap">{{ week.w }}</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Panel -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-20 transition duration-500"></div>
                <div class="relative p-6 backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl h-96 flex flex-col">
                    <h3 class="text-lg font-bold text-gray-900 mb-6">Oxirgi Xaridlar (BAR)</h3>
                    <div class="flex-1 overflow-y-auto space-y-4 pr-2">
                        <div v-for="i in 5" :key="i" class="flex items-center gap-4 p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-colors cursor-pointer border border-white/5">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-green-700 truncate">Protein Cocktail</p>
                                <p class="text-xs text-gray-500 truncate">2 daqiqa oldin</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-green-400">+25,000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
