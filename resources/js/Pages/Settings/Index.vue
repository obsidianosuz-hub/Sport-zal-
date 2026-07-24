<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineProps({
    users: Array,
    roles: Array,
    permissions: Array,
});

const page = usePage();
const user = page.props.auth.user;
const { t } = useI18n();

import { router } from '@inertiajs/vue3';

const activeTab = ref('general'); // 'general', 'employees', 'security'

const activeRole = typeof window !== 'undefined' ? (localStorage.getItem('activeRoleFilter') || '') : '';
const canEditPermissions = activeRole === '' || activeRole === 'admin';

const availableModules = [
    { name: 'Umumiy Statistika', route: 'dashboard' },
    { name: 'Xodimlar Boshqaruvi', route: 'employees.index' },
    { name: 'Xodimlar Oyligi', route: 'salaries.index' },
    { name: 'Mijozlar Bazasi', route: 'clients.index' },
    { name: 'Kassa', route: 'cashier.index' },
    { name: 'Fitnes Bar', route: 'kitchen.index' },
    { name: 'Sotuvlar Tarixi', route: 'sales.index' },
    { name: 'Omborxona', route: 'inventory.index' },
    { name: 'Sozlamalar', route: 'settings.index' },
];

const selectedEmployeeForRBAC = ref(null);
const showRBACModal = ref(false);

const openRBACModal = (employee) => {
    // Only Admin can access permissions editing
    if (!canEditPermissions) {
        alert("Sizda bu bo'limga kirish huquqi yo'q.");
        return;
    }
    selectedEmployeeForRBAC.value = employee;
    showRBACModal.value = true;
};

const hasPermission = (routeName) => {
    if(!selectedEmployeeForRBAC.value || !selectedEmployeeForRBAC.value.permissions) return false;
    return selectedEmployeeForRBAC.value.permissions.some(p => p.name === routeName);
};

const togglePermission = (routeName) => {
    if (!canEditPermissions) return;
    if(selectedEmployeeForRBAC.value.roles?.[0]?.name === 'admin') return;

    let currentPermissions = selectedEmployeeForRBAC.value.permissions ? selectedEmployeeForRBAC.value.permissions.map(p => p.name) : [];
    
    if(currentPermissions.includes(routeName)) {
        currentPermissions = currentPermissions.filter(p => p !== routeName);
    } else {
        currentPermissions.push(routeName);
    }

    // Optimistic update
    selectedEmployeeForRBAC.value.permissions = currentPermissions.map(name => ({name}));

    router.post(route('employees.permissions', selectedEmployeeForRBAC.value.id), {
        permissions: currentPermissions
    }, { preserveScroll: true, preserveState: true });
};

// Tillar va Mavzular uchun formalar
const uiForm = useForm({
    language: user.ui_settings?.language || 'uz',
    theme: user.ui_settings?.theme || 'dark',
    scale: user.ui_settings?.scale || 'medium',
});

// Parol o'zgartirish formasi
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

// PIN o'zgartirish formasi
const pinForm = useForm({
    pin_code: user.pin_code || '',
});

const saveUISettings = () => {
    uiForm.put(route('settings.ui'), {
        onSuccess: () => {
            router.reload({ preserveScroll: true, preserveState: false });
        },
    });
};

const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        onSuccess: () => {
            passwordForm.reset();
        },
    });
};

const updatePin = () => {
    pinForm.put(route('settings.pin'), {
        onSuccess: () => {
            pinForm.reset();
        },
    });
};
</script>

