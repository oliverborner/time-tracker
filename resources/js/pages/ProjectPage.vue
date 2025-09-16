<template>
  <div class="border-t-4 border-indigo-500">
    
    <!-- HEADER -->
    <Header />

    <!--  TODO: Dynamic Breadcrumb: Dashboard / Project-->
    <section class="grid grid-cols-1 px-6 pb-2 text-gray-400 border-b-1 border-gray-200">
      <span>
        <a class="text-gray-600 hover:text-gray-800 hover:underline cursor-pointer" @click="router.push('/dashboard')">Dashboard</a> 
        &raquo; {{ project.name }}
      </span>
    </section>

    <div class="container mx-auto min-h-screen p-6 bg-gray-100">
      <h1 class="text-2xl font-bold mb-2">Project: {{ project.name }}</h1>
      <p class="mb-4 text-gray-400">{{ project.description }}</p>
      
      <!-- Overall -->
      <section class="mb-8 max-w-md container">
        <div class="p-3 bg-white rounded shadow cursor-pointer">
          <h2 class="text-sm font-light text-gray-500 mb-2">Statistics</h2>
          <p>Overall time booked on this project: <strong>{{ overallHours }}h</strong></p>
          <p>Time booked today: <strong>{{ todayHours }}h</strong></p>
        </div>
      </section>

      <!-- Time Entries 
        TODO:   - Filter: datepicker, select date, calendar
                - Admin: filter by User
                - Pagination, Search
      -->
      <section class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Time Entries</h2>
        <div v-if="loading" class="text-gray-500">Loading entries...</div>
        <ul v-else class="space-y-2">
           
            <li
            v-for="entry in entries"
            :key="entry.id"
            class="p-3 bg-white rounded shadow items-center grid grid-cols-1 md:grid-cols-4 gap-2"
          >
            <!-- Edit mode -->
            <template v-if="editingId === entry.id">
              <input type="date" v-model="editDay" class="border rounded px-2 py-1" />
          
              <div class="flex space-x-2 justify-center items-baseline">
                <input type="number" v-model.number="editHours" min="0" class="border rounded px-2 py-1 w-16" placeholder="h" /> h
                <input type="number" v-model.number="editMinutes" step="15" min="0" max="59" class="border rounded px-2 py-1 w-16 mx-2" placeholder="m" /> min
              </div>
          
              <input v-model="editNote" type="text" class="border rounded px-2 py-1" />
          
              <div class="flex justify-end space-x-2">
                <button @click="saveEdit" class="bg-green-500 text-white px-2 py-1 rounded">Save</button>
                <button @click="cancelEdit" class="bg-gray-400 text-white px-2 py-1 rounded">Cancel</button>
              </div>
            </template>
          
            <!-- View mode -->
            <template v-else>
                <span>
                  <span class="text-gray-400 pr-2">{{ new Date(entry.day).toLocaleDateString('de-DE').split('T')[0] }}</span>  <strong> {{ entry.hours }} h</strong>
                </span>
                <p>{{ entry.note }}</p>
                <p v-if="isAdmin" class="text-sm text-gray-500">by {{ entry.user.name }}</p>
                <p v-else></p>
            
                <div class="flex space-x-2 justify-end">
                  <button v-if="!entry.locked" @click="startEdit(entry)">
                      <EditIcon class="w-5 h-5 mt-1 text-gray-500 hover:text-gray-700" />
                  </button>
                  <button v-if="!entry.locked" @click="remove(entry.id)" class="text-red-500 hover:text-red-700">
                    <TrashIcon class="w-5 h-5" />
                  </button>
                  <span v-else class="text-gray-400 text-sm">closed</span>
                </div>
              </template>
            </li>
          </ul>
        </section>
  
      <!-- Add Time Entry -->
      <section class="container md:flex justify-end items-center">
        <h2 class="text-xl font-semibold mb-2 mr-4">Add Time Entry</h2>

        <input
            type="date"
            id="current day"
            v-model="day"
            :min="monthStart"
            :max="todayStr"
            class="px-3 py-2 border rounded mr-2"
        />

        <input
          v-model.number="hoursInput"
          type="number"
          min="0"
          class="px-3 py-2 border rounded mr-2 w-20 ml-2"
          placeholder="h"
        />h 
        
        <input
          v-model.number="minutesInput"
          type="number"
          step="15"
          min="0"
          max="59"
          class="px-3 py-2 border rounded mr-2 w-20"
          placeholder="m"
        />min 
      
        <input v-model="note" type="text" placeholder="Note" class="ml-2 px-3 py-2 border rounded mr-2" />

        <button
          @click="submitEntry"
          class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 transition"
        >
          Add
        </button>
      </section>
      
      <!-- Delete Confirmation Modal -->
        <Modal v-if="showDeleteModal" fullScreenBackdrop @close="closeDeleteModal">
            <template #body>
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <h3 class="text-lg font-bold mb-4">
                Delete Entry from {{ new Date(entryToDelete?.day).toLocaleDateString('de-DE') }}?
                </h3>
                <p class="mb-6 text-gray-600">
                Are you sure you want to delete this entry? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-2">
                <button
                    @click="closeDeleteModal"
                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 transition"
                >
                    Cancel
                </button>
                <button
                    @click="confirmDelete"
                    class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700 transition"
                >
                    Delete
                </button>
                </div>
            </div>
            </template>
        </Modal>
  
    </div>

  </div>
