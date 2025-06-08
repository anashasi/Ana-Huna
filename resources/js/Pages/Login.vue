<script setup>
import InputField from '../Components/InputField.vue'
import { router,useForm } from '@inertiajs/vue3'

const form=useForm({
  email: null,
  password: null,
});

function cancel(){
    router.visit('/');
}

function login(){
    form.post('/login');
}

</script>

<template>
    <div class="h-screen flex items-center justify-center">
        <div class="w-[400px] bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-6">
                <p class="font-bold text-2xl text-[#053B50] font-serif">تسجيل الدخول</p>
            </div>

            <form @submit.prevent="login" class="space-y-6">
                <InputField
                    label="البريد الالكتروني"
                    type="email"
                    placeholder="you@example.com"
                    v-model="form.email"
                    :error="form.errors.email"
                />
                <InputField
                    label="كلمة السر"
                    type="password"
                    placeholder="••••••••"
                    v-model="form.password"
                    :error="form.errors.password"
                />
                <div class="flex justify-end gap-3 pt-4">
                <button
                    type="button"
                    @click="cancel"
                    class="bg-gray-200 text-black rounded-xl px-4 py-2 hover:bg-gray-300 transition"
                >
                    الغاء
                </button>
                <button
                    type="submit"
                    class="bg-[#053B50] text-white rounded-xl px-4 py-2 hover:bg-[#064663] transition"
                >
                    تسجيل الدخول
                </button>
                </div>
            </form>
        </div>
    </div>
</template>

