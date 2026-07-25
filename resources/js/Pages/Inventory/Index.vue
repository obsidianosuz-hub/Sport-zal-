<script setup>
import { ref, watch, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    inventory: Array,
});

const page = usePage();
const activeTab = ref('history'); // 'history' | 'stock'
const showModal = ref(false);
const supplierPanel = ref(null);

const checkUrlForAdd = () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('add')) {
        showModal.value = true;
        // Clean URL to prevent reopening on reload if unwanted
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
};

watch(() => page.url, () => {
    checkUrlForAdd();
});

onMounted(() => {
    checkUrlForAdd();
});

const form = useForm({
    name: '',
    category: 'protein',
    buy_price: '',
    price: '',
    stock: '',
});

const formReplenish = useForm({
    product_id: '',
    quantity: '',
    total_cost: '',
});

watch(() => [formReplenish.product_id, formReplenish.quantity], ([newProductId, newQuantity]) => {
    if (newProductId && newQuantity) {
        const product = props.inventory.find(p => p.id == newProductId);
        if (product && product.buy_price) {
            formReplenish.total_cost = Number(product.buy_price) * Number(newQuantity);
        } else {
            formReplenish.total_cost = 0;
        }
    } else {
        formReplenish.total_cost = '';
    }
});

const submit = () => {
    form.post(route('inventory.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
    });
};

const submitReplenish = () => {
    formReplenish.post(route('inventory.replenish'), {
        onSuccess: () => {
            formReplenish.reset();
        },
    });
};

const deleteAll = () => {
    if(confirm('Barcha mahsulotlarni o\'chirishga ishonchingiz komilmi?')) {
        router.delete(route('inventory.destroyAll'));
    }
};

const deleteItem = (id) => {
    if(confirm('Shu mahsulotni o\'chirishni xohlaysizmi?')) {
        router.delete(route('inventory.destroy', id));
    }
};
</script>

