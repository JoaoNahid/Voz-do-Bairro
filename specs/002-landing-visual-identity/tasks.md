# Tasks: Landing Page & Visual Identity Presentation

**Feature**: `002-landing-visual-identity`
**Status**: Ready for Execution
**Spec**: [specs/002-landing-visual-identity/spec.md](spec.md) | **Plan**: [specs/002-landing-visual-identity/plan.md](plan.md)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Inicialização da stack Laravel 11, configuração do Vite + Vue 3 e design system shadcn-vue / Tailwind

- [X] T001 Setup Laravel 11 application scaffolding and configuration in composer.json and bootstrap/app.php
- [X] T002 Configure Vite, Vue 3, Tailwind CSS and PostCSS dependencies in package.json and vite.config.js
- [X] T003 [P] Configure Tailwind CSS with Cívico Institucional theme tokens in tailwind.config.js and resources/css/app.css
- [X] T004 [P] Implement shadcn-vue utility function cn in resources/js/lib/utils.ts

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Primitivas UI do shadcn-vue, casca Blade do Laravel e store de sessão simulada

**⚠️ CRITICAL**: Pré-requisito para montagem de qualquer história de usuário

- [X] T005 Implement shadcn-vue Button primitive in resources/js/components/ui/button/Button.vue
- [X] T006 [P] Implement shadcn-vue Card primitives in resources/js/components/ui/card/Card.vue
- [X] T007 [P] Implement shadcn-vue Badge primitive in resources/js/components/ui/badge/Badge.vue
- [X] T008 [P] Implement shadcn-vue Dialog/Modal primitives in resources/js/components/ui/dialog/Dialog.vue
- [X] T009 [P] Implement shadcn-vue Input and Textarea primitives in resources/js/components/ui/input/Input.vue and resources/js/components/ui/textarea/Textarea.vue
- [X] T010 [P] Implement shadcn-vue Toast system and toaster in resources/js/components/ui/toast/Toaster.vue
- [X] T011 Setup Laravel web route and Blade SPA mounting view in routes/web.php and resources/views/app.blade.php
- [X] T012 Implement reactive simulated session store in resources/js/stores/sessionStore.ts

**Checkpoint**: Base de componentes de interface e infraestrutura pronta para início das histórias de usuário.

---

## Phase 3: User Story 1 - Impacto Visual Imediato e Conscientização Cívica no Hero Banner (Priority: P1) 🎯 MVP

**Goal**: Morador de Pouso Alegre visualiza navbar com toggle de visitante/morador e Hero Banner com estética Cívico Institucional e chamada forte.

**Independent Test**: Carregar a página inicial e verificar renderização da Navbar, do slogan de Pouso Alegre no Hero e botões de chamada primária (CTA).

### Implementation for User Story 1

- [X] T013 [P] [US1] Create local Pouso Alegre visual assets in public/images/hero-bg.jpg
- [X] T014 [US1] Implement responsive Navbar component with simulated auth mode toggle in resources/js/components/presentation/Navbar.vue
- [X] T015 [US1] Implement HeroSection component with civic slogan and call-to-action buttons in resources/js/components/presentation/HeroSection.vue

**Checkpoint**: Apresentação visual e Hero Banner 100% funcionais e testáveis de forma independente.

---

## Phase 4: User Story 2 - Exploração de Relatos em Alta e Reação Comunitária Condicional (Priority: P2)

**Goal**: Morador navega pelo carrossel nos 4 eixos (Vias, Saneamento, Iluminação, Praças) e interage com votos, disparando modais condicionais.

**Independent Test**: Clicar em "Relevante" no carrossel quando deslogado (abre AuthDialog) e quando logado (abre VoteConfirmDialog).

### Implementation for User Story 2