<template>
    <Head title="Sozlamalar" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white tracking-tight">{{ $t('settings.title') }}</h2>
            </div>
        </template>

        <div class="mt-6 flex flex-col md:flex-row gap-6">
            <!-- Chap panel (Tab menyu) -->
            <div class="w-full md:w-64 flex-shrink-0">
                <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden p-4 space-y-2">
                    <button 
                        @click="activeTab = 'general'" 
                        :class="activeTab === 'general' ? 'bg-gradient-to-r from-blue-600/30 to-purple-600/30 text-white border-l-4 border-blue-500' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent'"
                        class="w-full text-left px-4 py-3 rounded-r-xl font-medium transition-all"
                    >
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $t('settings.general') }}
                        </span>
                    </button>
                    
                    <button 
                        v-if="canEditPermissions"
                        @click="activeTab = 'employees'" 
                        :class="activeTab === 'employees' ? 'bg-gradient-to-r from-blue-600/30 to-purple-600/30 text-white border-l-4 border-blue-500' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent'"
                        class="w-full text-left px-4 py-3 rounded-r-xl font-medium transition-all"
                    >
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ $t('settings.employees') }}
                        </span>
                    </button>

                    <button 
                        @click="activeTab = 'security'" 
                        :class="activeTab === 'security' ? 'bg-gradient-to-r from-blue-600/30 to-purple-600/30 text-white border-l-4 border-blue-500' : 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent'"
                        class="w-full text-left px-4 py-3 rounded-r-xl font-medium transition-all"
                    >
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            {{ $t('settings.security') }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- O'ng panel (Tarkib) -->
            <div class="flex-1">
                <!-- Umumiy Sozlamalar Tab -->
                <div v-if="activeTab === 'general'" class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6">{{ $t('settings.ui_title') }}</h3>
                    <form @submit.prevent="saveUISettings" class="space-y-6 max-w-2xl">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">{{ $t('settings.sys_lang') }}</label>
                                <select v-model="uiForm.language" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-purple-500/50">
                                    <option value="uz">O'zbek tili</option>
                                    <option value="ru">Русский язык</option>
                                    <option value="en">English</option>
                                    <option value="tr">Türkçe</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">{{ $t('settings.theme') }}</label>
                                <select v-model="uiForm.theme" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-purple-500/50">
                                    <option value="dark">{{ $t('settings.theme_dark') }}</option>
                                    <option value="light">{{ $t('settings.theme_light') }}</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">{{ $t('settings.scale_title') }}</label>
                            <div class="flex gap-4">
                                <label class="flex-1 relative cursor-pointer group">
                                    <input type="radio" v-model="uiForm.scale" value="small" class="peer sr-only">
                                    <div class="p-4 border border-white/10 rounded-xl bg-white/5 peer-checked:bg-blue-500/20 peer-checked:border-blue-500/50 transition-all text-center">
                                        <span class="text-sm text-gray-300 font-medium">{{ $t('settings.scale_small') }}</span>
                                    </div>
                                </label>
                                <label class="flex-1 relative cursor-pointer group">
                                    <input type="radio" v-model="uiForm.scale" value="medium" class="peer sr-only">
                                    <div class="p-4 border border-white/10 rounded-xl bg-white/5 peer-checked:bg-purple-500/20 peer-checked:border-purple-500/50 transition-all text-center">
                                        <span class="text-base text-white font-bold">{{ $t('settings.scale_medium') }}</span>
                                    </div>
                                </label>
                                <label class="flex-1 relative cursor-pointer group">
                                    <input type="radio" v-model="uiForm.scale" value="large" class="peer sr-only">
                                    <div class="p-4 border border-white/10 rounded-xl bg-white/5 peer-checked:bg-pink-500/20 peer-checked:border-pink-500/50 transition-all text-center">
                                        <span class="text-lg text-gray-300 font-medium">{{ $t('settings.scale_large') }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)] text-white font-bold rounded-xl shadow-[0_0_15px_rgba(139,92,246,0.5)] transition-all">
                                {{ $t('settings.save') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Xodimlar va Ruxsatlar Tab -->
                <div v-if="activeTab === 'employees'" class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Xodimlar Huquqlari va Ruxsatlar</h3>
                        <Link :href="route('employees.index')" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition-all">
                            Xodimlarni Boshqarish
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-gray-300 mb-6">
                            <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Xodim</th>
                                    <th class="px-4 py-3 text-right">Roli / Lavozimi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="user in users" :key="user.id" @click="openRBACModal(user)" class="hover:bg-white/5 transition-colors cursor-pointer">
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-white">{{ user.name }}</p>
                                        <p class="text-xs text-gray-500">PIN: {{ user.pin_code }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="px-3 py-1 bg-purple-500/20 text-purple-400 rounded-full text-xs font-bold border border-purple-500/30">
                                            {{ user.roles.length > 0 ? user.roles[0].name : 'Rol yo\'q' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Xavfsizlik Tab -->
                <div v-if="activeTab === 'security'" class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-white mb-6">Xavfsizlik va PIN kod</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-lg font-medium text-gray-300 mb-4">Parolni Yangilash</h4>
                            <form @submit.prevent="updatePassword" class="space-y-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Hozirgi parol</label>
                                    <input v-model="passwordForm.current_password" type="password" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-red-500/50">
                                    <p v-if="passwordForm.errors.current_password" class="text-red-400 text-xs mt-1">{{ passwordForm.errors.current_password }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Yangi parol</label>
                                    <input v-model="passwordForm.password" type="password" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-red-500/50">
                                    <p v-if="passwordForm.errors.password" class="text-red-400 text-xs mt-1">{{ passwordForm.errors.password }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Yangi parolni takrorlang</label>
                                    <input v-model="passwordForm.password_confirmation" type="password" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-red-500/50">
                                    <p v-if="passwordForm.errors.password_confirmation" class="text-red-400 text-xs mt-1">{{ passwordForm.errors.password_confirmation }}</p>
                                </div>
                                <button type="submit" :disabled="passwordForm.processing" class="px-6 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition-all w-full border border-white/10 disabled:opacity-50">
                                    Parolni O'zgartirish
                                </button>
                            </form>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium text-gray-300 mb-4">PIN Kod (Oson Kirish)</h4>
                            <div class="bg-black/30 border border-white/10 rounded-xl p-4 mb-6">
                                <p class="text-sm text-gray-400 mb-3">Tizimga tezroq kirish uchun 4 xonali PIN kod belgilashingiz mumkin.</p>
                                <form @submit.prevent="updatePin" class="flex flex-col gap-2">
                                    <div class="flex items-center gap-4">
                                        <input v-model="pinForm.pin_code" type="text" maxlength="4" placeholder="****" class="w-24 bg-black/60 border border-white/10 rounded-xl text-white px-4 py-2 text-center text-xl tracking-[0.5em] focus:ring-2 focus:ring-purple-500/50">
                                        <button type="submit" :disabled="pinForm.processing" class="px-4 py-2 bg-purple-600/50 hover:bg-purple-600/80 text-white font-medium rounded-xl transition-all disabled:opacity-50">PIN o'rnatish</button>
                                    </div>
                                    <p v-if="pinForm.errors.pin_code" class="text-red-400 text-xs mt-1">{{ pinForm.errors.pin_code }}</p>
                                </form>
                            </div>

                            <h4 class="text-lg font-medium text-gray-300 mb-4">Kirishlar Tarixi</h4>
                            <div class="bg-black/30 border border-white/10 rounded-xl p-4 space-y-3 max-h-48 overflow-y-auto">
                                <div v-for="i in 3" :key="i" class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
                                    <div>
                                        <p class="text-white">Windows PC (Chrome)</p>
                                        <p class="text-xs text-gray-500">IP: 192.168.1.100</p>
                                    </div>
                                    <p class="text-green-400">Bugun, 10:45</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RBAC Modal -->
        <div v-if="showRBACModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showRBACModal = false"></div>
            <div class="relative bg-gray-900 border border-white/10 rounded-2xl w-full max-w-lg p-6 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">Xodim ruxsatlarini boshqarish</h3>
                    <button @click="showRBACModal = false" class="text-gray-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div v-if="selectedEmployeeForRBAC">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white">{{ selectedEmployeeForRBAC.name.charAt(0) }}</div>
                        <div>
                            <p class="text-sm text-white font-bold">{{ selectedEmployeeForRBAC.name }}</p>
                            <p class="text-xs text-blue-400">{{ selectedEmployeeForRBAC.roles?.[0]?.name ?? 'Xodim' }}</p>
                        </div>
                    </div>

                    <div v-if="selectedEmployeeForRBAC.roles?.[0]?.name === 'admin'" class="p-4 rounded-xl bg-blue-500/20 border border-blue-500/30 text-blue-300 text-sm mb-4">
                        Admin barcha bo'limlarga kirish huquqiga ega. Ularni cheklash imkonsiz.
                    </div>

                    <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
                        <div v-for="module in availableModules" :key="module.route" class="flex items-center justify-between p-3 rounded-xl bg-black/20 border border-white/5">
                            <span class="text-gray-300 text-sm font-medium">{{ module.name }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-bold" :class="(hasPermission(module.route) || selectedEmployeeForRBAC.roles?.[0]?.name === 'admin') ? 'text-green-400' : 'text-red-400'">
                                    {{ (hasPermission(module.route) || selectedEmployeeForRBAC.roles?.[0]?.name === 'admin') ? 'Ruxsat etilgan' : 'Cheklash' }}
                                </span>
                                <button 
                                    @click.stop="togglePermission(module.route)"
                                    :disabled="selectedEmployeeForRBAC.roles?.[0]?.name === 'admin'"
                                    class="w-11 h-6 rounded-full transition-colors relative focus:outline-none"
                                    :class="(hasPermission(module.route) || selectedEmployeeForRBAC.roles?.[0]?.name === 'admin') ? 'bg-green-500' : 'bg-gray-600'"
                                >
                                    <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-transform"
                                         :class="(hasPermission(module.route) || selectedEmployeeForRBAC.roles?.[0]?.name === 'admin') ? 'translate-x-6' : 'translate-x-1'"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
