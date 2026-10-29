<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  class?: HTMLAttributes['class'];
}>();
</script>

<template>
  <h3 :class="cn('text-lg font-bold leading-tight tracking-tight text-foreground', props.class)">
    <slot />
  </h3>
</template>
