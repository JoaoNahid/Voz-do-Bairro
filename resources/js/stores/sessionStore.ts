import { ref, computed } from 'vue';

export interface User {
  name: string;
  email: string;
  neighborhood: string;
  city: string;
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

const currentUser = ref<User | null>({
  name: 'Carlos Eduardo Silva',
  email: 'carlos.silva@exemplo.com',
  neighborhood: 'Recanto dos Fernandes',
  city: 'Pouso Alegre, MG',
});

export function useSessionStore() {
  function login(userData?: Partial<User>) {
    isAuthenticated.value = true;
    currentUser.value = {
      name: userData?.name || 'Carlos Eduardo Silva',
      email: userData?.email || 'carlos.silva@exemplo.com',
      neighborhood: userData?.neighborhood || 'Recanto dos Fernandes',
      city: 'Pouso Alegre, MG',
    };
  }

  function logout() {
    isAuthenticated.value = false;
  }

  function toggleAuth() {
    if (isAuthenticated.value) {
      logout();
    } else {
      login();
    }
  }

  return {
    isAuthenticated,
    currentUser,
    login,
    logout,
    toggleAuth,
  };
}
