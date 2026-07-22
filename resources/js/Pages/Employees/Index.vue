<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

defineProps({
    employees: Array,
});

const showModal = ref(false);
const editingEmployee = ref(null);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    password: '',
    role: 'trainer',
    pin_code: '',
});

const openAddModal = () => {
    editingEmployee.value = null;
    form.reset();
    form.clearErrors();
    showModal.value = true;
};

const openEditModal = (employee) => {
    editingEmployee.value = employee;
    form.name = employee.name;
    form.email = employee.email;
    form.phone = employee.phone || '';
    form.password = '';
    form.role = employee.roles?.[0]?.name || 'trainer';
    form.pin_code = employee.pin_code || '';
    form.clearErrors();
    showModal.value = true;
};

const submit = () => {
    if (editingEmployee.value) {
        form.put(route('employees.update', editingEmployee.value.id), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(route('employees.store'), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
};

const deleteEmployee = (id) => {
    if (confirm("Haqiqatan ham bu xodimni o'chirmoqchimisiz?")) {
        router.delete(route('employees.destroy', id));
    }
};

const availableModules = [
    { name: 'Umumiy Statistika', route: 'dashboard' },
    { name: 'Xodimlar Boshqaruvi', route: 'employees.index' },
    { name: 'Xodimlar Oyligi', route: 'salaries.index' },
    { name: 'Mijozlar Bazasi', route: 'clients.index' },
    { name: 'Fitnes Bar', route: 'kitchen.index' },
    { name: 'Sotuvlar Tarixi', route: 'sales.index' },
    { name: 'Omborxona', route: 'inventory.index' },
    { name: 'Sozlamalar', route: 'settings.index' },
];

const selectedEmployee = ref(null);

const selectEmployee = (employee) => {
    selectedEmployee.value = employee;
};

const hasPermission = (routeName) => {
    if(!selectedEmployee.value || !selectedEmployee.value.permissions) return false;
    return selectedEmployee.value.permissions.some(p => p.name === routeName);
};

const activeRole = typeof window !== 'undefined' ? (localStorage.getItem('activeRoleFilter') || '') : '';
const canEditPermissions = activeRole === '' || activeRole === 'admin';

const togglePermission = (routeName) => {
    if (!canEditPermissions) {
        alert("Ruxsatlarni o'zgartirish huquqi faqat Asosiy Adminga berilgan!");
        return;
    }
    if(selectedEmployee.value.roles?.[0]?.name === 'admin') return;

    let currentPermissions = selectedEmployee.value.permissions ? selectedEmployee.value.permissions.map(p => p.name) : [];
    
    if(currentPermissions.includes(routeName)) {
        currentPermissions = currentPermissions.filter(p => p !== routeName);
    } else {
        currentPermissions.push(routeName);
    }

    // Optimistic update
    selectedEmployee.value.permissions = currentPermissions.map(name => ({name}));

    router.post(route('employees.permissions', selectedEmployee.value.id), {
        permissions: currentPermissions
    }, { preserveScroll: true, preserveState: true });
};
</script>

<template>
    <Head title="Xodimlar" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="text-2xl font-bold text-white tracking-tight">Xodimlar Boshqaruvi</h2>
                <button v-if="canEditPermissions" @click="openAddModal" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)] text-white font-bold rounded-xl shadow-[0_0_15px_rgba(139,92,246,0.5)] transition-all transform hover:-translate-y-0.5">
                    + Yangi Xodim
                </button>
            </div>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-6">
            <!-- Left Side: Employee List -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Glass Panel for Table -->
                <div class="relative group">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-2xl blur-xl"></div>
                    <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                        <!-- Search and Filter -->
                        <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row justify-between gap-4">
                            <div class="relative w-full sm:w-96">
                                <input type="text" placeholder="Xodimlarni qidirish..." class="w-full pl-10 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all">
                                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-gray-300">
                                <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                                    <tr>
                                        <th class="px-6 py-4">F.I.O</th>
                                        <th class="px-6 py-4">Lavozim</th>
                                        <th class="px-6 py-4">Telefon</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Amallar</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    <tr v-if="employees.length === 0">
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Hozircha xodimlar mavjud emas</td>
                                    </tr>
                                    <tr v-for="employee in employees" :key="employee.id" 
                                        @click="selectEmployee(employee)"
                                        class="hover:bg-white/5 transition-colors cursor-pointer"
                                        :class="{'bg-white/10': selectedEmployee?.id === employee.id}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white shadow-lg">{{ employee.name.charAt(0) }}</div>
                                                <div>
                                                    <p class="font-bold text-white">{{ employee.name }}</p>
                                                    <p class="text-xs text-gray-500">{{ employee.email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-bold border border-blue-500/30">
                                                {{ employee.roles?.[0]?.name ?? 'Xodim' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-sm">{{ employee.phone ?? 'Kiritilmagan' }}</td>
                                        <td class="px-6 py-4">
                                            <span class="flex items-center gap-2 text-green-400 text-sm font-bold">
                                                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> Faol
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button v-if="canEditPermissions" @click.stop="openEditModal(employee)" class="text-blue-400 hover:text-blue-300 mx-2 font-medium transition-colors">Tahrirlash</button>
                                            <button v-if="canEditPermissions" @click.stop="deleteEmployee(employee.id)" class="text-red-400 hover:text-red-300 mx-2 font-medium transition-colors">O'chirish</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="p-4 border-t border-white/10 flex justify-between items-center text-sm text-gray-400">
                            <p>Jami: {{ employees.length }} ta xodim</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Permissions Panel -->
            <div class="lg:col-span-1">
                <div class="sticky top-6">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/20 to-blue-600/20 rounded-2xl blur-xl transition-all duration-300"></div>
                        <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden p-6">
                            <h3 class="text-xl font-bold text-white mb-2">Bo'limlarga Ruxsatlar</h3>
                            
                            <div v-if="!selectedEmployee" class="flex flex-col items-center justify-center py-8 text-center">
                                <svg class="w-12 h-12 text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                <p class="text-sm text-gray-400">Huquqlarini sozlash uchun chap tomondan xodimni tanlang.</p>
                            </div>
                            
                            <div v-else>
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white">{{ selectedEmployee.name.charAt(0) }}</div>
                                    <div>
                                        <p class="text-sm text-white font-bold">{{ selectedEmployee.name }}</p>
                                        <p class="text-xs text-blue-400">{{ selectedEmployee.roles?.[0]?.name ?? 'Xodim' }}</p>
                                    </div>
                                </div>
                                
                                <div v-if="selectedEmployee.roles?.[0]?.name === 'admin'" class="p-4 rounded-xl bg-blue-500/20 border border-blue-500/30 text-blue-300 text-sm mb-4">
                                    Admin barcha bo'limlarga kirish huquqiga ega. Ularni cheklash imkonsiz.
                                </div>

                                <div class="space-y-3">
                                    <div v-for="module in availableModules" :key="module.route" class="flex items-center justify-between p-3 rounded-xl bg-black/20 border border-white/5 hover:bg-white/5 transition-colors">
                                        <span class="text-gray-300 text-sm font-medium">{{ module.name }}</span>
                                        <button 
                                            @click="togglePermission(module.route)"
                                            :disabled="!canEditPermissions || selectedEmployee.roles?.[0]?.name === 'admin'"
                                            class="w-11 h-6 rounded-full transition-colors relative focus:outline-none"
                                            :class="(hasPermission(module.route) || selectedEmployee.roles?.[0]?.name === 'admin') ? (canEditPermissions ? 'bg-green-500' : 'bg-green-500/50 cursor-not-allowed') : (canEditPermissions ? 'bg-gray-600' : 'bg-gray-700/50 cursor-not-allowed')"
                                        >
                                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-transform"
                                                 :class="(hasPermission(module.route) || selectedEmployee.roles?.[0]?.name === 'admin') ? 'translate-x-6' : 'translate-x-1'"></div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Employee Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-gray-900 border border-white/10 rounded-2xl w-full max-w-lg p-6 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">{{ editingEmployee ? 'Xodimni Tahrirlash' : 'Yangi Xodim Qo\'shish' }}</h3>
                    <button @click="showModal = false" class="text-gray-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Ism -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Ism va Familiya *</label>
                        <input v-model="form.name" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500/50 outline-none" required>
                        <p v-if="form.errors.name" class="text-red-400 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Email *</label>
                        <input v-model="form.email" type="email" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500/50 outline-none" required>
                        <p v-if="form.errors.email" class="text-red-400 text-xs mt-1">{{ form.errors.email }}</p>
                    </div>

                    <!-- Telefon & Rol -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Telefon</label>
                            <input v-model="form.phone" type="tel" placeholder="+998 90 000 00 00" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500/50 outline-none">
                            <p v-if="form.errors.phone" class="text-red-400 text-xs mt-1">{{ form.errors.phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Lavozim (Rol) *</label>
                            <select v-model="form.role" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500/50 outline-none">
                                <option value="trainer">Treyner</option>
                                <option value="manager">Menejer</option>
                                <option value="admin">Admin</option>
                                <option value="cook">Oshpaz</option>
                            </select>
                            <p v-if="form.errors.role" class="text-red-400 text-xs mt-1">{{ form.errors.role }}</p>
                        </div>
                    </div>

                    <!-- Parol & PIN -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Parol <span v-if="editingEmployee" class="text-xs font-normal text-gray-500">(o'zgartirmasangiz bo'sh qoldiring)</span></label>
                            <input v-model="form.password" type="password" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-blue-500/50 outline-none" :required="!editingEmployee">
                            <p v-if="form.errors.password" class="text-red-400 text-xs mt-1">{{ form.errors.password }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">PIN Kod (4 raqam)</label>
                            <input v-model="form.pin_code" type="text" maxlength="4" placeholder="****" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 text-center tracking-[0.5em] text-lg focus:ring-2 focus:ring-purple-500/50 outline-none">
                            <p v-if="form.errors.pin_code" class="text-red-400 text-xs mt-1">{{ form.errors.pin_code }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-white/10">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">Bekor qilish</button>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-500 hover:to-purple-500 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)] disabled:opacity-50 transition-all">
                            <span v-if="form.processing">Saqlanmoqda...</span>
                            <span v-else>{{ editingEmployee ? 'Yangilash' : 'Xodimni Qo\'shish' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
