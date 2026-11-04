import { ref, computed } from 'vue';
import { api, ApiError } from '@/lib/api';

export interface Address {
  id?: number;
  cep: string;
  logradouro: string;
  numero: string;
  complemento?: string | null;
  bairro: string;
  cidade: string;
  uf: string;
  is_pilot_region?: boolean;
}

export interface User {
  id?: number;
  name: string;
  email: string;
  neighborhood?: string;
  city?: string;
  address?: Address | null;
}

export interface Report {
  id: string;
  title: string;
  category: 'vias' | 'saneamento' | 'iluminacao' | 'limpeza';
  categoryLabel: string;
  neighborhood: string;
  street: string;
  date: string;
  imageUrl: string;
  votesRelevant: number;
  votesIrrelevant: number;
  quorumPercentage: number;
  isPublicPriority: boolean;
  userVote?: 'relevant' | 'irrelevant';
}

const isAuthenticated = ref(false);
const currentUser = ref<User | null>(null);
const isLoading = ref(false);

export function useSessionStore() {
  async function fetchUser(): Promise<User | null> {
    try {
      const response = await api.get('/api/auth/me');
      if (response && response.user) {
        currentUser.value = {
          ...response.user,
          neighborhood: response.user.address?.bairro || '',
          city: response.user.address ? `${response.user.address.cidade}, ${response.user.address.uf}` : '',
        };
        isAuthenticated.value = true;
        return currentUser.value;
      }
    } catch (e) {
      currentUser.value = null;
      isAuthenticated.value = false;
    }
    return null;
  }

  async function register(payload: any) {
    isLoading.value = true;
    try {
      const response = await api.post('/api/auth/register', payload);
      if (response && response.user) {
        currentUser.value = {
          ...response.user,
          neighborhood: response.user.address?.bairro || payload.bairro,
          city: response.user.address
            ? `${response.user.address.cidade}, ${response.user.address.uf}`
            : `${payload.cidade}, ${payload.uf}`,
        };
        isAuthenticated.value = true;
      }
      return response;
    } finally {
      isLoading.value = false;
    }
  }

  async function login(credentials: { email: string; password: string; remember?: boolean }) {
    isLoading.value = true;
    try {
      const response = await api.post('/api/auth/login', credentials);
      if (response && response.user) {
        currentUser.value = {
          ...response.user,
          neighborhood: response.user.address?.bairro || '',
          city: response.user.address ? `${response.user.address.cidade}, ${response.user.address.uf}` : '',
        };
        isAuthenticated.value = true;
      }
      return response;
    } finally {
      isLoading.value = false;
    }
  }

  async function logout() {
    isLoading.value = true;
    try {
      await api.post('/api/auth/logout');
    } catch (e) {
      console.warn('Erro ao encerrar sessão no servidor:', e);
    } finally {
      currentUser.value = null;
      isAuthenticated.value = false;
      isLoading.value = false;
    }
  }

  async function updateProfile(payload: any) {
    isLoading.value = true;
    try {
      const response = await api.put('/api/profile', payload);
      if (response && response.user) {
        currentUser.value = {
          ...response.user,
          neighborhood: response.user.address?.bairro || payload.bairro,
          city: response.user.address
            ? `${response.user.address.cidade}, ${response.user.address.uf}`
            : `${payload.cidade}, ${payload.uf}`,
        };
      }
      return response;
    } finally {
      isLoading.value = false;
    }
  }

  async function updatePassword(payload: any) {
    isLoading.value = true;
    try {
      return await api.put('/api/profile/password', payload);
    } finally {
      isLoading.value = false;
    }
  }

  async function deleteAccount(payload: { current_password: string }) {
    isLoading.value = true;
    try {
      const response = await api.delete('/api/profile/account', payload);
      currentUser.value = null;
      isAuthenticated.value = false;
      return response;
    } finally {
      isLoading.value = false;
    }
  }

  return {
    isAuthenticated,
    currentUser,
    isLoading,
    fetchUser,
    register,
    login,
    logout,
    updateProfile,
    updatePassword,
    deleteAccount,
  };
}
