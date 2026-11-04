<script setup lang="ts">
import { ref, watch, computed } from 'vue';
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
  User,
  KeyRound,
  Trash2,
  MapPin,
  Search,
  Loader2,
  CheckCircle,
  AlertTriangle,
  Info,
  ShieldAlert,
} from 'lucide-vue-next';

const props = defineProps<{
  open: boolean;
}>();

const emit = defineEmits<{
  (e: 'update:open', val: boolean): void;
}>();

const sessionStore = useSessionStore();
const { toast } = useToast();

const currentTab = ref<'profile' | 'password' | 'danger'>('profile');

// Form States - Profile
const name = ref('');
const email = ref('');
const initialEmail = ref('');
const currentPasswordForEmail = ref('');
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

// Form States - Password Update
const currentPassword = ref('');
const newPassword = ref('');
const newPasswordConfirmation = ref('');

// Form States - Delete Account
const deletePassword = ref('');
const deleteConfirmOpen = ref(false);

// Error and Loading states
const isSubmitting = ref(false);
const formErrors = ref<Record<string, string[]>>({});
const generalError = ref<string | null>(null);

const isEmailChanged = computed(() => {
  return email.value.trim().toLowerCase() !== initialEmail.value.trim().toLowerCase();
});

watch(
  () => props.open,
  (val) => {
    if (val && sessionStore.currentUser) {
      currentTab.value = 'profile';
      formErrors.value = {};
      generalError.value = null;

      const user = sessionStore.currentUser;
      name.value = user.name || '';
      email.value = user.email || '';
      initialEmail.value = user.email || '';
      currentPasswordForEmail.value = '';

      const addr = user.address;
      cep.value = addr?.cep ? formatCep(addr.cep) : '';
      logradouro.value = addr?.logradouro || '';
      numero.value = addr?.numero || '';
      complemento.value = addr?.complemento || '';
      bairro.value = addr?.bairro || user.neighborhood || '';
      cidade.value = addr?.cidade || '';
      uf.value = addr?.uf || '';
      isPilotRegion.value = addr?.is_pilot_region ?? null;

      currentPassword.value = '';
      newPassword.value = '';
      newPasswordConfirmation.value = '';
      deletePassword.value = '';
      deleteConfirmOpen.value = false;
    }
  },
);

function formatCep(value: string) {
  const digits = value.replace(/\D/g, '').slice(0, 8);
  if (digits.length > 5) {
    return `${digits.slice(0, 5)}-${digits.slice(5)}`;
  }
  return digits;
}

function onCepInput(e: Event) {
  const target = e.target as HTMLInputElement;
  cep.value = formatCep(target.value);

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

  try {
    const data = await api.get(`/api/cep/${searchCep}`);
    if (data) {
      logradouro.value = data.logradouro || '';
      bairro.value = data.bairro || '';
      cidade.value = data.cidade || '';
      uf.value = data.uf || '';
      isPilotRegion.value = !!data.is_pilot_region;

      if (!data.is_pilot_region) {
        cepLookupMessage.value = `Nota: ${data.cidade}/${data.uf} está fora da região piloto de Pouso Alegre/MG.`;
      }
    }
  } catch (err: any) {
    isPilotRegion.value = null;
    cepLookupMessage.value = err?.message || 'CEP não localizado. Preencha manualmente.';
  } finally {
    isCepLoading.value = false;
  }
}

async function handleUpdateProfile() {
  generalError.value = null;
  formErrors.value = {};
  isSubmitting.value = true;

  try {
    const cleanCep = cep.value.replace(/\D/g, '');
    const payload: any = {
      name: name.value,
      email: email.value,
      cep: cleanCep,
      logradouro: logradouro.value,
      numero: numero.value,
      complemento: complemento.value || null,
      bairro: bairro.value,
      cidade: cidade.value,
      uf: uf.value,
    };

    if (isEmailChanged.value) {
      payload.current_password = currentPasswordForEmail.value;
    }

    await sessionStore.updateProfile(payload);
    initialEmail.value = email.value;
    currentPasswordForEmail.value = '';

    toast({
      title: 'Perfil Atualizado!',
      description: 'Seus dados cadastrais e de endereço foram atualizados com sucesso.',
      type: 'success',
    });
  } catch (err: any) {
    if (err instanceof ApiError && err.errors) {
      formErrors.value = err.errors;
      generalError.value = 'Por favor, confira os erros no formulário.';
    } else {
      generalError.value = err?.message || 'Erro ao atualizar dados.';
    }
  } finally {
    isSubmitting.value = false;
  }
}

async function handleUpdatePassword() {
  generalError.value = null;
  formErrors.value = {};
  isSubmitting.value = true;

  try {
    await sessionStore.updatePassword({
      current_password: currentPassword.value,
      password: newPassword.value,
      password_confirmation: newPasswordConfirmation.value,
    });

    currentPassword.value = '';
    newPassword.value = '';
    newPasswordConfirmation.value = '';

    toast({
      title: 'Senha Alterada com Sucesso!',
      description: 'Sua nova senha de alta complexidade já está ativa.',
      type: 'success',
    });
  } catch (err: any) {
    if (err instanceof ApiError && err.errors) {
      formErrors.value = err.errors;
    } else {
      generalError.value = err?.message || 'Erro ao alterar senha.';
    }
  } finally {
    isSubmitting.value = false;
  }
}

