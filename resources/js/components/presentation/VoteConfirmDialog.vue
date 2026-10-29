<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import type { Report } from '@/stores/sessionStore';
import { ThumbsUp, ThumbsDown, CheckCircle2, AlertCircle, MapPin } from 'lucide-vue-next';

const props = defineProps<{
  open: boolean;
  report: Report | null;
  voteType: 'relevant' | 'irrelevant';
}>();

const emit = defineEmits<{
  (e: 'update:open', val: boolean): void;
  (e: 'confirm'): void;
}>();
</script>

<template>
  <Dialog :open="open" @update:open="(val) => emit('update:open', val)">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <div class="flex items-center gap-2 mb-1">
          <Badge :variant="voteType === 'relevant' ? 'priority' : 'secondary'">
            {{ voteType === 'relevant' ? 'Voto de Relevância' : 'Voto de Irrelevância' }}
          </Badge>
          <span class="text-xs text-muted-foreground flex items-center gap-1">
            <MapPin class="h-3 w-3 text-amber-500" /> {{ report?.neighborhood }}
          </span>
        </div>
        <DialogTitle class="text-xl font-bold text-foreground">
          Confirmar Voto Comunitário?
        </DialogTitle>
        <DialogDescription>
          Seu voto influencia diretamente o quórum de 60% necessário para tornar esta ocorrência prioridade pública no município.
        </DialogDescription>
      </DialogHeader>

      <div v-if="report" class="rounded-xl border border-border/80 bg-muted/40 p-4 space-y-2.5 my-2">
        <div class="flex items-center gap-2">
          <Badge variant="outline" class="text-[11px] font-semibold">{{ report.categoryLabel }}</Badge>
          <span class="text-xs text-muted-foreground">{{ report.street }}</span>
        </div>
        <p class="text-sm font-semibold text-foreground leading-snug">
          "{{ report.title }}"
        </p>
        <div class="flex items-center justify-between text-xs text-muted-foreground pt-1 border-t border-border/40">
          <span>Quórum atual: <strong class="text-foreground">{{ report.quorumPercentage }}%</strong> dos moradores</span>
          <span :class="report.quorumPercentage >= 60 ? 'text-amber-600 dark:text-amber-400 font-bold' : ''">
            {{ report.quorumPercentage >= 60 ? '★ Prioridade Pública' : 'Aguardando quórum' }}
          </span>
        </div>
      </div>

      <DialogFooter class="pt-2">
        <Button variant="outline" @click="emit('update:open', false)">
          Cancelar
        </Button>
        <Button
          :variant="voteType === 'relevant' ? 'accent' : 'secondary'"
          @click="emit('confirm')"
          class="font-bold gap-2"
        >
          <ThumbsUp v-if="voteType === 'relevant'" class="h-4 w-4" />
          <ThumbsDown v-else class="h-4 w-4" />
          Confirmar Voto
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
