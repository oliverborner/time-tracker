<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <!-- 
            Lottie Animation
            TODO: Via JSON not external
        -->
        <div class="flex items-center justify-center mb-4">
          <DotLottieVue 
            style="height: 100px; width: 100px" 
            autoplay 
            loop 
            src="https://lottie.host/db21db34-33dd-486d-a9e6-79301645efc8/6kBCvGZZAY.lottie" 
          />
        </div>
  
        <h1 class="text-2xl font-bold mb-6 text-center">TimeTracker</h1>
  
        <form @submit.prevent="handleLogin" class="space-y-4">
  
          <!-- Email Input -->
          <div>
            <input 
              v-model="email" 
              type="email" 
              placeholder="Email" 
              :class="emailClasses" 
            />
            <p v-if="email && !emailValid" class="text-red-500 text-sm mt-1">Invalid email format</p>
          </div>
  
          <!-- Password Input -->
          <div>
            <input 
              v-model="password" 
              type="password" 
              placeholder="Password" 
              :class="passwordClasses" 
            />
            <p v-if="password && !passwordValid" class="text-red-500 text-sm mt-1">Password must be at least 6 characters</p>
          </div>
  
          <!-- Login Button -->
          <button 
            type="submit" 
            :disabled="!formValid"
            class="w-full bg-brand-600 text-white px-3 py-2 rounded hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
          >
            Login
          </button>
  
        </form>
      </div>
    </div>
  </template>
  
  <script lang="ts" setup>
  import { ref, computed, watch } from 'vue'
  import { useAuth } from '../composables/useAuth'
  import { useToast } from '@/composables/useToast'
  import { useRouter } from 'vue-router'
  import { DotLottieVue } from '@lottiefiles/dotlottie-vue'
  
  const { login } = useAuth()
  const { showToast } = useToast()
  const router = useRouter()
  
  const email = ref('')
  const password = ref('')
  
  const emailTouched = ref(false)
  const passwordTouched = ref(false)
  
  const emailValid = computed(() => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value))
  const passwordValid = computed(() => password.value.length >= 6)
  
  watch(email, () => { emailTouched.value = true })
  watch(password, () => { passwordTouched.value = true })
  
  const emailClasses = computed(() => [
    'w-full px-3 py-2 rounded border transition',
    !emailTouched.value ? 'border-gray-300' : emailValid.value ? 'border-green-500' : 'border-red-500'
  ])
  
  const passwordClasses = computed(() => [
    'w-full px-3 py-2 rounded border transition',
    !passwordTouched.value ? 'border-gray-300' : passwordValid.value ? 'border-green-500' : 'border-red-500'
  ])
  
  const formValid = computed(() => emailValid.value && passwordValid.value)
  
  const handleLogin = async () => {
    try {
      await login(email.value, password.value)
      showToast('success', 'Login successful', 'Redirecting to dashboard...')
      router.push('/dashboard')
    } catch (err) {
      showToast('error', 'Login failed', 'Invalid email or password')
    }
  }
  </script>
  