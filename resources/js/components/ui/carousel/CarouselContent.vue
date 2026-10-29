<script setup lang="ts">
import { inject } from 'vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  class?: string;
}>();

const { emblaRef } = inject<any>('emblaCarousel', {});
</script>

<template>
  <div ref="emblaRef" class="overflow-hidden">
    <div :class="cn('flex -ml-4', props.class)">
      <slot />
    </div>
  </div>
</template>
