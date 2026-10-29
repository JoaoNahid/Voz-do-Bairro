<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';
import { DialogTitle, useForwardProps } from 'radix-vue';
import { cn } from '@/lib/utils';

const props = defineProps<{
  asChild?: boolean;
  as?: any;
  class?: HTMLAttributes['class'];
}>();

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;
  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <DialogTitle
    v-bind="forwardedProps"
    :class="cn('text-xl font-bold leading-none tracking-tight text-foreground', props.class)"
  >
    <slot />
  </DialogTitle>
</template>
