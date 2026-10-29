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
import { Textarea } from '@/components/ui/textarea';
import { useToast } from '@/components/ui/toast';
import {
  Camera,
  MapPin,
  Tag,
  AlertTriangle,
  UploadCloud,
  CheckCircle2,
} from 'lucide-vue-next';

interface InitialData {
  title?: string;
  description?: string;
  category?: 'vias' | 'saneamento' | 'iluminacao' | 'limpeza';
}

const props = withDefaults(
  defineProps<{
    open: boolean;
    initialData?: InitialData;
  }>(),
  {
    open: false,
    initialData: () => ({ title: '', description: '', category: 'vias' }),
  },
);

const emit = defineEmits<{
  (e: 'update:open', val: boolean): void;
  (e: 'submit', report: any): void;
}>();

const { toast } = useToast();

const title = ref(props.initialData?.title || '');
const description = ref(props.initialData?.description || '');
const category = ref<'vias' | 'saneamento' | 'iluminacao' | 'limpeza'>('vias');
const neighborhood = ref('Recanto dos Fernandes');
const street = ref('');
const hasSimulatedPhoto = ref(false);

watch(
  () => props.initialData,
  (val) => {
    if (val) {
      if (val.title) title.value = val.title;
      if (val.description) description.value = val.description;
      if (val.category) category.value = val.category;
    }
  },
  { deep: true, immediate: true },
);

const neighborhoods = [
  'Recanto dos Fernandes',
  'Centro',
  'São Geraldo',
  'Fátima',
  'Árvore Grande',
  'Pouso do Campo',
  'Jardim Guanabara',
  'São João',
  'Aterrado',
];

const categories = [
  { id: 'vias', label: 'Vias Públicas', icon: '🛣️' },
  { id: 'saneamento', label: 'Saneamento & Água', icon: '🚰' },
  { id: 'iluminacao', label: 'Iluminação Pública', icon: '💡' },
  { id: 'limpeza', label: 'Limpeza & Praças', icon: '🌳' },
] as const;

function handleClose() {
  emit('update:open', false);
}

function handlePhotoSimulation() {
  hasSimulatedPhoto.value = true;
  toast({
    title: 'Foto anexada com sucesso!',
    description: 'Evidência fotográfica adicionada ao relato.',
    variant: 'default',
  });
}

function handleSubmit() {
  if (!title.value.trim()) {
    toast({
      title: 'Título obrigatório',
      description: 'Por favor, informe um título objetivo para a denúncia.',
      variant: 'destructive',
    });
    return;
  }

  const categoryLabelMap = {
    vias: 'Vias Públicas',
    saneamento: 'Saneamento & Água',
    iluminacao: 'Iluminação Pública',
    limpeza: 'Limpeza & Praças',
  };

  const newReport = {
    id: `rep-${Date.now().toString().slice(-4)}`,
    title: title.value.trim(),
    description: description.value.trim(),
    category: category.value,
    categoryLabel: categoryLabelMap[category.value],
    neighborhood: neighborhood.value,
    street: street.value.trim() || 'Localidade informada no relato',
    date: 'Agora mesmo',
    imageUrl: hasSimulatedPhoto.value
      ? '/images/reports/buraco-rua.jpg'
      : '/images/reports/buraco-rua.jpg',
    votesRelevant: 1,
    votesIrrelevant: 0,
    quorumPercentage: 1,
    isPublicPriority: false,
    userVote: 'relevant',
  };

  emit('submit', newReport);
  emit('update:open', false);

  toast({
    title: '🎉 Denúncia Publicada com Sucesso!',
    description: `Seu relato no bairro ${neighborhood.value} já está disponível para o quórum de votos comunitários.`,
    variant: 'default',
  });

  // Reset form
  title.value = '';
  description.value = '';
  street.value = '';
  hasSimulatedPhoto.value = false;
}
</script>

