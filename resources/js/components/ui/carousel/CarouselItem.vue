<script setup lang="ts">
import { cn } from '@/lib/utils';

const props = defineProps<{
  class?: string;
}>();
</script>

<template>
  <div
    role="group"
    aria-roledescription="slide"
    :class="cn('min-w-0 shrink-0 grow-0 basis-full pl-4 md:basis-1/2 lg:basis-1/3', props.class)"
  >
    <slot />
  </div>
</template>
