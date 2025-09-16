<template>
    <section class="mb-8 max-w-md container px-2">
      <div class="p-3 bg-white rounded shadow cursor-pointer hover:bg-gray-50">
        <h2 class="text-sm font-light text-gray-500 mb-2">Profile</h2>
        <p v-if="user">Your role: <strong class="capitalize">{{ user.role }}</strong></p>
        <!-- TODO -->
        <p>Last time logged in: 12.09.25 - 23:00 Uhr</p>
      </div>
    </section>
  </template>
  
  <script setup lang="ts">
  defineProps<{
    user: any;
  }>();
  </script>
  