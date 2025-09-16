<template>
    <section class="mb-8 max-w-md container px-2">
      <div class="p-3 bg-white rounded shadow cursor-pointer hover:bg-gray-50">
        <h2 class="text-sm font-light text-gray-500 mb-2">Calendar</h2>
        <p>{{new Date().toISOString().slice(0, 10)}}</p>
        <p>&nbsp;</p>
      </div>
    </section>
  </template>
  
  <script setup lang="ts">
  defineProps<{
    user: any;
  }>();
  </script>
  