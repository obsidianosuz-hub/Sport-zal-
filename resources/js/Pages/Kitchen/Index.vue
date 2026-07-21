<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const props = defineProps({
    sales: Array,
    products: Array,
});

const isSelling = ref(false);
const activeCategory = ref('all');

const filteredProducts = computed(() => {
    if (activeCategory.value === 'all') return props.products;
    return props.products.filter(p => p.category === activeCategory.value);
});

const cart = ref([]);

onMounted(() => {
    const savedCart = localStorage.getItem('bar_cart');
    if (savedCart) {
        try {
            cart.value = JSON.parse(savedCart);
        } catch (e) {
            localStorage.removeItem('bar_cart');
        }
    }
});

watch(cart, (newCart) => {
    localStorage.setItem('bar_cart', JSON.stringify(newCart));
}, { deep: true });

const addToCart = (product) => {
    const existingItem = cart.value.find(item => item.product_id === product.id);
    if (existingItem) {
        if (existingItem.quantity < product.stock) {
            existingItem.quantity++;
        }
    } else {
        if (product.stock > 0) {
            cart.value.push({
                product_id: product.id,
                name: product.name,
                price: product.price,
                stock: product.stock,
                quantity: 1
            });
        }
    }
};

const removeFromCart = (index) => {
    cart.value.splice(index, 1);
};

const totalCartPrice = computed(() => {
    return cart.value.reduce((total, item) => total + (item.price * item.quantity), 0);
});

const checkout = () => {
    if (cart.value.length === 0 || isSelling.value) return;
    isSelling.value = true;
    
    // faqat kerakli fieldlarni yuborish
    const items = cart.value.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity
    }));

    router.post(route('kitchen.store'), { items }, {
        preserveScroll: true,
        onFinish: () => {
            isSelling.value = false;
        },
        onSuccess: () => {
            cart.value = [];
            localStorage.removeItem('bar_cart');
        }
    });
};

