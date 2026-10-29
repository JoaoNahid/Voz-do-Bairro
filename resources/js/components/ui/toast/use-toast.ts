import { ref } from 'vue';

export interface Toast {
  id: string;
  title?: string;
  description?: string;
  type?: 'default' | 'success' | 'destructive';
}

const toasts = ref<Toast[]>([]);

export function useToast() {
  function toast({ title, description, type = 'default' }: Omit<Toast, 'id'>) {
    const id = Math.random().toString(36).substring(2, 9);
    const newToast: Toast = { id, title, description, type };
    toasts.value.push(newToast);

    setTimeout(() => {
      dismiss(id);
    }, 4000);
  }

  function dismiss(id: string) {
    toasts.value = toasts.value.filter((t) => t.id !== id);
  }

  return {
    toasts,
    toast,
    dismiss,
  };
}
