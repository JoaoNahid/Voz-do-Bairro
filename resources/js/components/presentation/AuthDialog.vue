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
import { api, ApiError } from '@/lib/api';
import {
  LogIn,
  UserPlus,
  MapPin,
  CheckCircle,
  Search,
  Loader2,
  AlertTriangle,
  Info,
} from 'lucide-vue-next';

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

const sessionStore = useSessionStore();
const { toast } = useToast();

const currentTab = ref<'login' | 'register'>('login');

watch(
  () => props.open,
  (val) => {
    if (val && props.initialTab) {
      currentTab.value = props.initialTab;
    }
    if (val) {
      formErrors.value = {};
      generalError.value = null;
    }
  },
);

// Form Fields - Common & Auth
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const remember = ref(true);

// Form Fields - Register & Address
const name = ref('');
const cep = ref('');
const logradouro = ref('');
const numero = ref('');
const complemento = ref('');
const bairro = ref('');
const cidade = ref('');
const uf = ref('');

// OpenCEP UI states
const isCepLoading = ref(false);
const cepLookupMessage = ref<string | null>(null);
const isPilotRegion = ref<boolean | null>(null);
const isManualAddressUnlocked = ref(true);

// Error and Loading states
const isSubmitting = ref(false);
const formErrors = ref<Record<string, string[]>>({});
const generalError = ref<string | null>(null);

function formatCep(value: string) {
  const digits = value.replace(/\D/g, '').slice(0, 8);
  if (digits.length > 5) {
    return `${digits.slice(0, 5)}-${digits.slice(5)}`;
  }
  return digits;
}

function onCepInput(e: Event) {
  const target = e.target as HTMLInputElement;
  const raw = target.value;
  cep.value = formatCep(raw);

  const clean = cep.value.replace(/\D/g, '');
  if (clean.length === 8) {
    fetchCep(clean);
  }
}

async function fetchCep(cleanCep?: string) {
  const searchCep = (cleanCep || cep.value).replace(/\D/g, '');
  if (searchCep.length !== 8) {
    cepLookupMessage.value = 'Digite os 8 números do CEP.';
    return;
  }

  isCepLoading.value = true;
  cepLookupMessage.value = null;
  generalError.value = null;

  try {
    const data = await api.get(`/api/cep/${searchCep}`);
    if (data) {
      logradouro.value = data.logradouro || '';
      bairro.value = data.bairro || '';
      cidade.value = data.cidade || '';
      uf.value = data.uf || '';
      isPilotRegion.value = !!data.is_pilot_region;
      isManualAddressUnlocked.value = true;

      if (!data.is_pilot_region) {
        cepLookupMessage.value = `Nota: ${data.cidade}/${data.uf} está fora da região piloto de Pouso Alegre/MG. Seu cadastro será aceito normalmente!`;
      }
    }
  } catch (err: any) {
    isPilotRegion.value = null;
    isManualAddressUnlocked.value = true;
    cepLookupMessage.value =
      err?.message || 'CEP não localizado. Você pode preencher os campos de endereço manualmente.';
  } finally {
    isCepLoading.value = false;
  }
}