const deleteProduct = (id) => {
    if (confirm('Rostdan ham bu mahsulotni o\'chirmoqchimisiz?')) {
        router.delete(route('inventory.destroy', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="BAR" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white tracking-tight">BAR va Savdo</h2>
            </div>
        </template>

        <template #actions>
            <Link :href="route('inventory.index')" class="hidden sm:flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-400 hover:to-green-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all border border-emerald-400/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Maxsulot qo'shish
            </Link>
        </template>

        <!-- Fast Access Links or Info -->
        <div class="mt-6 flex flex-wrap gap-4">
            <Link :href="route('sales.index')" class="flex items-center gap-2 px-6 py-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-colors backdrop-blur-xl">
                <div class="p-3 bg-blue-500/20 text-blue-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg">Sotuvlar Tarixini Ko'rish</h3>
                    <p class="text-gray-400 text-sm">Barcha savdolarni alohida sahifada ko'ring</p>
                </div>
            </Link>
        </div>

        <!-- Products and Cart Section -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Left Side: Products Grid (lg:col-span-3) -->
            <div class="lg:col-span-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h3 class="text-xl font-bold text-white">Sotuvdagi Mahsulotlar</h3>
                    
                    <!-- Category Filters -->
                    <div class="flex overflow-x-auto gap-2 pb-2 sm:pb-0 hide-scrollbar">
                        <button @click="activeCategory = 'all'" :class="activeCategory === 'all' ? 'bg-white/20 text-white font-bold' : 'bg-white/5 text-gray-400 hover:bg-white/10'" class="px-4 py-2 rounded-xl text-sm whitespace-nowrap transition-colors border border-white/10">Barcha</button>
                        <button @click="activeCategory = 'protein'" :class="activeCategory === 'protein' ? 'bg-white/20 text-white font-bold' : 'bg-white/5 text-gray-400 hover:bg-white/10'" class="px-4 py-2 rounded-xl text-sm whitespace-nowrap transition-colors border border-white/10">Protein</button>
                        <button @click="activeCategory = 'energy'" :class="activeCategory === 'energy' ? 'bg-white/20 text-white font-bold' : 'bg-white/5 text-gray-400 hover:bg-white/10'" class="px-4 py-2 rounded-xl text-sm whitespace-nowrap transition-colors border border-white/10">Energetik</button>
                        <button @click="activeCategory = 'water'" :class="activeCategory === 'water' ? 'bg-white/20 text-white font-bold' : 'bg-white/5 text-gray-400 hover:bg-white/10'" class="px-4 py-2 rounded-xl text-sm whitespace-nowrap transition-colors border border-white/10">Suv</button>
                        <button @click="activeCategory = 'diet_food'" :class="activeCategory === 'diet_food' ? 'bg-white/20 text-white font-bold' : 'bg-white/5 text-gray-400 hover:bg-white/10'" class="px-4 py-2 rounded-xl text-sm whitespace-nowrap transition-colors border border-white/10">Parhez Taomlar</button>
                    </div>
                </div>
                
                <div v-if="filteredProducts.length === 0" class="p-8 text-center bg-white/5 border border-white/10 rounded-2xl">
                    <p class="text-gray-400">Hozircha sotish uchun bunday mahsulot yo'q.</p>
                </div>
                
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <button 
                        v-for="product in filteredProducts" 
                        :key="product.id"
                        @click="addToCart(product)"
                        :disabled="product.stock < 1"
                        class="text-left relative group bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-5 hover:bg-white/10 transition-all transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-full flex items-center justify-center text-green-400 mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h4 class="text-lg font-bold text-white mb-1 truncate">{{ product.name }}</h4>
                        <p class="text-gray-400 text-sm mb-4">Omborda: <span :class="product.stock > 0 ? 'text-white' : 'text-red-400'" class="font-bold">{{ product.stock }} dona</span></p>
                        <div class="flex justify-between items-center mt-4 pt-4 border-t border-white/10">
                            <span class="text-green-400 font-bold">{{ Number(product.price).toLocaleString() }} so'm</span>
                        </div>
                        
                        <!-- Actions on hover -->
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-2">
                            <div @click.stop.prevent="deleteProduct(product.id)" class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white p-1.5 rounded-lg transition-colors cursor-pointer" title="O'chirish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <div v-if="product.stock > 0" class="bg-green-500/20 text-green-400 p-1.5 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Right Side: Cart Panel (lg:col-span-1) -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="sticky top-6 backdrop-blur-xl bg-gray-900 border border-green-500/30 rounded-2xl p-6 shadow-2xl flex flex-col h-[calc(100vh-120px)]">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white">Savat</h3>
                        </div>
                        <span v-if="cart.length > 0" class="bg-green-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ cart.length }} ta</span>
                    </div>

                    <!-- Cart Items -->
                    <div class="flex-1 overflow-y-auto pr-2 space-y-4 hide-scrollbar">
                        <div v-if="cart.length === 0" class="flex flex-col items-center justify-center h-full text-center">
                            <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <p class="text-gray-400">Savat bo'sh.<br><span class="text-sm">Chaptagi mahsulotlardan birini bosib qo'shing.</span></p>
                        </div>
                        
                        <div v-else v-for="(item, index) in cart" :key="index" class="bg-white/5 border border-white/10 rounded-xl p-3 relative group">
                            <button @click="removeFromCart(index)" class="absolute -top-2 -right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600 shadow-lg">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            
                            <h5 class="text-white font-bold text-sm mb-1 truncate pr-4">{{ item.name }}</h5>
                            <p class="text-gray-400 text-xs mb-3">{{ Number(item.price).toLocaleString() }} so'm / dona</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center bg-black/40 rounded-lg border border-white/10">
                                    <button @click="item.quantity > 1 ? item.quantity-- : removeFromCart(index)" class="px-2 py-1 text-gray-400 hover:text-white transition-colors">-</button>
                                    <span class="px-2 py-1 text-white text-sm font-bold w-8 text-center">{{ item.quantity }}</span>
                                    <button @click="item.quantity < item.stock ? item.quantity++ : null" :disabled="item.quantity >= item.stock" class="px-2 py-1 text-gray-400 hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed">+</button>
                                </div>
                                <span class="text-green-400 font-bold text-sm">{{ Number(item.price * item.quantity).toLocaleString() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Footer -->
                    <div class="pt-4 mt-4 border-t border-white/10">
                        <div class="flex justify-between items-end mb-4">
                            <span class="text-gray-400 text-sm">Jami to'lov:</span>
                            <span class="text-white font-black text-xl">{{ Number(totalCartPrice).toLocaleString() }} <span class="text-sm font-normal text-gray-400">so'm</span></span>
                        </div>
                        
                        <button 
                            @click="checkout" 
                            :disabled="cart.length === 0 || isSelling"
                            class="w-full py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-bold rounded-xl transition-all shadow-[0_0_15px_rgba(16,185,129,0.3)] disabled:opacity-50 flex items-center justify-center gap-2"
                        >
                            <svg v-if="!isSelling" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <svg v-else class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            {{ isSelling ? 'Sotilmoqda...' : 'Sotishni Tasdiqlash' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
