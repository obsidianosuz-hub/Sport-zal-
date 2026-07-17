<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;
const showingNavigationDropdown = ref(false);
const showingNotificationsDropdown = ref(false);

const selectedRole = ref(localStorage.getItem('activeRoleFilter') || '');
const previousRole = ref(selectedRole.value);
const showPasswordModal = ref(false);
const roleToSwitch = ref('');
const rolePasswordInput = ref('');
const passwordError = ref('');

const rolePasswords = {
    'admin': '7000admin@',
    'manager': '8000menejr@',
    'trainer': '6000treyner@',
    'cook': '9000oshpaz@',
    '': '1111ro\'llar@'
};

const handleRoleChange = (e) => {
    roleToSwitch.value = e.target.value;
    e.target.value = previousRole.value;
    
    rolePasswordInput.value = '';
    passwordError.value = '';
    showPasswordModal.value = true;
};

const confirmRoleSwitch = () => {
    if (rolePasswordInput.value === rolePasswords[roleToSwitch.value]) {
        selectedRole.value = roleToSwitch.value;
        previousRole.value = roleToSwitch.value;
        showPasswordModal.value = false;
        localStorage.setItem('activeRoleFilter', roleToSwitch.value);
        window.location.reload(); 
    } else {
        passwordError.value = 'Parol noto\'g\'ri!';
    }
};

const cancelRoleSwitch = () => {
    showPasswordModal.value = false;
};