<template>
    <Head title="Ombor" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h2 class="text-2xl font-bold text-white tracking-tight">Ombor va Mahsulotlar Kirimi</h2>
                <div class="flex items-center gap-3">
                    <button @click="showModal = true" class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-bold rounded-xl shadow-[0_0_10px_rgba(16,185,129,0.3)] transition-all transform hover:-translate-y-0.5">
                        + Yangi Mahsulot
                    </button>
                </div>
            </div>
        </template>

        <!-- Grid Layout for Tables and Supplier Panel -->
        <div class="mt-6 grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Glass Panel for Table (Left Side) -->
            <div class="relative group lg:col-span-3">
                <div class="absolute inset-0 bg-gradient-to-r from-green-600/20 to-emerald-600/20 rounded-2xl blur-xl"></div>
                <div class="relative backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
                    <!-- Tabs -->
                    <div class="p-6 border-b border-white/10 flex justify-between items-center flex-wrap gap-4">
                        <div class="flex gap-6">
                            <button @click="activeTab = 'history'"
                                :class="activeTab === 'history' ? 'text-black font-extrabold border-b-4 border-green-500' : 'text-gray-600 hover:text-black font-medium'"
                                class="pb-1 transition-colors text-lg">Kirimlar Tarixi</button>
                            <button @click="activeTab = 'stock'"
                                :class="activeTab === 'stock' ? 'text-black font-extrabold border-b-4 border-green-500' : 'text-gray-600 hover:text-black font-medium'"
                                class="pb-1 transition-colors text-lg">Ombordagi Qoldiqlar</button>
                        </div>
                        
                        <button v-if="inventory.length > 0" @click="deleteAll" class="px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 rounded-xl text-sm font-bold transition-all">
                            Hammasini O'chirish
                        </button>
                    </div>

                    <!-- Kirimlar Tarixi Table -->
                    <div v-if="activeTab === 'history'" class="overflow-x-auto">
                        <table class="w-full text-left text-gray-300">
                            <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                                <tr>
                                    <th class="px-6 py-4">Mahsulot</th>
                                    <th class="px-6 py-4">Turkum</th>
                                    <th class="px-6 py-4">Sotish Narxi</th>
                                    <th class="px-6 py-4">Boshlang'ich Qoldiq</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-if="inventory.length === 0">
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Hozircha omborda mahsulot yo'q</td>
                                </tr>
                                <tr v-for="product in inventory" :key="product.id" class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 font-bold text-white">{{ product.name }}</td>
                                    <td class="px-6 py-4 text-gray-400 capitalize">{{ product.category }}</td>
                                    <td class="px-6 py-4 font-mono text-sm text-blue-400">{{ Number(product.price).toLocaleString() }} so'm</td>
                                    <td class="px-6 py-4 font-bold text-green-400">{{ product.stock }} dona</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ombordagi Qoldiqlar Table -->
                    <div v-if="activeTab === 'stock'" class="overflow-x-auto">
                        <table class="w-full text-left text-gray-300">
                            <thead class="bg-white/5 border-b border-white/10 text-xs uppercase font-bold text-gray-400">
                                <tr>
                                    <th class="px-6 py-4">Mahsulot</th>
                                    <th class="px-6 py-4">Turkum</th>
                                    <th class="px-6 py-4">Sotish Narxi</th>
                                    <th class="px-6 py-4">Qoldiq</th>
                                    <th class="px-6 py-4">Holat</th>
                                    <th class="px-6 py-4 text-right">Amal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-if="inventory.length === 0">
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Ombor bo'sh</td>
                                </tr>
                                <tr v-for="product in inventory" :key="'stock-' + product.id" class="hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4 font-bold text-white">{{ product.name }}</td>
                                    <td class="px-6 py-4 text-gray-400 capitalize">{{ product.category }}</td>
                                    <td class="px-6 py-4 font-mono text-sm text-blue-400">{{ Number(product.price).toLocaleString() }} so'm</td>
                                    <td class="px-6 py-4">
                                        <span :class="product.stock > 5 ? 'text-green-400' : product.stock > 0 ? 'text-yellow-400' : 'text-red-400'" class="font-bold text-lg">{{ product.stock }}</span>
                                        <span class="text-gray-500 text-sm ml-1">dona</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span v-if="product.stock > 5" class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold border border-green-500/30">Yetarli</span>
                                        <span v-else-if="product.stock > 0" class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-bold border border-yellow-500/30">Kam qoldi</span>
                                        <span v-else class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-bold border border-red-500/30">Tugagan</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="deleteItem(product.id)" class="px-3 py-1 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-xs font-bold transition-all">
                                            O'chirish
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Supplier / Replenish Panel (Right Side) -->
            <div class="relative group lg:col-span-1" id="supplier-panel" ref="supplierPanel">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 to-blue-600/20 rounded-2xl blur-xl"></div>
                <div class="relative backdrop-blur-xl bg-gray-900 border border-indigo-500/30 rounded-2xl p-6 shadow-2xl transition-all duration-500">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Ta'minotchi</h3>
                    </div>
                    <p class="text-sm text-gray-400 mb-6">Mahsulot qolmaganda shu yerdan sotib oling. Xarajatlar sof foydadan ayiriladi.</p>

                    <form @submit.prevent="submitReplenish" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Mahsulotni tanlang</label>
                            <select v-model="formReplenish.product_id" class="w-full bg-black/40 border border-indigo-500/20 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50" required>
                                <option value="" disabled hidden>— Tanlang —</option>
                                <option v-for="product in inventory" :key="'rep-' + product.id" :value="product.id">
                                    {{ product.name }} (Qoldiq: {{ product.stock }})
                                </option>
                            </select>
                            <p v-if="formReplenish.errors.product_id" class="text-red-400 text-xs mt-1">{{ formReplenish.errors.product_id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Soni (nechta olyapsiz?)</label>
                            <input v-model="formReplenish.quantity" type="number" min="1" class="w-full bg-black/40 border border-indigo-500/20 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50" required>
                            <p v-if="formReplenish.errors.quantity" class="text-red-400 text-xs mt-1">{{ formReplenish.errors.quantity }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Jami Xarajat (so'm)</label>
                            <input v-model="formReplenish.total_cost" type="number" min="0" class="w-full bg-black/40 border border-indigo-500/20 rounded-xl text-white px-4 py-2.5 focus:ring-2 focus:ring-indigo-500/50" required>
                            <p v-if="formReplenish.errors.total_cost" class="text-red-400 text-xs mt-1">{{ formReplenish.errors.total_cost }}</p>
                        </div>

                        <div class="pt-4 border-t border-white/10">
                            <button type="submit" :disabled="formReplenish.processing || !formReplenish.product_id" class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-xl hover:from-indigo-500 hover:to-blue-500 shadow-[0_0_15px_rgba(79,70,229,0.4)] disabled:opacity-50 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Sotib Olish
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Add Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative bg-gray-900 border border-white/10 rounded-2xl w-full max-w-lg p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-white mb-6">Yangi Mahsulot Kirim Qilish</h3>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Mahsulot Nomi</label>
                        <input v-model="form.name" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-purple-500/50" required>
                        <p v-if="form.errors.name" class="text-red-400 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Turkum</label>
                        <select v-model="form.category" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-purple-500/50">
                            <option value="protein">Protein</option>
                            <option value="energy">Energetik</option>
                            <option value="water">Suv</option>
                            <option value="diet_food">Diet Taom</option>
                        </select>
                        <p v-if="form.errors.category" class="text-red-400 text-xs mt-1">{{ form.errors.category }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Kirim Narxi</label>
                            <input v-model="form.buy_price" type="number" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-purple-500/50" required>
                            <p v-if="form.errors.buy_price" class="text-red-400 text-xs mt-1">{{ form.errors.buy_price }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Sotish Narxi</label>
                            <input v-model="form.price" type="number" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-purple-500/50" required>
                            <p v-if="form.errors.price" class="text-red-400 text-xs mt-1">{{ form.errors.price }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Soni (dona)</label>
                        <input v-model="form.stock" type="number" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 focus:ring-2 focus:ring-purple-500/50" required>
                        <p v-if="form.errors.stock" class="text-red-400 text-xs mt-1">{{ form.errors.stock }}</p>
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-white/10">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">Bekor qilish</button>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-500 hover:to-purple-500 active:scale-95 active:from-emerald-500 active:to-emerald-600 active:shadow-[0_0_15px_rgba(16,185,129,0.5)] disabled:opacity-50">
                            Saqlash
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
