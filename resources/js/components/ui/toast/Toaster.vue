<script setup lang="ts">
import { useToast } from './use-toast';
import { CheckCircle2, AlertCircle, Info, X } from 'lucide-vue-next';

const { toasts, dismiss } = useToast();
</script>

<template>
  <div class="fixed bottom-4 right-4 z-50 flex flex-col gap-2 max-w-md w-full pointer-events-none p-4">
    <TransitionGroup
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-y-3 opacity-0 scale-95"
      enter-to-class="transform translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-for="t in toasts"
        :key="t.id"
        class="pointer-events-auto flex items-start gap-3 rounded-xl border border-border/80 bg-background/95 backdrop-blur-md p-4 shadow-xl"
        :class="{
          'border-emerald-500/30 bg-emerald-50/90 dark:bg-emerald-950/40 text-emerald-900 dark:text-emerald-200': t.type === 'success',
          'border-destructive/30 bg-destructive/10 text-destructive': t.type === 'destructive',
          'border-primary/20 text-foreground': t.type === 'default',
        }"
      >
        <CheckCircle2 v-if="t.type === 'success'" class="h-5 w-5 text-emerald-600 mt-0.5 shrink-0" />
        <AlertCircle v-else-if="t.type === 'destructive'" class="h-5 w-5 text-destructive mt-0.5 shrink-0" />
        <Info v-else class="h-5 w-5 text-primary mt-0.5 shrink-0" />

        <div class="flex-1 space-y-0.5">
          <p v-if="t.title" class="text-sm font-bold leading-tight">{{ t.title }}</p>
          <p v-if="t.description" class="text-xs text-muted-foreground leading-relaxed">{{ t.description }}</p>
        </div>

        <button
          @click="dismiss(t.id)"
          class="rounded-md p-1 opacity-60 hover:opacity-100 hover:bg-black/5 transition-opacity"
        >
          <X class="h-3.5 w-3.5" />
        </button>
      </div>
    </TransitionGroup>
  </div>
</template>
