<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    salaries: Array,
    employees: Array,
});

const page = usePage();
const currentUser = page.props.auth.user;

const availableEmployees = computed(() => {
    if (currentUser.roles?.includes('manager')) {
        return props.employees.filter(emp => emp.id !== currentUser.id);
    }
    return props.employees;
});

const showModal = ref(false);
const editingSalary = ref(null);

const form = useForm({
    user_id: '',
    amount: '',
    month: new Date().toISOString().slice(0, 7), // joriy yil-oy (Masalan: 2026-07)
    payment_date: new Date().toISOString().slice(0, 10), // bugungi sana
    note: '',
});

const openAddModal = () => {
    editingSalary.value = null;
    form.reset();
    form.month = new Date().toISOString().slice(0, 7);
    form.payment_date = new Date().toISOString().slice(0, 10);
    form.clearErrors();
    showModal.value = true;
};

const payFullSalary = (emp) => {
    if (!emp.salary || emp.salary <= 0) {
        alert("Bu xodim uchun oylik stavka belgilanmagan!");
        return;
    }
    
    if (confirm(`${emp.name} ga ${formatCurrency(emp.salary)} miqdorida maosh to'lashni tasdiqlaysizmi?`)) {
        router.post(route('salaries.store'), {
            user_id: emp.id,
            amount: emp.salary,
            month: new Date().toISOString().slice(0, 7),
            payment_date: new Date().toISOString().slice(0, 10),
            note: 'Asosiy oylik stavka bo\'yicha to\'lov'
        }, {
            preserveScroll: true
        });
    }
};

const openEditModal = (salary) => {
    editingSalary.value = salary;
    form.user_id = salary.user_id;
    form.amount = salary.amount;
    form.month = salary.month;
    form.payment_date = salary.payment_date;
    form.note = salary.note || '';
    form.clearErrors();
    showModal.value = true;
};

