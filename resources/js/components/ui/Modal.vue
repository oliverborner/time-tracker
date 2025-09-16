<template>
    <div class="fixed inset-0 flex items-center justify-center z-50">
      <!-- Backdrop -->
      <div
        v-if="fullScreenBackdrop"
        class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
        aria-hidden="true"
        @click="$emit('close')"
      ></div>
  
      <!-- Modal content -->
      <div class="relative z-60">
        <slot name="body"></slot>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  interface ModalProps {
    fullScreenBackdrop?: boolean
  }
  defineProps<ModalProps>()
  defineEmits(['close'])
  </script>
  
  