async function handleSubmit() {
  generalError.value = null;
  formErrors.value = {};
  isSubmitting.value = true;

  try {
    if (currentTab.value === 'login') {
      await sessionStore.login({
        email: email.value,
        password: password.value,
        remember: remember.value,
      });

      emit('update:open', false);

      toast({
        title: 'Sessão Iniciada!',
        description: `Bem-vindo(a) de volta, ${sessionStore.currentUser?.name || ''}!`,
        type: 'success',
      });
    } else {
      const cleanCep = cep.value.replace(/\D/g, '');
      await sessionStore.register({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: passwordConfirmation.value,
        cep: cleanCep,
        logradouro: logradouro.value,
        numero: numero.value,
        complemento: complemento.value || null,
        bairro: bairro.value,
        cidade: cidade.value,
        uf: uf.value,
      });

      emit('update:open', false);

      toast({
        title: 'Conta Criada com Sucesso!',
        description: `Bem-vindo(a), ${name.value}! Seu bairro foi registrado como ${bairro.value}.`,
        type: 'success',
      });
    }
  } catch (err: any) {
    if (err instanceof ApiError && err.errors) {
      formErrors.value = err.errors;
      generalError.value = 'Por favor, corrija os erros no formulário antes de prosseguir.';
    } else {
      generalError.value = err?.message || 'Ocorreu um erro ao processar sua requisição.';
    }
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
    <DialogContent class="sm:max-w-lg max-h-[90vh] overflow-y-auto">
      <DialogHeader>
        <div class="flex items-center gap-2 mb-1">
          <Badge variant="priority" class="text-xs uppercase tracking-wider">Acesso Cidadão</Badge>
          <span class="text-xs text-muted-foreground flex items-center gap-1">
            <MapPin class="h-3 w-3 text-amber-500" />
            <span v-if="isPilotRegion === false">Localidade Nacional</span>
            <span v-else>Pouso Alegre (Piloto)</span>
          </span>
        </div>
        <DialogTitle class="text-2xl font-extrabold text-foreground">
          {{ currentTab === 'login' ? 'Identifique-se para Participar' : 'Junte-se à Sua Comunidade' }}
        </DialogTitle>
        <DialogDescription>
          {{
            currentTab === 'login'
              ? 'Acesse sua conta para votar em demandas e acompanhar o seu bairro.'
              : 'Cadastre-se para relatar demandas de zeladoria urbana e fortalecer seu bairro.'
          }}
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

      <!-- General Error Alert -->
      <div
        v-if="generalError"
        class="rounded-lg bg-destructive/10 border border-destructive/20 p-3 text-xs text-destructive flex items-start gap-2"
      >
        <AlertTriangle class="h-4 w-4 mt-0.5 shrink-0" />
        <p>{{ generalError }}</p>
      </div>

      <!-- Form Body -->
      <form @submit.prevent="handleSubmit" class="space-y-4 pt-1">
        <!-- REGISTER FIELDS -->
        <template v-if="currentTab === 'register'">
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">Nome Completo</label>
            <Input v-model="name" placeholder="Ex: Maria de Fátima Oliveira" required />
            <p v-if="formErrors.name" class="text-xs text-destructive">{{ formErrors.name[0] }}</p>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">E-mail</label>
            <Input v-model="email" type="email" placeholder="seuemail@exemplo.com" required />
            <p v-if="formErrors.email" class="text-xs text-destructive">{{ formErrors.email[0] }}</p>
          </div>

          <!-- CEP Lookup Section -->
          <div class="space-y-1.5">
            <div class="flex items-center justify-between">
              <label class="text-xs font-bold text-foreground">CEP Residencial</label>
              <span class="text-[11px] text-muted-foreground">Validação OpenCEP</span>
            </div>
            <div class="flex gap-2">
              <Input
                :model-value="cep"
                @input="onCepInput"
                placeholder="00000-000"
                maxlength="9"
                required
                class="flex-1"
              />
              <Button
                type="button"
                variant="outline"
                size="sm"
                @click="() => fetchCep()"
                :disabled="isCepLoading"
                class="px-3 shrink-0"
                title="Buscar endereço pelo CEP"
              >
                <Loader2 v-if="isCepLoading" class="h-4 w-4 animate-spin" />
                <Search v-else class="h-4 w-4" />
              </Button>
            </div>
            <p v-if="formErrors.cep" class="text-xs text-destructive">{{ formErrors.cep[0] }}</p>

            <!-- CEP Status / Pilot Warning Alert -->
            <div
              v-if="cepLookupMessage"
              class="rounded-lg p-2.5 text-xs flex items-start gap-2"
              :class="
                isPilotRegion === false
                  ? 'bg-amber-500/10 border border-amber-500/20 text-amber-900 dark:text-amber-200'
                  : 'bg-muted text-muted-foreground'
              "
            >
              <Info class="h-4 w-4 mt-0.5 shrink-0" />
              <p>{{ cepLookupMessage }}</p>
            </div>
          </div>

          <!-- Address Fields -->
          <div class="grid grid-cols-3 gap-2">
            <div class="col-span-2 space-y-1.5">
              <label class="text-xs font-bold text-foreground">Logradouro (Rua/Av.)</label>
              <Input v-model="logradouro" placeholder="Nome da rua" required />
              <p v-if="formErrors.logradouro" class="text-xs text-destructive">{{ formErrors.logradouro[0] }}</p>
            </div>
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-foreground">Número</label>
              <Input v-model="numero" placeholder="123 ou S/N" required />
              <p v-if="formErrors.numero" class="text-xs text-destructive">{{ formErrors.numero[0] }}</p>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-2">
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-foreground">Complemento (opcional)</label>
              <Input v-model="complemento" placeholder="Apto, Casa 2..." />
            </div>
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-foreground">Bairro</label>
              <Input v-model="bairro" placeholder="Ex: Recanto dos Fernandes" required />
              <p v-if="formErrors.bairro" class="text-xs text-destructive">{{ formErrors.bairro[0] }}</p>
            </div>
          </div>

          <div class="grid grid-cols-3 gap-2">
            <div class="col-span-2 space-y-1.5">
              <label class="text-xs font-bold text-foreground">Cidade</label>
              <Input v-model="cidade" placeholder="Pouso Alegre" required />
              <p v-if="formErrors.cidade" class="text-xs text-destructive">{{ formErrors.cidade[0] }}</p>
            </div>
            <div class="space-y-1.5">
              <label class="text-xs font-bold text-foreground">UF</label>
              <Input v-model="uf" placeholder="MG" maxlength="2" required />
              <p v-if="formErrors.uf" class="text-xs text-destructive">{{ formErrors.uf[0] }}</p>
            </div>
          </div>

          <div class="space-y-1.5">
            <div class="flex items-center justify-between">
              <label class="text-xs font-bold text-foreground">Senha</label>
              <span class="text-[10px] text-muted-foreground">8+ chars, maiúscula, minúscula, nº e símbolo</span>
            </div>
            <Input v-model="password" type="password" placeholder="••••••••" required />
            <p v-if="formErrors.password" class="text-xs text-destructive">{{ formErrors.password[0] }}</p>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">Confirmar Senha</label>
            <Input v-model="passwordConfirmation" type="password" placeholder="••••••••" required />
          </div>
        </template>

        <!-- LOGIN FIELDS -->
        <template v-else>
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">E-mail</label>
            <Input v-model="email" type="email" placeholder="seuemail@exemplo.com" required />
            <p v-if="formErrors.email" class="text-xs text-destructive">{{ formErrors.email[0] }}</p>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">Senha</label>
            <Input v-model="password" type="password" placeholder="••••••••" required />
            <p v-if="formErrors.password" class="text-xs text-destructive">{{ formErrors.password[0] }}</p>
          </div>

          <!-- Remember Me Checkbox -->
          <div class="flex items-center space-x-2 pt-1">
            <input
              type="checkbox"
              id="remember"
              v-model="remember"
              class="h-4 w-4 rounded border-input text-primary focus:ring-primary accent-primary cursor-pointer"
            />
            <label for="remember" class="text-xs font-medium text-foreground cursor-pointer select-none">
              Lembrar de mim (manter conectado neste navegador)
            </label>
          </div>
        </template>

        <DialogFooter class="pt-2">
          <Button type="button" variant="outline" @click="emit('update:open', false)">
            Cancelar
          </Button>
          <Button type="submit" variant="accent" :disabled="isSubmitting" class="font-bold gap-2">
            <Loader2 v-if="isSubmitting" class="h-4 w-4 animate-spin" />
            <CheckCircle v-else class="h-4 w-4" />
            {{ currentTab === 'login' ? 'Entrar e Participar' : 'Confirmar Cadastro' }}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
