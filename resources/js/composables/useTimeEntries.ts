import { ref } from "vue";
import axios from "axios";

export function useTimeEntries(projectId: number) {
  const entries = ref<any[]>([]);
  const loading = ref(false);

  // Helper: get token from localStorage / TODO: pinia
  const authHeaders = () => {
    const token = localStorage.getItem("token");
    return token
      ? { Authorization: `Bearer ${token}` }
      : {};
  };

  const fetchAll = async () => {
    loading.value = true;
    try {
      const { data } = await axios.get(`/api/projects/${projectId}/entries`, {
        headers: authHeaders(),
        withCredentials: true,
      });
      entries.value = data;
    } catch (err) {
      console.error("Error fetching entries:", err);
    } finally {
      loading.value = false;
    }
  };

  const create = async (day: string, time_input: string, note: string, hours:string) => {
    try {
      await axios.post(
        `/api/projects/${projectId}/entries`,
        { day, time_input, note, hours },
        {
          headers: authHeaders(),
          withCredentials: true,
        }
      );
      await fetchAll(); 
    } catch (err) {
      console.error("Error creating entry:", err);
    }
  };

  const update = async (id: number, payload: any) => {
    try {
      await axios.put(`/api/entries/${id}`, payload, {
        headers: authHeaders(),
        withCredentials: true,
      });
      await fetchAll();
    } catch (err) {
      console.error("Error updating entry:", err);
    }
  };

  const remove = async (id: number) => {
    try {
      await axios.delete(`/api/entries/${id}`, {
        headers: authHeaders(),
        withCredentials: true,
      });
      await fetchAll();
    } catch (err) {
      console.error("Error deleting entry:", err);
    }
  };

  return { entries, loading, fetchAll, create, update, remove };
}