const submit = () => {
    if (editingSalary.value) {
        form.put(route('salaries.update', editingSalary.value.id), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    } else {
        form.post(route('salaries.store'), {
            onSuccess: () => {
                showModal.value = false;
                form.reset();
            },
        });
    }
};

const deleteSalary = (id) => {
    if (confirm("Haqiqatan ham bu to'lovni o'chirmoqchimisiz?")) {
        router.delete(route('salaries.destroy', id));
    }
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('uz-UZ', { style: 'currency', currency: 'UZS', maximumFractionDigits: 0 }).format(value);
};

const editingEmployeeSalary = ref(null);
const salaryForm = useForm({
    salary: ''
});

const startEditingSalary = (emp) => {
    editingEmployeeSalary.value = emp.id;
    salaryForm.salary = emp.salary;
};

const saveSalary = (emp) => {
    salaryForm.post(route('salaries.employee.salary', emp.id), {
        preserveScroll: true,
        onSuccess: () => {
            editingEmployeeSalary.value = null;
        }
    });
};

const toggleActive = (emp) => {
    router.post(route('salaries.employee.toggle-active', emp.id), {}, { preserveScroll: true });
};
</script>

<template>
    <Head title="Xodimlar Oyligi" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h2 class="text-2xl font-bold text-white tracking-tight">Xodimlar Oyligi</h2>
                <button @click="openAddModal" class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Oylik To'lash
                </button>
            </div>
        </template>

        <!-- Grid Layout -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Left Side: Table -->
            <div class="lg:col-span-3">
                <!-- Glass Panel for Table -->
                <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/20 to-teal-600/20 rounded-2xl blur-xl"></div>
            <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                <!-- Search and Filter (Optional future use) -->
                <div class="p-6 border-b border-white/10 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-96">
                        <input type="text" placeholder="Qidirish..." class="w-full pl-10 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-300">
                        <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                            <tr>
                                <th class="px-6 py-4">Xodim</th>
                                <th class="px-6 py-4">To'lov Summasi</th>
                                <th class="px-6 py-4">Qaysi Oy Uchun</th>
                                <th class="px-6 py-4">To'lov Sanasi</th>
                                <th class="px-6 py-4">Izoh</th>
                                <th class="px-6 py-4 text-right">Amallar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-if="salaries.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">Hozircha oylik to'lovlari kiritilmagan</td>
                            </tr>
                            <tr v-for="salary in salaries" :key="salary.id" class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white">{{ salary.user?.name || 'O\'chirilgan xodim' }}</div>
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-emerald-400">
                                    {{ formatCurrency(salary.amount) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-white/10 text-white rounded-lg text-sm">{{ salary.month }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm">{{ salary.payment_date }}</td>
                                <td class="px-6 py-4 text-sm text-gray-400">{{ salary.note || '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="openEditModal(salary)" class="text-blue-400 hover:text-blue-300 mx-2 font-medium transition-colors">Tahrirlash</button>
                                    <button @click="deleteSalary(salary.id)" class="text-red-400 hover:text-red-300 mx-2 font-medium transition-colors">O'chirish</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            </div>

            <!-- Right Side: Employees Base Salary Panel -->
            <div class="lg:col-span-1">
                <div class="relative group h-full">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-purple-600/20 rounded-2xl blur-xl"></div>
                    <div class="relative backdrop-blur-xl bg-gray-900 border border-indigo-500/30 rounded-2xl p-6 shadow-2xl h-full flex flex-col">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Xodimlar Maoshi</h3>
                        </div>

                        <div class="flex-1 overflow-y-auto space-y-4 pr-2">
                            <div v-for="emp in employees" :key="'emp-'+emp.id" class="p-4 bg-black/40 border border-white/5 rounded-xl hover:bg-white/5 transition-colors">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h4 class="font-bold text-white">{{ emp.name }}</h4>
                                        <p class="text-xs text-gray-400 capitalize">{{ emp.role }}</p>
                                    </div>
                                    
                                    <!-- Active/Inactive Toggle -->
                                    <button 
                                        v-if="!(currentUser.roles?.includes('manager') && emp.id === currentUser.id)"
                                        @click="toggleActive(emp)" 
                                        :class="emp.is_active ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30'" 
                                        class="px-2.5 py-1 text-xs font-bold rounded-lg border transition-all"
                                    >
                                        {{ emp.is_active ? 'Faol' : 'Nofaol' }}
                                    </button>
                                    <span v-else :class="emp.is_active ? 'text-emerald-400' : 'text-red-400'" class="px-2.5 py-1 text-xs font-bold">{{ emp.is_active ? 'Faol' : 'Nofaol' }}</span>
                                </div>

                                <div class="pt-3 border-t border-white/10">
                                    <p class="text-xs text-gray-500 mb-1">Oylik Stavka:</p>
                                    
                                    <div v-if="editingEmployeeSalary === emp.id" class="flex gap-2">
                                        <input v-model="salaryForm.salary" type="number" class="w-full bg-black/60 border border-indigo-500/30 rounded-lg text-white px-2 py-1 text-sm focus:ring-1 focus:ring-indigo-500" placeholder="Summa...">
                                        <button @click="saveSalary(emp)" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg text-sm transition-colors">Saqlash</button>
                                    </div>
                                    <div v-else class="flex justify-between items-center group/edit">
                                        <span class="font-mono text-indigo-400 font-bold">{{ formatCurrency(emp.salary) }}</span>
                                        <button 
                                            v-if="!(currentUser.roles?.includes('manager') && emp.id === currentUser.id)"
                                            @click="startEditingSalary(emp)" 
                                            class="opacity-0 group-hover/edit:opacity-100 p-1 text-gray-400 hover:text-white transition-all"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="pt-3 mt-3 border-t border-white/10">
                                    <div class="flex justify-between items-center text-sm mb-3">
                                        <span class="text-gray-500">Shu oy to'landi:</span>
                                        <span class="font-bold text-emerald-400">{{ formatCurrency(emp.paid_this_month) }}</span>
                                    </div>
                                    <button 
                                        v-if="!(currentUser.roles?.includes('manager') && emp.id === currentUser.id)"
                                        @click="payFullSalary(emp)" 
                                        class="w-full py-2 bg-gradient-to-r from-emerald-600/20 to-teal-600/20 hover:from-emerald-500/30 hover:to-teal-500/30 text-emerald-400 text-sm font-bold rounded-lg border border-emerald-500/30 transition-all flex justify-center items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        To'lash
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-gray-900 border border-white/10 rounded-2xl w-full max-w-lg p-6 shadow-2xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white">{{ editingSalary ? 'To\'lovni Tahrirlash' : 'Oylik To\'lash' }}</h3>
                    <button @click="showModal = false" class="text-gray-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Xodim -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Xodimni Tanlang *</label>
                        <select v-model="form.user_id" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/50 outline-none" required>
                            <option value="" disabled>Tanlang...</option>
                            <option v-for="emp in availableEmployees" :key="emp.id" :value="emp.id">
                                {{ emp.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.user_id" class="text-red-400 text-xs mt-1">{{ form.errors.user_id }}</p>
                    </div>

                    <!-- Summa -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">To'lov Summasi (UZS) *</label>
                        <input v-model="form.amount" type="number" step="1000" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/50 outline-none" required>
                        <p v-if="form.errors.amount" class="text-red-400 text-xs mt-1">{{ form.errors.amount }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Oy -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Qaysi oy uchun? *</label>
                            <input v-model="form.month" type="month" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/50 outline-none" required>
                            <p v-if="form.errors.month" class="text-red-400 text-xs mt-1">{{ form.errors.month }}</p>
                        </div>

                        <!-- Sana -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">To'lov Sanasi *</label>
                            <input v-model="form.payment_date" type="date" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/50 outline-none" required>
                            <p v-if="form.errors.payment_date" class="text-red-400 text-xs mt-1">{{ form.errors.payment_date }}</p>
                        </div>
                    </div>

                    <!-- Izoh -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Qo'shimcha Izoh (Ixtiyoriy)</label>
                        <textarea v-model="form.note" rows="2" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-emerald-500/50 outline-none"></textarea>
                        <p v-if="form.errors.note" class="text-red-400 text-xs mt-1">{{ form.errors.note }}</p>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-white/10">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">Bekor qilish</button>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-500 hover:to-teal-500 disabled:opacity-50 transition-all">
                            <span v-if="form.processing">Saqlanmoqda...</span>
                            <span v-else>Saqlash</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