const navigation = [
    { name: 'nav.dashboard', route: 'dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { name: 'API', route: 'api.docs', icon: 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', indent: true },
    { name: 'nav.employees', route: 'employees.index', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z' },
    { name: 'Xodimlar Oyligi', route: 'salaries.index', icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', indent: true },
    { name: 'nav.clients', route: 'clients.index', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z' },
    { name: 'nav.kitchen', route: 'kitchen.index', icon: 'M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-4a2 2 0 00-2-2h-4a2 2 0 00-2 2v4h8z' },
    { name: 'Sotuvlar Tarixi', route: 'sales.index', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', indent: true },
    { name: 'nav.inventory', route: 'inventory.index', icon: 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' },
    { name: 'Ombor tarixi', route: 'inventory.history', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', indent: true },
    { name: 'nav.settings', route: 'settings.index', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z' },
];
</script>

<template>
    <div :class="[
        'min-h-screen flex relative overflow-hidden font-sans transition-colors duration-500',
        user.ui_settings?.theme === 'light' ? 'theme-light bg-gray-50' : 'theme-dark bg-gray-900',
    ]" :style="(user.ui_settings?.scale === 'large' ? 'zoom: 1.15;' : (user.ui_settings?.scale === 'small' ? 'zoom: 0.85;' : 'zoom: 1;'))">
        <!-- Premium Animated Background -->
        <div class="fixed inset-0 z-0 transition-opacity duration-500">
            <img src="/images/gym-bg.jpg" alt="Background" class="w-full h-full object-cover opacity-30 mix-blend-luminosity" />
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/60 via-gray-900/90 to-black/90 theme-bg-overlay transition-colors duration-500"></div>
            <!-- Neon Accents -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] pointer-events-none animate-blob"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-purple-600/20 rounded-full blur-[100px] pointer-events-none animate-blob animation-delay-2000"></div>
        </div>

        <!-- Sidebar -->
        <aside class="relative z-20 w-72 flex-shrink-0 hidden md:flex flex-col backdrop-blur-2xl bg-black/40 border-r border-white/10 h-screen transition-all duration-300">
            <div class="h-20 flex items-center justify-center border-b border-white/10 px-6">
                <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 tracking-tighter animate-logo">SPORT ZAL</h1>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-2">
                <template v-for="item in navigation" :key="item.name">
                    <Link
                        v-if="(!item.indent || (item.route === 'api.docs' && (route().current('dashboard') || route().current('api.*'))) || (item.route === 'salaries.index' && (route().current('employees.*') || route().current('salaries.*'))) || (item.route === 'sales.index' && (route().current('kitchen.*') || route().current('sales.*'))) || (item.route === 'inventory.history' && (route().current('inventory.*')))) && (user.roles?.includes('admin') || user.permissions?.includes(item.route))"
                        :href="route(item.route)"
                        :class="[
                            route().current(item.route) && !item.indent
                                ? 'bg-gradient-to-r from-emerald-600/20 to-teal-600/20 text-white border-l-4 border-emerald-500' 
                                : (!item.indent ? 'text-gray-400 hover:bg-white/5 hover:text-white border-l-4 border-transparent' : ''),
                            item.indent 
                                ? (route().current(item.route) ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-emerald-300') + ' ml-8 pl-4 text-sm py-2 relative before:content-[\'\'] before:absolute before:left-[-1px] before:top-0 before:w-3 before:h-1/2 before:border-l-2 before:border-b-2 before:border-gray-600 before:rounded-bl-md' 
                                : 'px-4 py-3.5 text-base font-medium rounded-r-xl',
                            'group flex items-center transition-all duration-200'
                        ]"
                    >
                        <svg
                            :class="[
                                route().current(item.route) ? (item.indent ? 'text-emerald-400' : 'text-emerald-400') : 'text-gray-500 group-hover:text-gray-300', 
                                item.indent ? 'mr-3 w-5 h-5' : 'mr-4 h-6 w-6',
                                'flex-shrink-0 transition-colors duration-200'
                            ]"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                        </svg>
                        {{ $t(item.name) }}
                    </Link>
                </template>
            </nav>

            <div class="p-4 border-t border-white/10 bg-black/20">
                <div class="flex items-center gap-3 px-2 py-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center font-bold text-white shadow-lg">
                        {{ user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ user.name }}</p>
                        <p class="text-xs text-blue-400 truncate uppercase tracking-widest">{{ $t('auth.admin') }}</p>
                    </div>
                </div>
                <Link :href="route('logout')" method="post" as="button" class="mt-2 w-full flex items-center justify-center gap-2 px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    {{ $t('auth.logout') }}
                </Link>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 z-10 relative">
            <!-- Topbar (Mobile menu toggle + Title) -->
            <header class="h-20 flex-shrink-0 flex items-center justify-between px-6 backdrop-blur-md bg-black/20 border-b border-white/10 md:bg-transparent md:backdrop-blur-none md:border-none">
                <div class="flex items-center md:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="text-gray-300 hover:text-white">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="ml-4 text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500 tracking-wider animate-logo">SPORT ZAL</h1>
                </div>
                
                <div class="hidden md:block flex-1 mr-6">
                    <!-- Dynamic Header Slot -->
                    <slot name="header" />
                </div>

                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Add Product Shortcut Removed per user request -->

                    <!-- Notifications -->
                    <div class="relative">
                        <button @click="showingNotificationsDropdown = !showingNotificationsDropdown" class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-white/10 transition-colors relative">
                            <span v-if="page.props.notifications?.length > 0" class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-ping"></span>
                            <span v-if="page.props.notifications?.length > 0" class="absolute top-1 right-1 w-3.5 h-3.5 bg-red-500 rounded-full border border-gray-900 flex items-center justify-center text-[8px] font-bold text-white">
                                {{ page.props.notifications.length }}
                            </span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div v-if="showingNotificationsDropdown" class="absolute right-0 mt-2 w-80 bg-gray-900 border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
                            <div class="p-4 border-b border-white/10 bg-black/40">
                                <h3 class="font-bold text-white">Xabarnomalar</h3>
                            </div>
                            <div class="max-h-96 overflow-y-auto p-2 space-y-1">
                                <div v-if="!page.props.notifications || page.props.notifications.length === 0" class="p-4 text-center text-sm text-gray-500">
                                    Hozircha yangi xabarlar yo'q
                                </div>
                                <Link 
                                    v-for="notification in page.props.notifications" 
                                    :key="notification.id"
                                    :href="route(notification.route)"
                                    class="block p-3 hover:bg-white/5 rounded-xl transition-colors"
                                >
                                    <div class="flex items-start gap-3">
                                        <div :class="notification.color" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                                            <svg v-if="notification.icon === 'cube'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                            <svg v-if="notification.icon === 'user'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-white">{{ notification.title }}</h4>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ notification.message }}</p>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>
                    <!-- Settings -->
                    <Link :href="route('settings.index')" class="p-2 text-gray-400 hover:text-white rounded-full hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </Link>
                </div>
            </header>

            <!-- Mobile Menu Overlay -->
            <div v-if="showingNavigationDropdown" class="fixed inset-0 z-50 md:hidden">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showingNavigationDropdown = false"></div>
                <div class="absolute top-0 left-0 w-64 h-full bg-gray-900 border-r border-gray-800 p-4">
                    <nav class="space-y-2 mt-8">
                        <template v-for="item in navigation" :key="item.name">
                            <Link v-if="user.roles?.includes('admin') || user.permissions?.includes(item.route)" :href="route(item.route)" class="block px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-lg">
                                {{ $t(item.name) }}
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>

        <!-- Role Switch Password Modal -->
        <div v-if="showPasswordModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="cancelRoleSwitch"></div>
            <div class="relative bg-gray-900 border border-white/10 rounded-2xl w-full max-w-sm p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-2">Parolni kiriting</h3>
                <p class="text-sm text-gray-400 mb-6">Ushbu rolga o'tish uchun maxfiy parolni kiritishingiz kerak.</p>
                
                <form @submit.prevent="confirmRoleSwitch">
                    <input 
                        type="password" 
                        v-model="rolePasswordInput" 
                        placeholder="Parolni yozing..." 
                        class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-purple-500/50 outline-none mb-2"
                        autocomplete="new-password"
                        autofocus
                    >
                    <p v-if="passwordError" class="text-red-400 text-xs mb-4">{{ passwordError }}</p>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="cancelRoleSwitch" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">Bekor qilish</button>
                        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-500 hover:to-purple-500 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all">
                            Tasdiqlash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
/* Utilities for scrollbar and animations */
::-webkit-scrollbar { width: 8px; height: 8px; }
::-webkit-scrollbar-track { background: rgba(0,0,0,0.2); }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
@keyframes shine {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-3px); }
  100% { transform: translateY(0px); }
}
.animate-blob { animation: blob 10s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
.animate-logo {
  background-size: 200% auto;
  animation: shine 3s linear infinite, float 4s ease-in-out infinite;
}
</style>
