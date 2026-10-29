<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';
import { DialogDescription, useForwardProps } from 'radix-vue';
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
  <DialogDescription
    v-bind="forwardedProps"
    :class="cn('text-sm text-muted-foreground leading-relaxed', props.class)"
  >
    <slot />
  </DialogDescription>
</template>
