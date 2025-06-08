<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'primary'
  }
})
</script>


<template>
    <button :type="props.type" :class="'text-black bg-[#F6F6F6] rounded-3xl inline-flex items-center px-4 py-2 hover:bg-gray-300  active:bg-gray-400 '">
        <slot />
    </button>
</template>