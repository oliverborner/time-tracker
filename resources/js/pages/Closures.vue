<template>
    <div class="border-t-4 border-indigo-500">
    <!-- HEADER -->
    <Header />

    <div class="container mx-auto min-h-screen p-6 bg-gray-100">
      <h1 class="text-2xl font-bold mb-4">Monthly Closures</h1>
  
      <div v-if="loading" class="text-gray-500">Loading closures...</div>
  
      <ul v-else class="space-y-4">
        <li v-for="closure in closures" :key="closure.id" class="p-4 bg-white rounded shadow">
            <h2 class="font-semibold">
              {{ closure.month }}/{{ closure.year }}
            </h2>
            <p class="text-sm text-gray-500">Closed by {{ closure.user.name }}</p>
          
            <div class="mt-2">
              <h3 class="font-semibold">Projects</h3>
              <ul class="list-disc list-inside text-sm">
                <li v-for="(hours, project) in closure.summary.projects" :key="project">
                  {{ project }}: {{ hours }}h
                </li>
              </ul>
          
              <h3 class="font-semibold mt-2">Users</h3>
              <ul class="list-disc list-inside text-sm">
                <li v-for="(hours, username) in closure.summary.users" :key="username">
                  {{ username }}: {{ hours }}h
                </li>
              </ul>
            </div>
          </li>          
      </ul>
    </div>
</div>
</template>

<script setup lang="ts">
  import { onMounted } from "vue";
  import { useMonthClosures } from "../composables/useMonthClosures";
  import Header from '../components/Header.vue';
  
  const { closures, loading, fetchAll: fetchClosures } = useMonthClosures();
  
  onMounted(async () => {
    try {
      await fetchClosures();
    } catch (err) {
      console.error("Error fetching closures:", err);
    }
  });
</script>
  