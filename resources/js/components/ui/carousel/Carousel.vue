<script setup lang="ts">
import { provide, ref, watch } from 'vue';
import emblaCarouselVue from 'embla-carousel-vue';
import type { EmblaCarouselType } from 'embla-carousel';
import { cn } from '@/lib/utils';

const props = withDefaults(
  defineProps<{
    opts?: Parameters<typeof emblaCarouselVue>[0];
    orientation?: 'horizontal' | 'vertical';
    class?: string;
  }>(),
  {
    orientation: 'horizontal',
  },
);

const [emblaRef, emblaApi] = emblaCarouselVue(props.opts);
const canScrollPrev = ref(false);
const canScrollNext = ref(false);

function onSelect(api: EmblaCarouselType) {
  canScrollPrev.value = api.canScrollPrev();
  canScrollNext.value = api.canScrollNext();
}

watch(emblaApi, (api) => {
  if (!api) return;
  api.on('select', onSelect);
  api.on('reInit', onSelect);
  onSelect(api);
});

provide('emblaCarousel', {
  emblaRef,
  emblaApi,
  canScrollPrev,
  canScrollNext,
  scrollPrev: () => emblaApi.value?.scrollPrev(),
  scrollNext: () => emblaApi.value?.scrollNext(),
  orientation: props.orientation,
});
</script>

<template>
  <div :class="cn('relative', props.class)" role="region" aria-roledescription="carousel">
    <slot />
  </div>
</template>