</template>
  
<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useTimeEntries } from '../composables/useTimeEntries';
import { useProjects } from '../composables/useProjects';
import { TrashIcon, EditIcon } from '../components/ui/icons';
import { useAuth } from '../composables/useAuth';
import Header from '../components/Header.vue';
import { useToast } from '@/composables/useToast';
import Modal from '../components/ui/Modal.vue';

const { showToast } = useToast();

const { user, fetchUser } = useAuth();

const route = useRoute();
const router = useRouter();
const projectId = Number(route.params.id);

const isAdmin = computed(() => user.value?.role === 'admin');

const { entries, loading, fetchAll: fetchEntries, create, remove, update } = useTimeEntries(projectId);
const { projects, fetchAll: fetchProjects } = useProjects();

const project = ref<{ id: number; name: string; description?: string }>({
  id: projectId,
  name: '',
  description: ''
});


const hoursInput = ref(0);
const minutesInput = ref(0);
const note = ref('');

const today = new Date();
const year = today.getFullYear();
const month = today.getMonth(); 

const editingId = ref<number | null>(null);
const editDay = ref("");
const editHours = ref(0);
const editMinutes = ref(0);
const editNote = ref("");

const todayStr = today.toISOString().split("T")[0]; 

const pad = (n: number) => String(n).padStart(2, "0");

const monthStart = `${year}-${pad(month + 1)}-01`;
const monthEnd = new Date(year, month + 1, 0)
  .toISOString()
  .split("T")[0];

const day = ref(today.toISOString().split("T")[0]);


onMounted(async () => {
  await Promise.all([
    fetchEntries(),
    fetchProjects(),
    fetchUser()
  ]);

  const proj = projects.value.find(p => p.id === projectId);
  if (proj) project.value = proj;
});


const submitEntry = async () => {
  const total = hoursInput.value + minutesInput.value / 60;
  const rounded = Math.ceil(total * 4) / 4; // round up to next 15min

  await create(day.value, `${hoursInput.value}h ${minutesInput.value}m`, note.value, rounded);

  showToast('success', 'Entry created successfully', '');

  hoursInput.value = 0;
  minutesInput.value = 0;
  note.value = "";
};

/* Calculate stats */
const overallHours = computed(() => {
  return entries.value.reduce((sum, e) => sum + (e.hours || 0), 0);
});

const todayHours = computed(() => {
  const today = new Date().toISOString().split("T")[0];
  return entries.value
    .filter(e => e.day.startsWith(today)) 
    .reduce((sum, e) => sum + (e.hours || 0), 0);
});


/* Update time entries */
const startEdit = (entry: any) => {
  editingId.value = entry.id;
  editDay.value = entry.day;
  editHours.value = Math.floor(entry.hours);
  editMinutes.value = Math.round((entry.hours % 1) * 60);
  editNote.value = entry.note || "";
};

const cancelEdit = () => {
  editingId.value = null;
};

const saveEdit = async () => {
  if (!editingId.value) return;

  const total = editHours.value + editMinutes.value / 60;
  const rounded = Math.ceil(total * 4) / 4;

  await update(editingId.value, {
    day: editDay.value,
    hours: rounded,
    note: editNote.value,
  });

  showToast('success', 'Entry created successfully', '');

  editingId.value = null;
};


/* 
    Modal actions 
*/
const showDeleteModal = ref(false);
const entryToDelete = ref<any | null>(null);

const openDeleteModal = (entry: any) => {
  entryToDelete.value = entry;
  showDeleteModal.value = true;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  entryToDelete.value = null;
};

const confirmDelete = async () => {
  if (entryToDelete.value) {
    await remove(entryToDelete.value.id);
    showToast('success', 'Entry deleted successfully', '');
  }
  closeDeleteModal();
};



</script>
