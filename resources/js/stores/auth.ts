import { defineStore } from 'pinia';

interface User {
  id: number;
  name: string;
  email: string;
  is_admin: boolean;
}

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as User | null,
    token: localStorage.getItem('token') || '',
  }),
  actions: {
    async login(email: string, password: string) {
      const res = await api.post('/login', { email, password });
      this.token = res.data.token;
      localStorage.setItem('token', this.token);
      await this.fetchUser();
    },
    async fetchUser() {
      const res = await api.get('/user');
      this.user = res.data;
    },
    logout() {
      this.user = null;
      this.token = '';
      localStorage.removeItem('token');
    },
  },
});
