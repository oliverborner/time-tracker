import { createRouter, createWebHistory } from 'vue-router';
import Login from '../pages/Login.vue';
import Closures from '../pages/Closures.vue';
import Dashboard from '../pages/Dashboard.vue';
import { useAuth } from '../composables/useAuth';
import ProjectPage from '../pages/ProjectPage.vue'

const routes = [
  { path: '/login', name: 'Login', component: Login },
  { path: '/dashboard', name: 'Dashboard', component: Dashboard, meta: { requiresAuth: true } },
  { path: '/:pathMatch(.*)*', redirect: '/login' },
  { path: '/projects/:id', name: 'ProjectPage', component: ProjectPage, props: true },
  { path: '/closures', component: Closures, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const { user, token, fetchUser } = useAuth();

  if (token.value && !user.value) {
    await fetchUser();
  }

  if (to.meta.requiresAuth && !token.value) {
    next('/login');
  } else if (to.path === '/login' && token.value) {
    next('/dashboard');
  } else {
    next();
  }
});

export default router;
