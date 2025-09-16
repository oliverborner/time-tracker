import { ref } from 'vue'

type Variant = 'success' | 'error' | 'warning' | 'info'

interface Toast {
  id: number
  variant: Variant
  title: string
  message: string
}

const toasts = ref<Toast[]>([])
let idCounter = 0

export function useToast() {
  const showToast = (variant: Variant, title: string, message: string) => {
    const id = idCounter++
    toasts.value.push({ id, variant, title, message })

    // Auto-remove after 3 seconds
    setTimeout(() => {
      toasts.value = toasts.value.filter(t => t.id !== id)
    }, 3000)
  }

  return {
    toasts,
    showToast,
  }
}
