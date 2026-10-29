<script setup lang="ts">
import {
  Building2,
  GraduationCap,
  Globe,
  Heart,
  ExternalLink,
  ShieldAlert,
} from 'lucide-vue-next';
</script>

<template>
  <footer class="bg-slate-950 text-slate-400 py-12 md:py-16 border-t border-slate-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Col 1: Brand & Academic Mission -->
        <div class="space-y-4">
          <div class="flex items-center gap-2 text-white">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-primary to-blue-600 flex items-center justify-center font-bold text-white shadow-md text-sm">
              VB
            </div>
            <span class="text-lg font-bold tracking-tight">Voz do Bairro</span>
          </div>

          <p class="text-xs text-slate-400 leading-relaxed">
            Plataforma cívica digital de zeladoria urbana participativa. Projeto de extensão acadêmica do curso de <strong>Sistemas de Informação</strong> em <strong>Pouso Alegre, MG</strong>.
          </p>

          <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-900 border border-slate-800 text-slate-300">
            <GraduationCap class="w-3.5 h-3.5 text-amber-400" />
            <span>Extensão Universitária &bull; Pouso Alegre</span>
          </div>
        </div>

        <!-- Col 2: Navigation Links -->
        <div class="space-y-3">
          <h4 class="text-sm font-bold text-white uppercase tracking-wider">
            Navegação Rápida
          </h4>
          <ul class="space-y-2 text-xs">
            <li>
              <a href="#inicio" class="hover:text-amber-400 transition-colors">Início & Apresentação</a>
            </li>
            <li>
              <a href="#denuncias" class="hover:text-amber-400 transition-colors">Demandas em Alta nos Bairros</a>
            </li>
            <li>
              <a href="#novo-relato" class="hover:text-amber-400 transition-colors">Registrar Nova Denúncia</a>
            </li>
            <li>
              <a href="#sobre" class="hover:text-amber-400 transition-colors">Metodologia de Quórum Comunitário</a>
            </li>
          </ul>
        </div>

        <!-- Col 3: UN Sustainable Development Goals (ODS) -->
        <div class="space-y-3">
          <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
            <Globe class="w-4 h-4 text-emerald-400" />
            Impacto ODS / ONU
          </h4>
          <ul class="space-y-2 text-xs">
            <li class="flex items-start gap-2">
              <span class="px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 font-mono text-[10px] font-bold shrink-0">ODS 11</span>
              <span class="text-slate-300">Cidades e Comunidades Sustentáveis</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 font-mono text-[10px] font-bold shrink-0">ODS 16</span>
              <span class="text-slate-300">Instituições Eficazes e Cidadania</span>
            </li>
            <li class="flex items-start gap-2">
              <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-300 font-mono text-[10px] font-bold shrink-0">ODS 9</span>
              <span class="text-slate-300">Inovação e Infraestrutura Local</span>
            </li>
          </ul>
        </div>

        <!-- Col 4: Neighborhood Pilot & City Info -->
        <div class="space-y-3">
          <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-1.5">
            <Building2 class="w-4 h-4 text-primary-foreground" />
            Comunidade Piloto
          </h4>
          <p class="text-xs text-slate-400 leading-relaxed">
            Foco inicial de mobilização no bairro <strong>Recanto dos Fernandes</strong> e expansão contínua para os demais bairros de <strong>Pouso Alegre, Sul de Minas Gerais</strong>.
          </p>
          <div class="p-3 rounded-lg bg-slate-900 border border-slate-800 text-[11px] text-slate-300 space-y-1">
            <div class="font-semibold text-white">4 Eixos de Atuação Cívica:</div>
            <div class="text-slate-400">Vias Públicas &bull; Saneamento &bull; Iluminação &bull; Limpeza</div>
          </div>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
        <p>
          &copy; {{ new Date().getFullYear() }} Voz do Bairro. Desenvolvido com foco no cidadão de Pouso Alegre, MG.
        </p>
        <p class="flex items-center gap-1 text-[11px]">
          Feito com <Heart class="w-3.5 h-3.5 text-rose-500 fill-rose-500" /> para a comunidade e zeladoria urbana.
        </p>
      </div>
    </div>
  </footer>
</template>
