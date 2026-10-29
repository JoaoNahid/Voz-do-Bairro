<script setup lang="ts">
import { ref } from 'vue';
import { useSessionStore, type Report } from '@/stores/sessionStore';
import { initialReports } from '@/data/mockReports';
import Navbar from '@/components/presentation/Navbar.vue';
import HeroSection from '@/components/presentation/HeroSection.vue';
import ReportsCarouselSection from '@/components/presentation/ReportsCarouselSection.vue';
import QuickReportSection from '@/components/presentation/QuickReportSection.vue';
import FooterSection from '@/components/presentation/FooterSection.vue';
import AuthDialog from '@/components/presentation/AuthDialog.vue';
import VoteConfirmDialog from '@/components/presentation/VoteConfirmDialog.vue';
import FullReportDialog from '@/components/presentation/FullReportDialog.vue';
import { Toaster, useToast } from '@/components/ui/toast';

const { isAuthenticated, currentUser, toggleAuth, login } = useSessionStore();
const { toast } = useToast();

// Interactive Reports State
const reports = ref<Report[]>([...initialReports]);

// Modal States
const isAuthModalOpen = ref(false);
const authModalTab = ref<'login' | 'register'>('login');

const isVoteModalOpen = ref(false);
const selectedReportForVote = ref<Report | null>(null);
const currentVoteType = ref<'relevant' | 'irrelevant'>('relevant');

const isFullReportModalOpen = ref(false);
const draftReportData = ref<{ title: string; description: string }>({
  title: '',
  description: '',
});

// Draft saved while authenticating
const pendingDraft = ref<{ title: string; description: string } | null>(null);
const pendingVoteAction = ref<{ reportId: string; type: 'relevant' | 'irrelevant' } | null>(null);

function handleOpenLogin(tab: 'login' | 'register' = 'login') {
  authModalTab.value = tab;
  isAuthModalOpen.value = true;
}

function handleExploreClick() {
  const el = document.getElementById('denuncias');
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' });
  }
}

function handleReportClick() {
  const el = document.getElementById('novo-relato');
  if (el) {
    el.scrollIntoView({ behavior: 'smooth' });
  }
}

function handleVote(reportId: string, type: 'relevant' | 'irrelevant') {
  const targetReport = reports.value.find((r) => r.id === reportId);
  if (!targetReport) return;

  if (!isAuthenticated.value) {
    pendingVoteAction.value = { reportId, type };
    authModalTab.value = 'login';
    isAuthModalOpen.value = true;
    toast({
      title: 'Acesso Necessário',
      description: 'Entre ou crie uma conta de morador para validar a relevância desta demanda.',
      variant: 'default',
    });
    return;
  }

  selectedReportForVote.value = targetReport;
  currentVoteType.value = type;
  isVoteModalOpen.value = true;
}

function handleConfirmVote() {
  if (!selectedReportForVote.value) return;

  const rep = reports.value.find((r) => r.id === selectedReportForVote.value?.id);
  if (rep) {
    if (currentVoteType.value === 'relevant') {
      if (rep.userVote !== 'relevant') {
        if (rep.userVote === 'irrelevant') rep.votesIrrelevant = Math.max(0, rep.votesIrrelevant - 1);
        rep.votesRelevant += 1;
        rep.userVote = 'relevant';
      }
    } else {
      if (rep.userVote !== 'irrelevant') {
        if (rep.userVote === 'relevant') rep.votesRelevant = Math.max(0, rep.votesRelevant - 1);
        rep.votesIrrelevant += 1;
        rep.userVote = 'irrelevant';
      }
    }

    // Recalculate quorum
    rep.quorumPercentage = Math.min(100, Math.round((rep.votesRelevant / 80) * 100));
    if (rep.quorumPercentage >= 60) {
      rep.isPublicPriority = true;
    }

    toast({
      title: currentVoteType.value === 'relevant' ? 'Voto Registrado como Relevante!' : 'Voto Registrado como Irrelevante',
      description: `Sua manifestação foi contabilizada para o relato em ${rep.neighborhood}.`,
      variant: 'default',
    });
  }

  isVoteModalOpen.value = false;
  selectedReportForVote.value = null;
}

function handleSubmitDraft(draft: { title: string; description: string }) {
  if (isAuthenticated.value) {
    draftReportData.value = { ...draft };
    isFullReportModalOpen.value = true;
  } else {
    pendingDraft.value = { ...draft };
    authModalTab.value = 'register';
    isAuthModalOpen.value = true;
    toast({
      title: 'Quase lá!',
      description: 'Acesse como morador para que possamos validar e publicar sua denúncia.',
      variant: 'default',
    });
  }
}

function handleAuthSuccess(user: any) {
  // Check if there was a pending draft to open
  if (pendingDraft.value) {
    draftReportData.value = { ...pendingDraft.value };
    pendingDraft.value = null;
    isFullReportModalOpen.value = true;
  } else if (pendingVoteAction.value) {
    const { reportId, type } = pendingVoteAction.value;
    pendingVoteAction.value = null;
    handleVote(reportId, type);
  }
}

function handleFullReportSubmit(newReport: Report) {
  reports.value.unshift(newReport);
  draftReportData.value = { title: '', description: '' };
  handleExploreClick();
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 flex flex-col font-sans selection:bg-amber-400 selection:text-slate-950">
    <!-- Navigation Bar -->
    <Navbar
      :is-authenticated="isAuthenticated"
      :current-user="currentUser"
      @toggle-auth="toggleAuth"
      @open-login="handleOpenLogin"
    />

    <!-- Main Content Area -->
    <main class="flex-1">
      <!-- Hero Section (US1) -->
      <HeroSection
        title="A voz do seu bairro tem força em Pouso Alegre."
        location="Pouso Alegre, MG"
        @explore-click="handleExploreClick"
        @report-click="handleReportClick"
      />

      <!-- Trending Reports Carousel (US2) -->
      <ReportsCarouselSection
        :reports="reports"
        :is-authenticated="isAuthenticated"
        @vote="handleVote"
      />

      <!-- Quick Report Section (US3) -->
      <QuickReportSection
        :is-authenticated="isAuthenticated"
        @submit-draft="handleSubmitDraft"
      />
    </main>

    <!-- Institutional Footer -->
    <FooterSection />

    <!-- Modals & Overlays -->
    <AuthDialog
      :open="isAuthModalOpen"
      :initial-tab="authModalTab"
      @update:open="(val) => (isAuthModalOpen = val)"
      @success="handleAuthSuccess"
    />

    <VoteConfirmDialog
      :open="isVoteModalOpen"
      :report="selectedReportForVote"
      :vote-type="currentVoteType"
      @update:open="(val) => (isVoteModalOpen = val)"
      @confirm="handleConfirmVote"
    />

    <FullReportDialog
      :open="isFullReportModalOpen"
      :initial-data="draftReportData"
      @update:open="(val) => (isFullReportModalOpen = val)"
      @submit="handleFullReportSubmit"
    />

    <!-- Global Toast Container -->
    <Toaster />
  </div>
</template>
