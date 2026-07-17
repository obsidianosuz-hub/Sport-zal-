<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const stats = [
    { name: 'Jami Mijozlar', value: '1,240', change: '+12%', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Bugungi Tashriflar', value: '142', change: '+5%', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
    { name: 'BAR Savdosi', value: '3.4M so\'m', change: '+18%', icon: 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Ombor Xarajatlari', value: '1.2M so\'m', change: '-4%', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
];
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
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-white">Tashriflar Statistikasi (Oylik)</h3>
                        <button class="px-4 py-2 bg-white/10 hover:bg-white/20 text-sm font-medium text-white rounded-lg transition-colors">
                            Batafsil
                        </button>
                    </div>
                    <div class="flex-1 flex items-center justify-center border border-dashed border-white/20 rounded-xl bg-black/20">
                        <p class="text-gray-400 font-medium">Grafik (Chart.js yoki ApexCharts) hududi</p>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Panel -->
            <div class="relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-20 transition duration-500"></div>
                <div class="relative p-6 backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl h-96 flex flex-col">
                    <h3 class="text-lg font-bold text-white mb-6">Oxirgi Xaridlar (BAR)</h3>
                    <div class="flex-1 overflow-y-auto space-y-4 pr-2">
                        <div v-for="i in 5" :key="i" class="flex items-center gap-4 p-3 bg-white/5 hover:bg-white/10 rounded-xl transition-colors cursor-pointer border border-white/5">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-200 truncate">Protein Cocktail</p>
                                <p class="text-xs text-gray-400 truncate">2 daqiqa oldin</p>
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
