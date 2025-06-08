<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: { type: String, required: true },
  modelValue: { type: [String, Number], default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  error: { type: String, default: '' },
  id: { type: String, default: '' },
})

const emit = defineEmits(['update:modelValue'])

const inputId = computed(() => props.id || props.label.toLowerCase().replace(/\s+/g, '-'))
</script>

<template>
   <div>
    <label :for="inputId" class="block text-sm font-bold text-[#053B50] mb-1 text-right">
        {{ label }}
    </label>

    <input
        :id="inputId"
        :type="type"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="w-full border rounded-xl h-10 px-4 focus:outline-none focus:ring-2 text-right"
        :class="error ? 'border-red-500 focus:ring-red-500' : 'border-[#053B50] focus:ring-[#053B50]'"
        dir="rtl"
    />
    
    <p v-if="error" class="text-red-500 text-sm mt-1 text-right">{{ error }}</p>
</div>

</template>