<template>
  <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
    <DialogContent class="sm:max-w-[580px] max-h-[90vh] overflow-y-auto">
      <DialogHeader>
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary/10 text-primary w-fit mb-1 border border-primary/20">
          <AlertTriangle class="w-3.5 h-3.5" />
          <span>Novo Relato de Zeladoria Urbana</span>
        </div>
        <DialogTitle class="text-xl sm:text-2xl font-bold text-slate-900">
          Registrar Demanda Cívica
        </DialogTitle>
        <DialogDescription class="text-xs sm:text-sm text-slate-600">
          Preencha as informações detalhadas para que os moradores de Pouso Alegre possam apoiar e priorizar este problema.
        </DialogDescription>
      </DialogHeader>

      <form @submit.prevent="handleSubmit" class="space-y-4 py-2">
        <!-- Eixo Urbano (Category) -->
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
            <Tag class="w-3.5 h-3.5 text-primary" />
            Eixo Urbano Prioritário
          </label>
          <div class="grid grid-cols-2 gap-2">
            <button
              v-for="cat in categories"
              :key="cat.id"
              type="button"
              class="flex items-center gap-2 p-2.5 rounded-lg border text-left text-xs font-medium transition-all"
              :class="
                category === cat.id
                  ? 'border-primary bg-primary/5 text-primary font-bold shadow-xs ring-1 ring-primary/30'
                  : 'border-slate-200 hover:border-slate-300 text-slate-700 bg-white'
              "
              @click="category = cat.id"
            >
              <span class="text-base">{{ cat.icon }}</span>
              <span>{{ cat.label }}</span>
            </button>
          </div>
        </div>

        <!-- Título -->
        <div class="space-y-1.5">
          <label for="full-title" class="text-xs font-bold text-slate-700">
            Título do Problema *
          </label>
          <Input
            id="full-title"
            v-model="title"
            placeholder="Ex: Falta de iluminação pública na esquina da escola"
            required
            class="h-10 text-sm"
          />
        </div>

        <!-- Bairro & Rua -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div class="space-y-1.5">
            <label for="full-neighborhood" class="text-xs font-bold text-slate-700 flex items-center gap-1">
              <MapPin class="w-3.5 h-3.5 text-primary" />
              Bairro (Pouso Alegre)
            </label>
            <select
              id="full-neighborhood"
              v-model="neighborhood"
              class="w-full h-10 px-3 py-2 text-xs sm:text-sm rounded-md border border-input bg-background focus:outline-none focus:ring-2 focus:ring-ring text-slate-800"
            >
              <option v-for="nb in neighborhoods" :key="nb" :value="nb">
                {{ nb }}
              </option>
            </select>
          </div>

          <div class="space-y-1.5">
            <label for="full-street" class="text-xs font-bold text-slate-700">
              Rua / Ponto de Referência
            </label>
            <Input
              id="full-street"
              v-model="street"
              placeholder="Ex: Rua das Flores, próx. ao nº 50"
              class="h-10 text-sm"
            />
          </div>
        </div>

        <!-- Descrição -->
        <div class="space-y-1.5">
          <label for="full-desc" class="text-xs font-bold text-slate-700">
            Descrição e Impacto Comunitário
          </label>
          <Textarea
            id="full-desc"
            v-model="description"
            rows="3"
            placeholder="Descreva há quanto tempo o problema persiste e quem é afetado (pedestres, cadeirantes, tráfego local)..."
            class="text-sm"
          />
        </div>

        <!-- Simulação de Upload de Foto -->
        <div class="space-y-1.5">
          <label class="text-xs font-bold text-slate-700 flex items-center gap-1">
            <Camera class="w-3.5 h-3.5 text-primary" />
            Foto do Local (Evidência)
          </label>
          <div
            class="border-2 border-dashed rounded-lg p-4 text-center cursor-pointer transition-colors"
            :class="hasSimulatedPhoto ? 'border-emerald-400 bg-emerald-50/50' : 'border-slate-300 hover:border-primary/50 bg-slate-50/50'"
            @click="handlePhotoSimulation"
          >
            <div v-if="hasSimulatedPhoto" class="flex items-center justify-center gap-2 text-emerald-800 text-xs font-semibold">
              <CheckCircle2 class="w-4 h-4 text-emerald-600" />
              <span>Foto anexada com sucesso: <strong>evidencia-denuncia.jpg</strong></span>
            </div>
            <div v-else class="flex flex-col items-center justify-center gap-1 text-slate-500">
              <UploadCloud class="w-6 h-6 text-slate-400" />
              <p class="text-xs font-medium">Clique para anexar imagem da situação (simulação)</p>
              <span class="text-[10px] text-slate-400">JPG, PNG até 5MB</span>
            </div>
          </div>
        </div>

        <DialogFooter class="pt-3 border-t border-slate-100 flex flex-col-reverse sm:flex-row gap-2">
          <Button type="button" variant="outline" @click="handleClose">
            Cancelar
          </Button>
          <Button type="submit" variant="accent" class="font-bold gap-1.5">
            <CheckCircle2 class="w-4 h-4" />
            Publicar Denúncia
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  </Dialog>
</template>
