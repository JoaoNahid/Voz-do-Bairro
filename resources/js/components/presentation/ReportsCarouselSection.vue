<script setup lang="ts">
import { ref } from 'vue';
import type { Report } from '@/stores/sessionStore';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselPrevious,
  CarouselNext,
} from '@/components/ui/carousel';
import {
  MapPin,
  ThumbsUp,
  ThumbsDown,
  Flame,
  CheckCircle2,
  Calendar,
  Layers,
} from 'lucide-vue-next';

interface Props {
  reports: Report[];
  isAuthenticated?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  isAuthenticated: false,
});

const emit = defineEmits<{
  (e: 'vote', reportId: string, type: 'relevant' | 'irrelevant'): void;
}>();

const selectedCategory = ref<string>('all');

function getCategoryBadgeVariant(category: Report['category']): 'default' | 'secondary' | 'outline' | 'priority' {
  switch (category) {
    case 'vias':
      return 'default';
    case 'saneamento':
      return 'secondary';
    case 'iluminacao':
      return 'priority';
    case 'limpeza':
      return 'outline';
    default:
      return 'secondary';
  }
}

function handleVote(reportId: string, type: 'relevant' | 'irrelevant') {
  emit('vote', reportId, type);
}
</script>

<template>
  <section id="denuncias" class="py-16 md:py-24 bg-slate-50/70 border-y border-slate-200/80">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Section Header -->
      <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
          <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100/90 text-amber-900 mb-3 border border-amber-200/80">
            <Flame class="w-3.5 h-3.5 text-amber-600 fill-amber-500" />
            <span>Mobilização Cívica em Alta</span>
          </div>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
            Demandas Urgentes dos Bairros
          </h2>
          <p class="text-slate-600 mt-2 max-w-2xl text-base sm:text-lg">
            Acompanhe e priorize os relatos mais críticos de Pouso Alegre. Quanto maior a relevância coletiva, mais rápido a demanda ganha visibilidade pública e atenção dos órgãos competentes.
          </p>
        </div>

        <!-- Indicator of how it works -->
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-white/80 backdrop-blur-sm p-3 rounded-lg border border-slate-200 shadow-xs shrink-0 self-start md:self-auto">
          <Layers class="w-4 h-4 text-primary" />
          <span>4 Eixos Urbanos Prioritários</span>
        </div>
      </div>

      <!-- Carousel Container -->
      <div class="relative">
        <Carousel
          :opts="{
            align: 'start',
            loop: false,
          }"
          class="w-full"
        >
          <div class="flex items-center justify-end gap-2 mb-4">
            <CarouselPrevious class="static translate-y-0" />
            <CarouselNext class="static translate-y-0" />
          </div>

          <CarouselContent class="-ml-4 pb-4">
            <CarouselItem
              v-for="report in props.reports"
              :key="report.id"
              class="pl-4 md:basis-1/2 lg:basis-1/3"
            >
              <Card class="h-full flex flex-col overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 bg-white border-slate-200">
                <!-- Image Header with Badges -->
                <div class="relative h-48 w-full overflow-hidden bg-slate-100">
                  <img
                    :src="report.imageUrl"
                    :alt="report.title"
                    class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                    loading="lazy"
                  />
                  <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

                  <!-- Category Badge -->
                  <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                    <Badge :variant="getCategoryBadgeVariant(report.category)" class="shadow-sm font-medium">
                      {{ report.categoryLabel }}
                    </Badge>
                    <Badge
                      v-if="report.isPublicPriority"
                      variant="priority"
                      class="shadow-sm font-semibold flex items-center gap-1"
                    >
                      <Flame class="w-3 h-3 fill-amber-700" />
                      Prioridade Cívica
                    </Badge>
                  </div>

                  <!-- Neighborhood Location Pill on Image bottom -->
                  <div class="absolute bottom-2.5 left-3 right-3 flex items-center text-xs font-medium text-white/95 truncate drop-shadow-sm">
                    <MapPin class="w-3.5 h-3.5 mr-1 text-amber-400 shrink-0" />
                    <span class="truncate">{{ report.street }} &bull; {{ report.neighborhood }}</span>
                  </div>
                </div>

                <!-- Card Body -->
                <CardHeader class="pt-4 pb-2">
                  <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                    <span class="inline-flex items-center gap-1">
                      <Calendar class="w-3 h-3" />
                      {{ report.date }}
                    </span>
                    <span class="font-mono text-[11px] text-slate-400">#{{ report.id }}</span>
                  </div>
                  <CardTitle class="text-base font-bold text-slate-900 leading-snug line-clamp-2">
                    {{ report.title }}
                  </CardTitle>
                </CardHeader>

                <CardContent class="py-2 flex-1">
                  <!-- Quorum & Engagement Meter -->
                  <div class="space-y-1.5 bg-slate-50 p-2.5 rounded-md border border-slate-100">
                    <div class="flex justify-between items-center text-xs">
                      <span class="font-medium text-slate-700">Índice de Relevância</span>
                      <span class="font-bold text-primary">{{ report.quorumPercentage }}%</span>
                    </div>
                    <!-- Custom Progress Bar -->
                    <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                      <div
                        class="h-full rounded-full transition-all duration-500"
                        :class="report.quorumPercentage >= 60 ? 'bg-amber-500' : 'bg-primary'"
                        :style="{ width: `${report.quorumPercentage}%` }"
                      ></div>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-500 pt-0.5">
                      <span>Meta comunitária: 100 apoios</span>
                      <span v-if="report.quorumPercentage >= 60" class="text-amber-700 font-semibold flex items-center gap-0.5">
                        <CheckCircle2 class="w-3 h-3 inline" /> Alta comoção
                      </span>
                    </div>
                  </div>
                </CardContent>

                <!-- Card Actions -->
                <CardFooter class="pt-2 pb-4 border-t border-slate-100 flex items-center justify-between gap-2">
                  <div class="text-xs font-medium text-slate-500">
                    Sua opinião:
                  </div>
                  <div class="flex items-center gap-2">
                    <Button
                      variant="outline"
                      size="sm"
                      class="h-9 px-3 gap-1.5 text-xs font-semibold hover:border-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors"
                      :class="{ 'border-emerald-600 bg-emerald-50 text-emerald-800 ring-2 ring-emerald-500/20': report.userVote === 'relevant' }"
                      @click="handleVote(report.id, 'relevant')"
                    >
                      <ThumbsUp class="w-3.5 h-3.5" :class="report.userVote === 'relevant' ? 'fill-emerald-600 text-emerald-600' : ''" />
                      <span>Relevante</span>
                      <span class="ml-0.5 font-bold text-slate-700">({{ report.votesRelevant }})</span>
                    </Button>

                    <Button
                      variant="outline"
                      size="sm"
                      class="h-9 px-2.5 gap-1 text-xs text-slate-600 hover:border-rose-400 hover:text-rose-700 hover:bg-rose-50 transition-colors"
                      :class="{ 'border-rose-600 bg-rose-50 text-rose-800 ring-2 ring-rose-500/20': report.userVote === 'irrelevant' }"
                      @click="handleVote(report.id, 'irrelevant')"
                    >
                      <ThumbsDown class="w-3.5 h-3.5" :class="report.userVote === 'irrelevant' ? 'fill-rose-600 text-rose-600' : ''" />
                      <span class="sr-only sm:not-sr-only">Irrelevante</span>
                      <span class="ml-0.5 text-slate-500">({{ report.votesIrrelevant }})</span>
                    </Button>
                  </div>
                </CardFooter>
              </Card>
            </CarouselItem>
          </CarouselContent>
        </Carousel>
      </div>

      <!-- Quick Context Box -->
      <div class="mt-8 p-4 rounded-xl bg-primary/5 border border-primary/10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
            <CheckCircle2 class="w-5 h-5 text-primary" />
          </div>
          <div>
            <h4 class="text-sm font-bold text-slate-900">Como funciona o quórum de priorização?</h4>
            <p class="text-xs text-slate-600">
              Relatos com mais de 60% de apoio coletivo ganham selo de Prioridade Cívica e são encaminhados em relatórios mensais para o poder público.
            </p>
          </div>
        </div>
        <a
          href="#novo-relato"
          class="shrink-0 text-xs font-bold text-primary hover:text-primary/80 hover:underline flex items-center gap-1"
        >
          Quer denunciar outro problema? Clique aqui &rarr;
        </a>
      </div>
    </div>
  </section>
</template>
