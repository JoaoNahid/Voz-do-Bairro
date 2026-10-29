<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Megaphone, User, LogIn, LogOut, Menu, X, ShieldAlert, MapPin } from 'lucide-vue-next';
import { useSessionStore } from '@/stores/sessionStore';

const emit = defineEmits<{
  (e: 'open-auth', tab: 'login' | 'register'): void;
}>();

const { isAuthenticated, currentUser, toggleAuth, logout } = useSessionStore();
const mobileMenuOpen = ref(false);
</script>

<template>
  <header class="sticky top-0 z-40 w-full border-b border-border/80 bg-background/85 backdrop-blur-md transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between gap-4">
      <!-- Logo & City Tag -->
      <a href="#" class="flex items-center gap-3 group">
        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-blue-800 flex items-center justify-center text-white shadow-md shadow-primary/20 group-hover:scale-105 transition-transform">
          <Megaphone class="h-5 w-5 text-amber-400" />
        </div>
        <div>
          <div class="flex items-center gap-2">
            <span class="font-extrabold text-xl tracking-tight text-foreground">Voz do Bairro</span>
            <Badge variant="priority" class="text-[10px] py-0 px-1.5 uppercase tracking-wider">Cívico</Badge>
          </div>
          <p class="text-xs text-muted-foreground flex items-center gap-1">
            <MapPin class="h-3 w-3 text-amber-500" /> Pouso Alegre, MG
          </p>
        </div>
      </a>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-muted-foreground">
        <a href="#hero" class="hover:text-primary transition-colors">Início</a>
        <a href="#denuncias" class="hover:text-primary transition-colors flex items-center gap-1.5">
          <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
          </span>
          Denúncias em Alta
        </a>
        <a href="#reportar" class="hover:text-primary transition-colors">Fazer Denúncia</a>
        <a href="#sobre" class="hover:text-primary transition-colors">Sobre o Projeto</a>
      </nav>

      <!-- Auth State & Simulation Controls -->
      <div class="hidden sm:flex items-center gap-3">
        <!-- Interactive Simulated Mode Switcher -->
        <button
          @click="toggleAuth"
          type="button"
          class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-border bg-muted/60 text-xs font-semibold hover:bg-muted transition-all cursor-pointer"
          title="Clique para alternar entre o modo Visitante e Morador Autenticado (Demonstração)"
        >
          <span class="h-2 w-2 rounded-full" :class="isAuthenticated ? 'bg-emerald-500 shadow-sm shadow-emerald-500/50' : 'bg-slate-400'"></span>
          <span>Modo:</span>
          <span :class="isAuthenticated ? 'text-emerald-700 dark:text-emerald-400 font-bold' : 'text-muted-foreground'">
            {{ isAuthenticated ? 'Morador Conectado' : 'Visitante' }}
          </span>
          <span class="text-[10px] text-muted-foreground underline ml-1">alternar</span>
        </button>

        <!-- Auth Action Buttons -->
        <template v-if="isAuthenticated">
          <div class="flex items-center gap-2 pl-2 border-l border-border">
            <div class="text-right hidden lg:block">
              <p class="text-xs font-bold leading-tight">{{ currentUser?.name }}</p>
              <p class="text-[11px] text-muted-foreground leading-none">{{ currentUser?.neighborhood }}</p>
            </div>
            <Button variant="ghost" size="sm" @click="logout" class="h-9 gap-1.5 text-xs text-muted-foreground hover:text-destructive">
              <LogOut class="h-4 w-4" />
              Sair
            </Button>
          </div>
        </template>
        <template v-else>
          <div class="flex items-center gap-2">
            <Button variant="outline" size="sm" @click="emit('open-auth', 'login')" class="h-9 gap-1.5">
              <LogIn class="h-4 w-4" />
              Entrar
            </Button>
            <Button variant="accent" size="sm" @click="emit('open-auth', 'register')" class="h-9 gap-1.5 text-xs">
              Criar Conta
            </Button>
          </div>
        </template>
      </div>

      <!-- Mobile Hamburger Button -->
      <button
        @click="mobileMenuOpen = !mobileMenuOpen"
        class="md:hidden p-2 rounded-lg text-muted-foreground hover:bg-muted"
        aria-label="Abrir menu"
      >
        <Menu v-if="!mobileMenuOpen" class="h-6 w-6" />
        <X v-else class="h-6 w-6" />
      </button>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div v-if="mobileMenuOpen" class="md:hidden border-b border-border bg-background/95 backdrop-blur-md px-4 py-4 space-y-4">
      <nav class="flex flex-col space-y-3 text-sm font-medium">
        <a href="#hero" @click="mobileMenuOpen = false" class="py-1 hover:text-primary">Início</a>
        <a href="#denuncias" @click="mobileMenuOpen = false" class="py-1 hover:text-primary">Denúncias em Alta</a>
        <a href="#reportar" @click="mobileMenuOpen = false" class="py-1 hover:text-primary">Fazer Denúncia</a>
        <a href="#sobre" @click="mobileMenuOpen = false" class="py-1 hover:text-primary">Sobre o Projeto</a>
      </nav>

      <div class="pt-3 border-t border-border flex flex-col gap-3">
        <button
          @click="toggleAuth"
          type="button"
          class="flex items-center justify-between px-3 py-2 rounded-lg border border-border bg-muted/60 text-xs font-semibold"
        >
          <span class="flex items-center gap-2">
            <span class="h-2 w-2 rounded-full" :class="isAuthenticated ? 'bg-emerald-500' : 'bg-slate-400'"></span>
            Status: {{ isAuthenticated ? 'Morador Conectado' : 'Visitante' }}
          </span>
          <span class="text-primary font-bold text-xs underline">Toque para alternar</span>
        </button>

        <div v-if="isAuthenticated" class="flex items-center justify-between">
          <div>
            <p class="text-xs font-bold">{{ currentUser?.name }}</p>
            <p class="text-[11px] text-muted-foreground">{{ currentUser?.neighborhood }}</p>
          </div>
          <Button variant="ghost" size="sm" @click="logout" class="text-xs text-destructive">
            <LogOut class="h-4 w-4 mr-1" /> Sair
          </Button>
        </div>
        <div v-else class="grid grid-cols-2 gap-2">
          <Button variant="outline" size="sm" @click="emit('open-auth', 'login'); mobileMenuOpen = false">
            Entrar
          </Button>
          <Button variant="accent" size="sm" @click="emit('open-auth', 'register'); mobileMenuOpen = false">
            Criar Conta
          </Button>
        </div>
      </div>
    </div>
  </header>
</template>
