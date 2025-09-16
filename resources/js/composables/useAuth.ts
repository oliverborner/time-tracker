import { ref } from 'vue';
import { useRouter } from 'vue-router';

interface User {
  id: number;
  name: string;
  email: string;
  role: string;
}

export function useAuth() {
  const user = ref<User | null>(null);
  const token = ref(localStorage.getItem('token') || '');
  const router = useRouter();

  const login = async (email: string, password: string) => {
    const res = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
    });

    if (!res.ok) {
      throw new Error('Login failed: Invalid credentials');
    }

    const data = await res.json(); 
    token.value = data.token;
    localStorage.setItem('token', token.value);

    user.value = data.user;

    router.push('/dashboard');
  };


  const fetchUser = async () => {
    if (!token.value) return;

    try {
      const res = await fetch('http://127.0.0.1:8000/api/user', {
        headers: { Authorization: `Bearer ${token.value}` },
      });

      if (!res.ok) {
        console.warn('Failed to fetch user');
        logout(); 
        return;
      }

      const data = await res.json();
      user.value = data;
    } catch (error) {
      console.error('Error fetching user:', error);
      logout();
    }
  };

  const logout = () => {
    user.value = null;
    token.value = '';
    localStorage.removeItem('token');
    router.push('/login');
  };

  return { user, token, login, fetchUser, logout };
}
