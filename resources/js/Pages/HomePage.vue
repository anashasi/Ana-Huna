<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import {ref} from 'vue';

const LLM=ref(false);

function translationPage(){
    router.visit('signtotext', {
        data: {
            LLM: Boolean(false)
        }
    });
}

function goToLLmPage() {
    router.visit('signtotext', {
        data: {
            LLM: Boolean(true)
        }
    });
}
</script>
<template>
    <AppLayout />
    <div class="min-h-screen flex flex-col items-center justify-center text-center p-6 " >
        <h1 class="text-4xl font-bold text-white mb-2">أهلًا بك في أنا هنا</h1>
        <p class="text-lg text-white mb-6">.منصة لفهم لغة الإشارة وتحويلها إلى نصوص وتواصل سهل</p>
        <div class="flex flex-wrap gap-6 justify-center mt-14">
            <div @click="goToLLmPage()" class="w-72 h-50 bg-gray-100 text-gray-900 rounded-2xl shadow-lg flex flex-col items-center justify-center text-xl font-semibold transition-transform transform hover:scale-105 hover:bg-blue-100 cursor-pointer">
                التحدث مع الروبوت
                <img src="C:\Users\alhas\OneDrive\Desktop\AnaHunaProject\Ana-Huna\public\images\robot.png" alt="Robot" class="w-16 h-16 mt-2" />
            </div>
                        <div @click="translationPage()" class="w-72 h-50 bg-gray-100 text-gray-900 rounded-2xl shadow-lg flex flex-col items-center justify-center text-xl font-semibold transition-transform transform hover:scale-105 hover:bg-blue-100 cursor-pointer">
                تحويل الإشارة إلى نص
                <img src="C:\Users\alhas\OneDrive\Desktop\AnaHunaProject\Ana-Huna\public\images\signtotext.png" class="w-16 h-16 mt-2 p-1 bg-[#176B87] rounded-xl" />
            </div>
        </div>
    </div>
</template>
