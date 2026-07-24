<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { isSystemUnlocked } from '@/store.js';
</script>

<template>
    <Head title="Kassa tarixi" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-2xl text-gray-800">Kassa tarixi</h2>
            </div>
        </template>

        <div class="py-6 h-full flex flex-col" v-if="isSystemUnlocked">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Kassa tarixi tez kunda...</h3>
                    <p class="text-gray-500">Bu yerda barcha kassa tarixi ro'yxati chiqadi.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
