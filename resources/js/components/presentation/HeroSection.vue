<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { AlertTriangle, ArrowRight, ShieldCheck, Users, Flame, MapPin } from 'lucide-vue-next';

const emit = defineEmits<{
  (e: 'explore'): void;
  (e: 'report'): void;
}>();
</script>

<template>
  <section id="hero" class="relative overflow-hidden bg-slate-950 text-white py-20 lg:py-28 min-h-[580px] flex items-center">
    <!-- Background Image with Civic Overlay -->
    <div class="absolute inset-0 z-0">
      <img
        src="/images/hero-bg.jpg"
        alt="Vista panorâmica da cidade de Pouso Alegre, Minas Gerais"
        class="h-full w-full object-cover object-center opacity-40 scale-105 transition-transform duration-1000 ease-out"
      />
      <div class="absolute inset-0 hero-overlay"></div>
      <div class="absolute inset-0 bg-radial-gradient from-transparent via-slate-950/60 to-slate-950"></div>
    </div>

    <!-- Hero Content Container -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
      <div class="max-w-3xl space-y-6">
        <!-- Urgent Civic Banner Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/20 border border-amber-400/40 text-amber-300 text-xs sm:text-sm font-bold tracking-wide backdrop-blur-sm animate-pulse">
          <Flame class="h-4 w-4 text-amber-400 shrink-0" />
          <span>ALERTA COMUNITÁRIO EM POUSO ALEGRE</span>
          <span class="hidden sm:inline-block text-amber-200/60">• Recanto dos Fernandes e Região</span>
        </div>

        <!-- Strong / Impactful Catchy Heading -->
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] text-white">
          A Voz do Bairro Não Pode Ser Calada: <br class="hidden sm:inline" />
          <span class="bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-500 bg-clip-text text-transparent">
            Chega de Esperar!
          </span>
        </h1>

        <!-- Subheadline / Body -->
        <p class="text-base sm:text-lg lg:text-xl text-slate-200 leading-relaxed font-normal max-w-2xl">
          Buracos na rua que estragam seu carro? Esgoto a céu aberto na porta de casa? Postes sem luz trazendo perigo para sua família?
          <strong class="text-white font-semibold"> Faça sua denúncia agora mesmo.</strong> No <span class="text-amber-400 font-bold">Voz do Bairro</span>, quando a comunidade atinge <span class="underline decoration-amber-400 font-bold">60% dos votos</span>, a cobrança se torna prioridade pública inegociável!
        </p>

        <!-- CTA Buttons -->
        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
          <Button
            size="lg"
            variant="accent"
            @click="emit('report')"
            class="text-base font-extrabold shadow-amber-500/25 hover:shadow-amber-500/40 text-slate-950 gap-2 h-13"
          >
            <AlertTriangle class="h-5 w-5 text-slate-950" />
            Fazer Denúncia do Meu Bairro
          </Button>

          <Button
            size="lg"
            variant="outline"
            @click="emit('explore')"
            class="text-base font-semibold border-white/30 text-white hover:bg-white/10 hover:text-white hover:border-white gap-2 h-13 backdrop-blur-sm"
          >
            Ver Problemas em Alta
            <ArrowRight class="h-4 w-4" />
          </Button>
        </div>

        <!-- Trust Badges & Metrics -->
        <div class="pt-6 grid grid-cols-2 sm:grid-cols-3 gap-4 border-t border-white/15 text-xs text-slate-300">
          <div class="flex items-center gap-2.5">
            <div class="h-8 w-8 rounded-lg bg-blue-500/20 flex items-center justify-center shrink-0 text-blue-400">
              <Users class="h-4 w-4" />
            </div>
            <div>
              <p class="font-bold text-white text-sm">Quórum de 60%</p>
              <p class="text-slate-400">Validação democrática real</p>
            </div>
          </div>

          <div class="flex items-center gap-2.5">
            <div class="h-8 w-8 rounded-lg bg-amber-500/20 flex items-center justify-center shrink-0 text-amber-400">
              <ShieldCheck class="h-4 w-4" />
            </div>
            <div>
              <p class="font-bold text-white text-sm">Sem Burocracia</p>
              <p class="text-slate-400">Autogerido por vizinhos</p>
            </div>
          </div>

          <div class="col-span-2 sm:col-span-1 flex items-center gap-2.5">
            <div class="h-8 w-8 rounded-lg bg-emerald-500/20 flex items-center justify-center shrink-0 text-emerald-400">
              <MapPin class="h-4 w-4" />
            </div>
            <div>
              <p class="font-bold text-white text-sm">Foco em Pouso Alegre</p>
              <p class="text-slate-400">Recanto dos Fernandes e mais</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
