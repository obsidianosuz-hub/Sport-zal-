<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    devices: Array,
    activeSessions: Array
});

const showAddModal = ref(false);
const showEditModal = ref(false);
const editingDevice = ref(null);

const form = useForm({
    name: '',
    mac_address: '',
    ip_address: '',
    device_type: 'treadmill'
});

const editForm = useForm({
    name: '',
    mac_address: '',
    ip_address: '',
    device_type: 'treadmill'
});

const openAddModal = () => {
    form.reset();
    showAddModal.value = true;
};

const openEditModal = (device) => {
    editingDevice.value = device;
    editForm.name = device.name;
    editForm.mac_address = device.mac_address;
    editForm.ip_address = device.ip_address || '';
    editForm.device_type = device.device_type;
    showEditModal.value = true;
};

const submitAdd = () => {
    form.post(route('nfc-devices.store'), {
        onSuccess: () => {
            showAddModal.value = false;
        }
    });
};

const submitEdit = () => {
    editForm.put(route('nfc-devices.update', editingDevice.value.id), {
        onSuccess: () => {
            showEditModal.value = false;
        }
    });
};

const deleteDevice = (id) => {
    if (confirm('Rostdan ham ushbu qurilmani o\'chirmoqchimisiz?')) {
        router.delete(route('nfc-devices.destroy', id));
    }
};

const formatTime = (started_at) => {
    const start = new Date(started_at);
    const now = new Date();
    const diff = Math.floor((now - start) / 1000 / 60); // minutes
    return `${diff} daqiqa`;
};

let timer = null;
const currentTime = ref(Date.now());

onMounted(() => {
    timer = setInterval(() => {
        currentTime.value = Date.now();
        // Optional: refresh data automatically every 30 seconds
        router.reload({ only: ['activeSessions', 'devices'] });
    }, 30000); // update timer every 30 seconds
});

onUnmounted(() => {
    clearInterval(timer);
});
</script>

