import { ref } from "vue";
import axios from "axios";

export function useMonthClosures() {
  const closures = ref<any[]>([]);
  const loading = ref(false);

  const authHeaders = () => {
    const token = localStorage.getItem("token");
    return token
      ? { Authorization: `Bearer ${token}` }
      : {};
  };

  const fetchAll = async () => {
    loading.value = true;
    try {
      const { data } = await axios.get(`/api/month-closures`, {
        headers: authHeaders(),
        withCredentials: true,
      });
      closures.value = data;
    } catch (err) {
      console.error("Error fetching month closures:", err);
    } finally {
      loading.value = false;
    }
  };

  const create = async (year: number, month: number) => {
    try {
      const { data } = await axios.post(
        `/api/month-closures`,
        { year, month },
        {
          headers: authHeaders(),
          withCredentials: true,
        }
      );

      closures.value.push(data);
      return data;
    } catch (err) {
      console.error("Error creating month closure:", err);
      throw err;
    }
  };

  return { closures, loading, fetchAll, create };
}
