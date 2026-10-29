<script setup lang="ts">
import { ref } from 'vue';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Badge } from '@/components/ui/badge';
import { useToast } from '@/components/ui/toast';
import {
  Megaphone,
  ArrowRight,
  ShieldCheck,
  MapPin,
  Clock,
  Sparkles,
} from 'lucide-vue-next';

interface Props {
  isAuthenticated?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  isAuthenticated: false,
});

const emit = defineEmits<{
  (e: 'submit-draft', draft: { title: string; description: string }): void;
}>();

const { toast } = useToast();

const title = ref('');
const description = ref('');

function handleSubmit() {
  if (!title.value.trim()) {
    toast({
      title: 'Informe o título do problema',
      description: 'Diga resumidamente qual é o transtorno no seu bairro.',
      variant: 'destructive',
    });
    return;
  }

  emit('submit-draft', {
    title: title.value.trim(),
    description: description.value.trim(),
  });
}

function clearForm() {
  title.value = '';
  description.value = '';
}

defineExpose({
  clearForm,
});
</script>

<template>
  <section id="novo-relato" class="py-16 md:py-24 bg-white relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mt-16 -mr-16 w-96 h-96 rounded-full bg-primary/5 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -mb-16 -ml-16 w-96 h-96 rounded-full bg-amber-500/5 blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <div class="max-w-4xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
          <!-- Left Column: Context & Motivators -->
          <div class="lg:col-span-5 space-y-6">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
              <Megaphone class="w-3.5 h-3.5" />
              <span>Participação Cidadã Direta</span>
            </div>

            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
              Sua voz transforma nosso bairro.
            </h2>

            <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
              Viu um buraco perigoso, vazamento d'água, praça abandonada ou poste apagado? Registre o problema em poucos segundos. O coletivo valida, e a demanda ganha força perante os órgãos de Pouso Alegre.
            </p>

            <!-- Value props -->
            <div class="space-y-3 pt-2">
              <div class="flex items-start gap-3">
                <div class="p-2 rounded-lg bg-emerald-100 text-emerald-800 shrink-0 mt-0.5">
                  <ShieldCheck class="w-4 h-4" />
                </div>
                <div>
                  <h4 class="text-xs sm:text-sm font-bold text-slate-900">100% Gratuito e Comunitário</h4>
                  <p class="text-xs text-slate-500">Desenvolvido como projeto de extensão universitária em Sistemas de Informação.</p>
                </div>
              </div>

              <div class="flex items-start gap-3">
                <div class="p-2 rounded-lg bg-amber-100 text-amber-900 shrink-0 mt-0.5">
                  <Clock class="w-4 h-4" />
                </div>
                <div>
                  <h4 class="text-xs sm:text-sm font-bold text-slate-900">Rápido e Descomplicado</h4>
                  <p class="text-xs text-slate-500">Sem burocracias: preencha a prévia e a comunidade cuida do quórum de relevância.</p>
                </div>
              </div>

              <div class="flex items-start gap-3">
                <div class="p-2 rounded-lg bg-blue-100 text-blue-900 shrink-0 mt-0.5">
                  <MapPin class="w-4 h-4" />
                </div>
                <div>
                  <h4 class="text-xs sm:text-sm font-bold text-slate-900">Focado em Pouso Alegre</h4>
                  <p class="text-xs text-slate-500">Recanto dos Fernandes, Centro, Fátima, São Geraldo e toda a cidade.</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: Quick Report Card -->
          <div class="lg:col-span-7">
            <Card class="border-slate-200/90 shadow-xl bg-gradient-to-br from-white to-slate-50/50 backdrop-blur-sm relative overflow-hidden">
              <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-primary via-blue-600 to-amber-500"></div>

              <CardHeader class="pt-6 pb-4">
                <div class="flex items-center justify-between">
                  <CardTitle class="text-xl sm:text-2xl font-bold text-slate-900">
                    Faça sua Denúncia
                  </CardTitle>
                  <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                    Etapa 1 de 2
                  </span>
                </div>
                <CardDescription class="text-xs sm:text-sm text-slate-600">
                  Preencha os dados básicos. Na sequência você poderá anexar foto e confirmar o bairro exato.
                </CardDescription>
              </CardHeader>

              <CardContent>
                <form @submit.prevent="handleSubmit" class="space-y-4">
                  <!-- Title Input -->
                  <div class="space-y-1.5">
                    <label for="quick-title" class="text-xs font-bold text-slate-700">
                      Qual é o problema? *
                    </label>
                    <Input
                      id="quick-title"
                      v-model="title"
                      placeholder="Ex: Vazamento de esgoto na esquina com a Rua dos Cravos"
                      required
                      class="h-11 text-sm bg-white"
                    />
                  </div>

                  <!-- Description Input -->
                  <div class="space-y-1.5">
                    <label for="quick-desc" class="text-xs font-bold text-slate-700">
                      Mais detalhes ou referência de localidade (opcional)
                    </label>
                    <Textarea
                      id="quick-desc"
                      v-model="description"
                      rows="3"
                      placeholder="Ex: Próximo ao posto de saúde, começou a transbordar ontem à noite..."
                      class="text-sm bg-white"
                    />
                  </div>

                  <!-- Auth Hint / Prompt -->
                  <div
                    v-if="!props.isAuthenticated"
                    class="p-3 rounded-lg bg-amber-50/80 border border-amber-200/70 text-xs text-amber-900 flex items-center justify-between gap-2"
                  >
                    <div class="flex items-center gap-2">
                      <Sparkles class="w-4 h-4 text-amber-600 shrink-0" />
                      <span>Você está no modo visitante. Um rápido acesso será solicitado para validar o relato.</span>
                    </div>
                  </div>
                  <div
                    v-else
                    class="p-3 rounded-lg bg-emerald-50/80 border border-emerald-200/70 text-xs text-emerald-900 flex items-center gap-2"
                  >
                    <ShieldCheck class="w-4 h-4 text-emerald-600 shrink-0" />
                    <span>Conectado como <strong>Morador Autenticado</strong>. Seu relato será publicado imediatamente após a revisão!</span>
                  </div>

                  <!-- Submit CTA Button -->
                  <Button
                    type="submit"
                    variant="accent"
                    size="lg"
                    class="w-full text-sm sm:text-base font-bold shadow-md hover:shadow-lg transition-all h-12 gap-2"
                  >
                    <span>Continuar Denúncia</span>
                    <ArrowRight class="w-4 h-4" />
                  </Button>
                </form>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
