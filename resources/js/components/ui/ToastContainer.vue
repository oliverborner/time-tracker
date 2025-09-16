<template>
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex flex-col-reverse items-center">
      <transition-group
        name="toast"
        tag="div"
        class="space-y-3 flex flex-col-reverse items-center"
      >
        <Alert
          v-for="toast in toasts"
          :key="toast.id"
          :variant="toast.variant"
          :title="toast.title"
          :message="toast.message"
          class="shadow-lg w-80"
        />
      </transition-group>
    </div>
  </template>
  
  <script setup lang="ts">
  import { useToast } from '@/composables/useToast'
  import Alert from './Alert.vue'
  
  const { toasts } = useToast()
  </script>
  
  <style scoped>
  .toast-enter-active,
  .toast-leave-active {
    transition: all 0.4s ease;
  }
  .toast-enter-from {
    opacity: 0;
    transform: translateY(30px);
  }
  .toast-enter-to {
    opacity: 1;
    transform: translateY(0);
  }
  .toast-leave-from {
    opacity: 1;
    transform: translateY(0);
  }
  .toast-leave-to {
    opacity: 0;
    transform: translateY(30px);
  }
  </style>
  