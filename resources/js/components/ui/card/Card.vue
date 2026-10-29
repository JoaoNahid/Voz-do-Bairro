<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  class?: HTMLAttributes['class'];
}>();
</script>

<template>
  <div :class="cn('rounded-xl border border-border/80 bg-card text-card-foreground shadow-sm transition-all hover:shadow-md', props.class)">
    <slot />
  </div>
</template>