async function handleDeleteAccount() {
  generalError.value = null;
  formErrors.value = {};
  isSubmitting.value = true;

  try {
    await sessionStore.deleteAccount({
      current_password: deletePassword.value,
    });

    emit('update:open', false);

    toast({
      title: 'Conta Excluída com Sucesso',
      description: 'Seus dados e registros de endereço foram definitivamente removidos.',
      type: 'success',
    });
  } catch (err: any) {
    if (err instanceof ApiError && err.errors) {
      formErrors.value = err.errors;
    } else {
      generalError.value = err?.message || 'Senha incorreta ou erro ao excluir conta.';
    }
  } finally {
    isSubmitting.value = false;
  }
}
</script>

<template>
  <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
    <DialogContent class="sm:max-w-xl max-h-[90vh] overflow-y-auto">
      <DialogHeader>
        <div class="flex items-center gap-2 mb-1">
          <Badge variant="outline" class="text-xs uppercase tracking-wider">Gestão do Munícipe</Badge>
          <span class="text-xs text-muted-foreground flex items-center gap-1">
            <MapPin class="h-3 w-3 text-amber-500" />
            <span>{{ sessionStore.currentUser?.neighborhood || 'Seu Bairro' }}</span>
          </span>
        </div>
        <DialogTitle class="text-2xl font-extrabold text-foreground">
          Meus Dados Cadastrais
        </DialogTitle>
        <DialogDescription>
          Visualize e atualize suas informações de contato, localização residencial ou credenciais de segurança.
        </DialogDescription>
      </DialogHeader>

      <!-- Navigation Tabs -->
      <div class="grid grid-cols-3 rounded-xl bg-muted p-1 text-xs font-semibold text-muted-foreground">
        <button
          type="button"
          @click="currentTab = 'profile'"
          class="rounded-lg py-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
          :class="currentTab === 'profile' ? 'bg-background text-foreground shadow-sm font-bold' : 'hover:text-foreground'"
        >
          <User class="h-4 w-4" />
          Dados & Endereço
        </button>
        <button
          type="button"
          @click="currentTab = 'password'"
          class="rounded-lg py-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer"
          :class="currentTab === 'password' ? 'bg-background text-foreground shadow-sm font-bold' : 'hover:text-foreground'"
        >
          <KeyRound class="h-4 w-4" />
          Alterar Senha
        </button>
        <button
          type="button"
          @click="currentTab = 'danger'"
          class="rounded-lg py-2 transition-all flex items-center justify-center gap-1.5 cursor-pointer text-destructive/80 hover:text-destructive"
          :class="currentTab === 'danger' ? 'bg-background text-destructive shadow-sm font-bold' : ''"
        >
          <Trash2 class="h-4 w-4" />
          Excluir Conta
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

      <!-- TAB 1: DADOS & ENDEREÇO -->
      <form v-if="currentTab === 'profile'" @submit.prevent="handleUpdateProfile" class="space-y-4 pt-1">
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">Nome Completo</label>
          <Input v-model="name" placeholder="Nome completo" required />
          <p v-if="formErrors.name" class="text-xs text-destructive">{{ formErrors.name[0] }}</p>
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">E-mail Cadastrado</label>
          <Input v-model="email" type="email" placeholder="seuemail@exemplo.com" required />
          <p v-if="formErrors.email" class="text-xs text-destructive">{{ formErrors.email[0] }}</p>
        </div>

        <!-- Conditional Password prompt if email is being changed -->
        <div
          v-if="isEmailChanged"
          class="rounded-lg border border-amber-500/30 bg-amber-500/10 p-3 space-y-2 transition-all"
        >
          <div class="flex items-center gap-2 text-xs font-bold text-amber-800 dark:text-amber-200">
            <ShieldAlert class="h-4 w-4 text-amber-600" />
            Confirmação de Segurança Requerida
          </div>
          <p class="text-xs text-muted-foreground">
            Você está alterando seu e-mail de acesso. Por segurança, confirme sua <strong>senha atual</strong>:
          </p>
          <Input
            v-model="currentPasswordForEmail"
            type="password"
            placeholder="Digite sua senha atual"
            required
          />
          <p v-if="formErrors.current_password" class="text-xs text-destructive">
            {{ formErrors.current_password[0] }}
          </p>
        </div>

        <!-- CEP and Address Section -->
        <div class="space-y-1.5 pt-1">
          <div class="flex items-center justify-between">
            <label class="text-xs font-bold text-foreground">CEP Residencial</label>
            <span class="text-[11px] text-muted-foreground">Busca OpenCEP</span>
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

          <div
            v-if="cepLookupMessage"
            class="rounded-lg p-2.5 text-xs flex items-start gap-2 bg-muted text-muted-foreground"
          >
            <Info class="h-4 w-4 mt-0.5 shrink-0" />
            <p>{{ cepLookupMessage }}</p>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
          <div class="col-span-2 space-y-1.5">
            <label class="text-xs font-bold text-foreground">Logradouro</label>
            <Input v-model="logradouro" placeholder="Rua/Avenida" required />
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
            <label class="text-xs font-bold text-foreground">Complemento</label>
            <Input v-model="complemento" placeholder="Apto, Casa 2..." />
          </div>
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">Bairro</label>
            <Input v-model="bairro" placeholder="Bairro" required />
            <p v-if="formErrors.bairro" class="text-xs text-destructive">{{ formErrors.bairro[0] }}</p>
          </div>
        </div>

        <div class="grid grid-cols-3 gap-2">
          <div class="col-span-2 space-y-1.5">
            <label class="text-xs font-bold text-foreground">Cidade</label>
            <Input v-model="cidade" placeholder="Cidade" required />
            <p v-if="formErrors.cidade" class="text-xs text-destructive">{{ formErrors.cidade[0] }}</p>
          </div>
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">UF</label>
            <Input v-model="uf" placeholder="MG" maxlength="2" required />
            <p v-if="formErrors.uf" class="text-xs text-destructive">{{ formErrors.uf[0] }}</p>
          </div>
        </div>

        <DialogFooter class="pt-2">
          <Button type="button" variant="outline" @click="emit('update:open', false)">
            Fechar
          </Button>
          <Button type="submit" variant="accent" :disabled="isSubmitting" class="font-bold gap-2">
            <Loader2 v-if="isSubmitting" class="h-4 w-4 animate-spin" />
            <CheckCircle v-else class="h-4 w-4" />
            Salvar Alterações
          </Button>
        </DialogFooter>
      </form>

      <!-- TAB 2: ALTERAR SENHA -->
      <form v-else-if="currentTab === 'password'" @submit.prevent="handleUpdatePassword" class="space-y-4 pt-1">
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">Senha Atual</label>
          <Input v-model="currentPassword" type="password" placeholder="Digite sua senha atual" required />
          <p v-if="formErrors.current_password" class="text-xs text-destructive">{{ formErrors.current_password[0] }}</p>
        </div>

        <div class="space-y-1.5">
          <div class="flex items-center justify-between">
            <label class="text-xs font-bold text-foreground">Nova Senha</label>
            <span class="text-[10px] text-muted-foreground">8+ caracteres com maiúscula, minúscula, nº e símbolo</span>
          </div>
          <Input v-model="newPassword" type="password" placeholder="Nova senha complexa" required />
          <p v-if="formErrors.password" class="text-xs text-destructive">{{ formErrors.password[0] }}</p>
        </div>

        <div class="space-y-1.5">
          <label class="text-xs font-bold text-foreground">Confirmar Nova Senha</label>
          <Input v-model="newPasswordConfirmation" type="password" placeholder="Confirme a nova senha" required />
        </div>

        <DialogFooter class="pt-2">
          <Button type="button" variant="outline" @click="emit('update:open', false)">
            Cancelar
          </Button>
          <Button type="submit" variant="accent" :disabled="isSubmitting" class="font-bold gap-2">
            <Loader2 v-if="isSubmitting" class="h-4 w-4 animate-spin" />
            <KeyRound v-else class="h-4 w-4" />
            Atualizar Senha
          </Button>
        </DialogFooter>
      </form>

      <!-- TAB 3: ZONA DE PERIGO (EXCLUIR CONTA) -->
      <div v-else-if="currentTab === 'danger'" class="space-y-4 pt-1">
        <div class="rounded-xl border border-destructive/30 bg-destructive/10 p-4 space-y-3">
          <div class="flex items-center gap-2 text-sm font-bold text-destructive">
            <AlertTriangle class="h-5 w-5" />
            Exclusão Definitiva de Conta e Endereço
          </div>
          <p class="text-xs text-destructive/90 leading-relaxed">
            Esta é uma ação destrutiva irreversível. Ao confirmar, seu registro de munícipe e o respectivo endereço residencial serão excluídos permanentemente do banco de dados, e sua sessão será encerrada.
          </p>
        </div>

        <form @submit.prevent="handleDeleteAccount" class="space-y-3">
          <div class="space-y-1.5">
            <label class="text-xs font-bold text-foreground">Confirme com sua Senha Atual</label>
            <Input
              v-model="deletePassword"
              type="password"
              placeholder="Digite sua senha para autorizar a exclusão"
              required
            />
            <p v-if="formErrors.current_password" class="text-xs text-destructive">{{ formErrors.current_password[0] }}</p>
          </div>

          <DialogFooter class="pt-2">
            <Button type="button" variant="outline" @click="emit('update:open', false)">
              Cancelar
            </Button>
            <Button type="submit" variant="destructive" :disabled="isSubmitting" class="font-bold gap-2">
              <Loader2 v-if="isSubmitting" class="h-4 w-4 animate-spin" />
              <Trash2 v-else class="h-4 w-4" />
              Excluir Minha Conta Definitivamente
            </Button>
          </DialogFooter>
        </form>
      </div>
    </DialogContent>
  </Dialog>
</template>
