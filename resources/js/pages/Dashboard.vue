<template>
    <div class="border-t-4 border-indigo-500">
  
      <!-- HEADER -->
      <Header />
  
      <div class="container mx-auto bg-gray-100 p-4"> 
  
        <!-- Overview Section -->
        <div class="grid justify-items-stretch grid-cols-1 md:grid-cols-3 pb-4">
          <Summary :user="user" />
          <Calendar class="md:justify-self-center" :user="user" />
          <Shortcuts class="md:justify-self-end" :user="user" />
        </div>
  
        <!-- Project Listing Section -->
        <ProjectListView
          :projects="projects"
          :loading="loadingProjects"
          :is-admin="isAdmin"
          @remove="removeProject"
          @add="addProject"
          @go="goToProject"
        />
      </div>
  
      <!-- Close Month -->
      <section class="mb-8 max-w-md container mx-auto px-2" v-if="isAdmin">
        <div class="grid justify-items-stretch grid-cols-1 p-3 bg-white rounded shadow cursor-pointer hover:bg-gray-50">
          <h2 class="text-sm font-light text-gray-500 mb-2">Admin Controls</h2>
          <router-link
            to="/closures"
            class="px-4 py-2 bg-white text-gray-600 text-center border border-gray-400 rounded hover:bg-gray-200 mb-4"
          >
            Show Closures
          </router-link>
  
          <button
            @click="openCloseMonthModal"
            class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-600 w-full"
          >
            Close Current Month
          </button>
        </div>
      </section>
  
      <!-- Modal -->
      <Modal v-if="showCloseMonthModal" fullScreenBackdrop @close="cancelCloseMonth">
        <template #body>
          <div class="bg-white p-6 rounded shadow-lg w-96">
            <h3 class="text-lg font-bold mb-4">Close Current Month?</h3>
            <p class="mb-6 text-gray-600">
              Are you sure you want to close this month? All entries will be locked and cannot be edited.
            </p>
            <div class="flex justify-end gap-2">
              <button
                @click="cancelCloseMonth"
                class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 transition"
              >
                Cancel
              </button>
              <button
                @click="confirmCloseMonth"
                class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-600"
              >
                Close Current Month
              </button>
            </div>
          </div>
        </template>
      </Modal>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, onMounted, computed } from 'vue';
  import { useAuth } from '../composables/useAuth';
  import { useProjects } from '../composables/useProjects';
  import { useMonthClosures } from "../composables/useMonthClosures";
  import { useRouter } from 'vue-router';
  import { useToast } from '@/composables/useToast';
  
  import Header from '../components/Header.vue';
  import Modal from '../components/ui/Modal.vue';
  import Summary from '../components/dashboard/Summary.vue';
  import Calendar from '../components/dashboard/Calendar.vue';
  import Shortcuts from '../components/dashboard/Shortcuts.vue';
  import ProjectListView from '../components/dashboard/ProjectListView.vue';
  
  const { user, fetchUser } = useAuth();
  const { closures, fetchAll: fetchClosures, create: createClosure } = useMonthClosures();
  const { projects, fetchAll: fetchProjects, create: createProject, remove: removeProject, loading: loadingProjects } = useProjects();
  const router = useRouter();
  const { showToast } = useToast();
  
  const isAdmin = computed(() => user.value?.role === 'admin');
  
  const addProject = async (project) => {
    if (!project) return;
    await createProject(project);
    await fetchProjects();
  };
  
  const goToProject = (projectId: number) => {
    router.push(`/projects/${projectId}`);
  };
  
  /* Close Month Modal */
  const showCloseMonthModal = ref(false);
  
  const openCloseMonthModal = () => {
    showCloseMonthModal.value = true;
  };
  
  const cancelCloseMonth = () => {
    showCloseMonthModal.value = false;
  };
  
  const confirmCloseMonth = async () => {
    const now = new Date();
    try {
        const res = await createClosure(now.getFullYear(), now.getMonth() + 1);

        if (res.closure) {
            showToast('success', 'Month closed successfully', '');
        } else {
            showToast('info', 'Nothing to close for this month', '');
        }

        await fetchClosures();
        showToast('success', 'Month closed successfully', '');
        showCloseMonthModal.value = false;
    } catch (err) {
        console.error("Error closing month:", err);
        showToast('error', 'Failed to close month', '');
        showCloseMonthModal.value = false;
    }
  };

  onMounted(async () => {
    await fetchUser();
    await fetchProjects();
    if (isAdmin.value) {
      await fetchClosures();
    }
  });
  </script>
  