<template>
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label>Projekt</label>
        <select v-model="form.project_id" class="form-select">
          <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }}</option>
        </select>
      </div>
  
      <div class="mb-3">
        <label>Datum</label>
        <input type="date" v-model="form.day" class="form-control" required />
      </div>
  
      <div class="mb-3">
        <label>Zeit</label>
        <input type="text" v-model="form.time_input" class="form-control" required />
      </div>
  
      <button class="btn btn-primary">Speichern</button>
    </form>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue';
  import type { Project } from '../types/types';
  
  const props = defineProps<{ projects: Project[] }>();
  const emit = defineEmits<{ (e: 'saved'): void }>();
  
  interface FormData {
    project_id: number | null;
    day: string;
    time_input: string;
    note?: string;
  }
  
  const form = ref<FormData>({
    project_id: null,
    day: new Date().toISOString().substring(0, 10),
    time_input: '',
  });
  
  async function submit() {
    const res = await fetch('/api/time-entries', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value),
    });
    if (res.ok) {
      emit('saved');
      form.value.time_input = '';
    } else {
      const err = await res.json();
      alert(err.message || 'Fehler');
    }
  }
  </script>
  