<script setup>
import { usePage } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'

const page = usePage();
const user = page.props.auth.user;

function logout() {
  router.post('/logout')
}
</script>


<template>
    <header class="bg-[#176B87] shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-4 lg:px-4">
            <div class="flex justify-between  items-center h-16">
            <!-- Logo -->
                <div class="flex-shrink-0">
                <a href="/" class="text-xl font-bold text-gray-300">AnaHuna</a>
                </div>

            <!-- Navigation Links -->
            <nav class="hidden md:flex space-x-4 ml-12">
                <a href="/aboutus" class="text-gray-200 hover:text-[#64CCC5]">من نحن</a>
                <a href="/ourservices" class="text-gray-200 hover:text-[#64CCC5]">خدماتنا</a>
                <a href="/" class="text-gray-200 hover:text-[#64CCC5]">الصفحة الرئيسية</a>
            </nav>

            
            <div v-if="!user" class="hidden md:flex space-x-2 ">
              <a href="/register" class="bg-[#64CCC5] text-white px-4 py-2 rounded-3xl hover:bg-[#053B50]">
                تسجيل
              </a>
              <a href="/login" class="bg-[#64CCC5] text-white px-4 py-2 rounded-3xl hover:bg-[#053B50]">
                تسجيل الدخول
              </a>
            </div>

            <div v-if="user" class="hidden md:flex space-x-2">
              <button
                @click="logout"
                class="bg-[#64CCC5] text-white px-4 py-2 rounded-3xl hover:bg-[#053B50]"
              >
                تسجيل الخروج
              </button>
            </div>
            
          </div>
        </div>
  </header>
</template>