<template>
    <Head title="NFC va Monitoring" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white tracking-tight">NFC Qurilmalari va Jonli Monitoring</h2>
                <button @click="openAddModal" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-medium shadow-lg hover:shadow-xl transition-all">
                    + Yangi Qurilma
                </button>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Live Monitoring -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-white mb-4">Jonli Monitoring (Faol Mashg'ulotlar)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="session in activeSessions" :key="session.id" 
                             class="backdrop-blur-xl bg-white/5 border border-green-500/30 rounded-2xl p-5 relative overflow-hidden shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                            <div class="absolute top-0 left-0 w-1 h-full bg-green-500 animate-pulse"></div>
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-lg font-bold text-white">{{ session.device_name }}</h4>
                                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-lg border border-green-500/30 animate-pulse flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    FAOL
                                </span>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm border-b border-white/5 pb-2">
                                    <span class="text-gray-400">Mijoz:</span>
                                    <span class="text-white font-medium">{{ session.client_name }}</span>
                                </div>
                                <div class="flex justify-between text-sm pt-1">
                                    <span class="text-gray-400">Mashg'ulot vaqti:</span>
                                    <span class="text-green-400 font-bold" :key="currentTime">{{ formatTime(session.started_at) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Agar faol sessiyalar bo'lmasa -->
                        <div v-if="activeSessions.length === 0" class="col-span-full py-12 flex flex-col items-center justify-center backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl">
                            <svg class="w-12 h-12 text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <p class="text-gray-400 font-medium">Hozirda barcha yo'lakchalar bo'sh.</p>
                        </div>
                    </div>
                </div>

                <!-- Devices List -->
                <div>
                    <h3 class="text-xl font-bold text-white mb-4">Qurilmalar Ro'yxati</h3>
                    <div class="backdrop-blur-xl bg-white/5 border border-white/10 rounded-2xl overflow-hidden shadow-xl">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/5 text-sm uppercase tracking-wider text-gray-400">
                                    <th class="p-4 font-medium">Nomi</th>
                                    <th class="p-4 font-medium">MAC Manzil</th>
                                    <th class="p-4 font-medium text-center">Turi</th>
                                    <th class="p-4 font-medium text-center">Holat</th>
                                    <th class="p-4 font-medium text-right">So'nggi aloqa</th>
                                    <th class="p-4 font-medium text-right">Amallar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="device in devices" :key="device.id" class="hover:bg-white/5 transition-colors">
                                    <td class="p-4 text-white font-medium">{{ device.name }}</td>
                                    <td class="p-4 text-gray-300 font-mono text-sm">{{ device.mac_address }}</td>
                                    <td class="p-4 text-center">
                                        <span class="px-2 py-1 bg-white/10 text-gray-300 text-xs rounded-lg capitalize">{{ device.device_type }}</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span v-if="device.status === 'online' || device.status === 'in_use'" 
                                              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                            Onlayn
                                        </span>
                                        <span v-else class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400 border border-gray-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                            Offlayn
                                        </span>
                                    </td>
                                    <td class="p-4 text-right text-gray-400 text-sm">
                                        {{ device.last_ping_at ? new Date(device.last_ping_at).toLocaleTimeString() : 'Aloqa yo\'q' }}
                                    </td>
                                    <td class="p-4 text-right space-x-4">
                                        <button @click="openEditModal(device)" class="text-blue-400 hover:text-blue-300 font-medium transition-colors text-sm">Tahrirlash</button>
                                        <button @click="deleteDevice(device.id)" class="text-red-400 hover:text-red-300 font-medium transition-colors text-sm">O'chirish</button>
                                    </td>
                                </tr>
                                <tr v-if="devices.length === 0">
                                    <td colspan="6" class="p-12 text-center">
                                        <p class="text-gray-400 mb-2">Qurilmalar mavjud emas.</p>
                                        <p class="text-sm text-gray-500">Yuqoridagi tugma orqali birinchi NFC qurilmangizni qo'shing.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- Add Modal -->
        <Modal :show="showAddModal" @close="showAddModal = false">
            <div class="p-6 bg-[#1a1a2e] border border-white/10 rounded-2xl">
                <h3 class="text-xl font-bold text-white mb-6">Yangi Qurilma Qo'shish</h3>
                <form @submit.prevent="submitAdd" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Qurilma Nomi</label>
                        <input v-model="form.name" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2" required placeholder="Masalan: Yugurish yo'lakchasi 1">
                        <p v-if="form.errors.name" class="text-red-400 text-xs mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">MAC Manzil</label>
                        <input v-model="form.mac_address" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 font-mono text-sm uppercase" required placeholder="AA:BB:CC:DD:EE:FF">
                        <p v-if="form.errors.mac_address" class="text-red-400 text-xs mt-1">{{ form.errors.mac_address }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">IP Manzil (ixtiyoriy)</label>
                        <input v-model="form.ip_address" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 font-mono text-sm" placeholder="192.168.1.100">
                        <p v-if="form.errors.ip_address" class="text-red-400 text-xs mt-1">{{ form.errors.ip_address }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Turi</label>
                        <select v-model="form.device_type" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 [&>option]:bg-[#1a1a2e] [&>option]:text-white">
                            <option value="treadmill">Yugurish yo'lakchasi</option>
                            <option value="turnstile">Turniket</option>
                            <option value="entry">Eshik (Kirish)</option>
                        </select>
                        <p v-if="form.errors.device_type" class="text-red-400 text-xs mt-1">{{ form.errors.device_type }}</p>
                    </div>
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" @click="showAddModal = false" class="px-5 py-2 text-gray-400 hover:text-white transition-colors">Bekor qilish</button>
                        <button type="submit" :disabled="form.processing" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">Saqlash</button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Edit Modal -->
        <Modal :show="showEditModal" @close="showEditModal = false">
            <div class="p-6 bg-[#1a1a2e] border border-white/10 rounded-2xl">
                <h3 class="text-xl font-bold text-white mb-6">Qurilmani Tahrirlash</h3>
                <form @submit.prevent="submitEdit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Qurilma Nomi</label>
                        <input v-model="editForm.name" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2" required>
                        <p v-if="editForm.errors.name" class="text-red-400 text-xs mt-1">{{ editForm.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">MAC Manzil</label>
                        <input v-model="editForm.mac_address" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 font-mono text-sm uppercase" required>
                        <p v-if="editForm.errors.mac_address" class="text-red-400 text-xs mt-1">{{ editForm.errors.mac_address }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">IP Manzil</label>
                        <input v-model="editForm.ip_address" type="text" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 font-mono text-sm">
                        <p v-if="editForm.errors.ip_address" class="text-red-400 text-xs mt-1">{{ editForm.errors.ip_address }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Turi</label>
                        <select v-model="editForm.device_type" class="w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-2 [&>option]:bg-[#1a1a2e] [&>option]:text-white">
                            <option value="treadmill">Yugurish yo'lakchasi</option>
                            <option value="turnstile">Turniket</option>
                            <option value="entry">Eshik (Kirish)</option>
                        </select>
                        <p v-if="editForm.errors.device_type" class="text-red-400 text-xs mt-1">{{ editForm.errors.device_type }}</p>
                    </div>
                    <div class="pt-4 flex justify-end gap-3">
                        <button type="button" @click="showEditModal = false" class="px-5 py-2 text-gray-400 hover:text-white transition-colors">Bekor qilish</button>
                        <button type="submit" :disabled="editForm.processing" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">Yangilash</button>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
