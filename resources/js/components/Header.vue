<template>
    <header class="grid grid-cols-1 md:grid-cols-2 mb-2 px-6 py-4 bg-white">
      <h1 class="text-2xl font-bold cursor-pointer hover:text-indigo-500 transition"
        @click="home">
        TimeTracker
      </h1>
      <div class="flex justify-start md:justify-end items-center">
        <span class="mr-4">Hello, {{ user?.name }}</span>
        <button
          @click="logout"
          class="text-gray-400 hover:text-red-400 transition flex justify-between items-center">
          <span class="border-l-1 border-gray-300 pl-4">Logout</span>
          <LogoutIcon class="w-5 h-5 ml-2" alt="Logout"/>
        </button>
      </div>
    </header>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useAuth } from '../composables/useAuth';
import { LogoutIcon } from './ui/icons';
import { useRouter } from 'vue-router';

const auth = useAuth();
const user = auth.user;
const logout = auth.logout;
const router = useRouter();

const home = () => {
  router.push('/dashboard'); 
};

onMounted(async () => {
  if (!user.value) {
    await auth.fetchUser();
  }
});
</script>