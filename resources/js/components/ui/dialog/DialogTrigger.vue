<script setup lang="ts">
import { DialogTrigger } from 'radix-vue';

const props = defineProps<{
  asChild?: boolean;
  as?: any;
}>();
</script>

<template>
  <DialogTrigger v-bind="props">
    <slot />
  </DialogTrigger>
</template>
