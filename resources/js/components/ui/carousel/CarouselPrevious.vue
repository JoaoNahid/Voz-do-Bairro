<script setup lang="ts">
import { inject } from 'vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';
import { cn } from '@/lib/utils';

const props = defineProps<{
  class?: string;
}>();

const { scrollPrev } = inject<any>('emblaCarousel', {});
</script>

<template>
  <Button
    variant="outline"
    size="icon"
    :class="cn('h-10 w-10 rounded-full shadow-md bg-background/90 backdrop-blur-sm hover:bg-background', props.class)"
    @click="scrollPrev"
  >
    <ArrowLeft class="h-4 w-4" />
    <span class="sr-only">Anterior</span>
  </Button>
</template>
