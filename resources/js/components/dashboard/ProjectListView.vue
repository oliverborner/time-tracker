<template>
    <section class="mb-8 container mx-auto px-2">
      <h2 class="text-xl font-semibold mb-2">Projects</h2>
  
      <div v-if="loading" class="text-gray-500">Loading projects...</div>
  
      <ul v-else class="space-y-2">
        <li
          v-for="project in projects"
          :key="project.id"
          class="p-3 bg-white rounded shadow flex justify-between items-center cursor-pointer hover:bg-gray-50 hover:border-l-4 border-indigo-500"
          @click="$emit('go', project.id)"
        >
          <div>
            <span class="font-semibold">{{ project.name }}</span>
            <p class="text-gray-400">{{ project.description?.substring(0, 120) }}</p>
          </div>
          <button
            v-if="isAdmin"
            @click.stop="openDeleteModal(project)"
            class="text-red-500 hover:text-red-700 transition"
          >
            <TrashIcon class="w-5 h-5" />
          </button>
        </li>
      </ul>
  
      <!-- Add Project Button -->
      <div v-if="isAdmin" class="mt-4 flex justify-end">
        <button
          @click="openAddModal"
          class="bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600 transition"
        >
          Add Project
        </button>
      </div>
  
      <!-- Delete Confirmation Modal -->
      <Modal v-if="showDeleteModal" fullScreenBackdrop @close="closeModal">
        <template #body>
          <div class="bg-white p-6 rounded shadow-lg w-96">
            <h3 class="text-lg font-bold mb-4">
              Delete Project "{{ projectToDelete?.name }}"?
            </h3>
            <p class="mb-6 text-gray-600">
              Are you sure you want to delete this project? This action cannot be undone.
            </p>
            <div class="flex justify-end gap-2">
              <button
                @click="closeModal"
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
  
      <!-- Add Project Modal -->
      <Modal v-if="showAddModal" fullScreenBackdrop @close="closeAddModal">
        <template #body>
          <div class="bg-white p-6 rounded shadow-lg w-96">
            <h3 class="text-lg font-bold mb-4">Add New Project</h3>
  
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-medium mb-1">Name</label>
              <input
                v-model="newProjectName"
                type="text"
                placeholder="Project name"
                class="w-full px-3 py-2 border rounded"
              />
            </div>
  
            <div class="mb-6">
              <label class="block text-gray-700 text-sm font-medium mb-1">Description</label>
              <textarea
                v-model="newProjectDescription"
                placeholder="Project description"
                rows="3"
                class="w-full px-3 py-2 border rounded"
              ></textarea>
            </div>
            
            <!-- 
                TODO:  Assign Team
                        Assign Employee
            -->
            <div class="flex justify-end gap-2">
              <button
                @click="closeAddModal"
                class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 transition"
              >
                Cancel
              </button>
              <button
                @click="confirmAdd"
                class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition"
              >
                Add
              </button>
            </div>
          </div>
        </template>
      </Modal>
    </section>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue';
  import { TrashIcon } from '../ui/icons';
  import Modal from '../ui/Modal.vue';
  import { useToast } from '@/composables/useToast';
  
  defineProps<{
    projects: any[];
    loading: boolean;
    isAdmin: boolean;
  }>();
  
  const { showToast } = useToast();
  
  const emit = defineEmits<{
    (e: 'remove', id: number): void;
    (e: 'add', project: { name: string; description: string }): void;
    (e: 'go', id: number): void;
  }>();
  
  // Delete modal state
  const showDeleteModal = ref(false);
  const projectToDelete = ref<any | null>(null);
  
  // Add modal state
  const showAddModal = ref(false);
  const newProjectName = ref('');
  const newProjectDescription = ref('');
  
  // Delete project flow
  const openDeleteModal = (project: any) => {
    projectToDelete.value = project;
    showDeleteModal.value = true;
  };
  
  const closeModal = () => {
    showDeleteModal.value = false;
    projectToDelete.value = null;
  };
  
  const confirmDelete = () => {
    if (projectToDelete.value) {
      emit('remove', projectToDelete.value.id);
      showToast('success', 'Project deleted successfully', '');
    }
    closeModal();
  };
  
  // Add project flow
  const openAddModal = () => {
    showAddModal.value = true;
  };
  
  const closeAddModal = () => {
    showAddModal.value = false;
    newProjectName.value = '';
    newProjectDescription.value = '';
  };


  const confirmAdd = () => {
    if (!newProjectName.value || !newProjectDescription.value) return;
    emit('add', {
        name: newProjectName.value,
        description: newProjectDescription.value,
    });
    newProjectName.value = '';
    newProjectDescription.value = '';
    showToast('success', 'Project added successfully', '');
        closeAddModal();
    };
</script>
  