- [X] T016 [P] [US2] Implement shadcn-vue Carousel primitives in resources/js/components/ui/carousel/Carousel.vue
- [X] T017 [P] [US2] Create mock reports fixture dataset for the 4 civic axes in resources/js/data/mockReports.ts
- [X] T018 [US2] Implement AuthDialog component for simulated Login and Register tabs with Toast feedback in resources/js/components/presentation/AuthDialog.vue
- [X] T019 [US2] Implement VoteConfirmDialog component for vote confirmation in resources/js/components/presentation/VoteConfirmDialog.vue
- [X] T020 [US2] Implement ReportsCarouselSection component displaying cards across the 4 civic axes in resources/js/components/presentation/ReportsCarouselSection.vue

**Checkpoint**: Carrossel nos 4 eixos de zeladoria e modais de autenticação e voto operacionais e integrados.

---

## Phase 5: User Story 3 - Prévia de Criação Rápida de Relato com Interceptação de Autenticação (Priority: P3)

**Goal**: Morador preenche título e descrição na seção rápida; se logado abre FullReportDialog, se deslogado abre AuthDialog.

**Independent Test**: Preencher formulário rápido e acionar envio em ambos os modos de sessão, validando abertura do modal correspondente.

### Implementation for User Story 3

- [X] T021 [US3] Implement FullReportDialog component with complete report fields preview in resources/js/components/presentation/FullReportDialog.vue
- [X] T022 [US3] Implement QuickReportSection component with draft title/description and auth check in resources/js/components/presentation/QuickReportSection.vue

**Checkpoint**: Fluxo de criação rápida com interceptação de autenticação testável e validado.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Rodapé institucional, montagem final do App.vue e validação responsiva

- [X] T023 [P] Implement institutional FooterSection with UN SDGs and university metadata in resources/js/components/presentation/FooterSection.vue
- [X] T024 Assemble all sections and dialogs in root Vue component resources/js/App.vue and mount in resources/js/app.js
- [X] T025 Run quickstart validation test scenarios from specs/002-landing-visual-identity/quickstart.md
- [X] T026 [P] Validate responsive design and mobile layout from 320px to 1920px in resources/css/app.css

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: Sem dependências prévias — inicialização imediata de Laravel, Vite, Vue e Tailwind.
- **Foundational (Phase 2)**: Depende do Setup — constrói as primitivas do shadcn-vue e casca Blade.
- **User Stories (Phase 3+)**: Dependem da conclusão da Fase Fundamental.
  - User Story 1 (P1): Implementada primeiro como núcleo da identidade visual e banner.
  - User Story 2 (P2): Depende das primitivas de Dialog e Card da Fase 2.
  - User Story 3 (P3): Depende do AuthDialog da US2 ou reutiliza o mesmo diálogo.
- **Polish (Phase 6)**: Montagem integrada do `App.vue` e validações de tela.

### Parallel Opportunities

- Na Fase 1: `T003` e `T004` podem rodar em paralelo.
- Na Fase 2: `T006`, `T007`, `T008`, `T009` e `T010` são componentes isolados do shadcn-vue e podem ser construídos simultaneamente.
- Na Fase 3: `T013` (ativos gráficos) e `T014` (Navbar) são paralelizáveis.
- Na Fase 4: `T016` (Carousel) e `T017` (mock data) podem ser criados em paralelo.
- Na Fase 6: `T023` (Footer) e `T026` (revisão de responsividade CSS) são independentes.

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Executar Fase 1: Setup Laravel + Vite + Vue 3 + Tailwind
2. Executar Fase 2: Primitivas shadcn-vue básicas e store de sessão
3. Executar Fase 3: User Story 1 (Navbar e Hero Banner com temática de Pouso Alegre)
4. Validar apresentação e layout visual no navegador

### Incremental Delivery

1. Setup + Fundações → Base do framework e design system prontos
2. User Story 1 → Entrega do MVP (Hero Banner e Navbar)
3. User Story 2 → Adição do Carrossel dos 4 eixos, modal de autenticação e diálogo de voto
4. User Story 3 → Adição da seção de reporte rápido e modal de formulário detalhado
5. Polish → Montagem final no `App.vue`, footer institucional e testes do `quickstart.md`
