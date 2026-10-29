<script setup lang="ts">
import { ref, watch } from 'vue';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { useSessionStore } from '@/stores/sessionStore';
import { useToast } from '@/components/ui/toast';
import { LogIn, UserPlus, MapPin, CheckCircle, Shield } from 'lucide-vue-next';

const props = withDefaults(
  defineProps<{
    open: boolean;
    initialTab?: 'login' | 'register';
  }>(),
  {
    open: false,
    initialTab: 'login',
  },
);

const emit = defineEmits<{
  (e: 'update:open', val: boolean): void;
}>();

const { login } = useSessionStore();
const { toast } = useToast();

const currentTab = ref<'login' | 'register'>('login');

watch(
  () => props.open,
  (val) => {
    if (val && props.initialTab) {
      currentTab.value = props.initialTab;
    }
  },
);

// Form Fields
const name = ref('Carlos Eduardo Silva');
const email = ref('carlos.silva@exemplo.com');
const neighborhood = ref('Recanto dos Fernandes');
const password = ref('********');

function handleSubmit() {
  login({
    name: name.value || 'Morador Cidadão',
    email: email.value,
    neighborhood: neighborhood.value,
    city: 'Pouso Alegre, MG',
  });

  emit('update:open', false);

  toast({
    title: currentTab.value === 'login' ? 'Sessão Iniciada com Sucesso!' : 'Conta Comunitária Criada!',
    description: `Bem-vindo(a), ${name.value}! Agora você pode votar e enviar denúncias para o bairro ${neighborhood.value}.`,
    type: 'success',
  });
}
</script>

<template>
  <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <div class="flex items-center gap-2 mb-1">
          <Badge variant="priority" class="text-xs uppercase tracking-wider">Acesso Cidadão</Badge>
          <span class="text-xs text-muted-foreground flex items-center gap-1">
            <MapPin class="h-3 w-3 text-amber-500" /> Pouso Alegre
          </span>
        </div>
        <DialogTitle class="text-2xl font-extrabold text-foreground">
          {{ currentTab === 'login' ? 'Identifique-se para Participar' : 'Junte-se à Sua Comunidade' }}
        </DialogTitle>
        <DialogDescription>
          Para votar na relevância dos relatos e registrar problemas da sua rua, precisamos saber qual bairro você representa.
        </DialogDescription>
      </DialogHeader>

      <!-- Tabs Header -->
      <div class="grid grid-cols-2 rounded-xl bg-muted p-1 text-sm font-semibold text-muted-foreground">
        <button
          type="button"
          @click="currentTab = 'login'"
          class="rounded-lg py-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
          :class="currentTab === 'login' ? 'bg-background text-foreground shadow-sm font-bold' : 'hover:text-foreground'"
        >
          <LogIn class="h-4 w-4" />
          Entrar
        </button>
        <button
          type="button"
          @click="currentTab = 'register'"
          class="rounded-lg py-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
          :class="currentTab === 'register' ? 'bg-background text-foreground shadow-sm font-bold' : 'hover:text-foreground'"
        >
          <UserPlus class="h-4 w-4" />
          Criar Conta
        </button>
      </div>

      <!-- Form Body -->
      <form @submit.prevent="handleSubmit" class="space-y-4 pt-2">
        <div v-if="currentTab === 'register'" class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">Nome Completo</label>
          <Input v-model="name" placeholder="Ex: Maria de Fátima Oliveira" required />
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">E-mail ou Telefone</label>
          <Input v-model="email" type="email" placeholder="seuemail@exemplo.com" required />
        </div>

        <div v-if="currentTab === 'register'" class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">Seu Bairro em Pouso Alegre</label>
          <select
            v-model="neighborhood"
            class="flex h-11 w-full rounded-lg border border-input bg-background px-3.5 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 transition-colors cursor-pointer"
          >
            <option value="Recanto dos Fernandes">Recanto dos Fernandes (Bairro Piloto)</option>
            <option value="Centro">Centro</option>
            <option value="São Geraldo">São Geraldo</option>
            <option value="Fátima">Fátima</option>
            <option value="Árvore Grande">Árvore Grande</option>
            <option value="Pão de Açúcar">Pão de Açúcar</option>
            <option value="Outro Bairro">Outro Bairro de Pouso Alegre</option>
          </select>
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">Senha</label>
          <Input v-model="password" type="password" placeholder="••••••••" required />
        </div>

        <div class="rounded-lg bg-amber-500/10 border border-amber-500/20 p-3 text-xs text-amber-900 dark:text-amber-200 flex items-start gap-2">
          <Shield class="h-4 w-4 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0" />
          <p>
            <strong>Demonstração da Apresentação Visual:</strong> Qualquer informação preenchida ativará o modo de Morador Conectado para teste imediato de votos e denúncias.
          </p>
        </div>

        <DialogFooter class="pt-2">
          <Button type="button" variant="outline" @click="emit('update:open', false)">
            Cancelar
          </Button>
          <Button type="submit" variant="accent" class="font-bold gap-2">
            <CheckCircle class="h-4 w-4" />
            {{ currentTab === 'login' ? 'Entrar e Participar' : 'Confirmar Cadastro' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
