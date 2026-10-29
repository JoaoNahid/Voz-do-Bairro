# Component Contracts: shadcn-vue Architecture

**Feature**: `002-landing-visual-identity`
**Date**: 2026-10-28

Este documento especifica os componentes de interface do [shadcn-vue](https://www.shadcn-vue.com/) e seus contratos de props/eventos para a Landing Page do Voz do Bairro.

## 1. Componentes Base (shadcn-vue UI)

Localizados em `resources/js/components/ui/`:

- **Button** (`Button.vue`):
  - *Variants*: `default` (azul marinho cívico), `accent` (dourado/âmbar), `outline`, `ghost`, `destructive`
  - *Sizes*: `sm`, `default`, `lg`, `icon`
- **Card** (`Card.vue`, `CardHeader.vue`, `CardTitle.vue`, `CardDescription.vue`, `CardContent.vue`, `CardFooter.vue`):
  - Utilizado nos cards de denúncias do carrossel com elevação suave, bordas arredondadas e badges de quórum.
- **Carousel** (`Carousel.vue`, `CarouselContent.vue`, `CarouselItem.vue`, `CarouselPrevious.vue`, `CarouselNext.vue`):
  - Primitiva baseada em Embla Carousel para slide fluído em mobile (touch swipe) e desktop (setas).
- **Dialog** (`Dialog.vue`, `DialogTrigger.vue`, `DialogContent.vue`, `DialogHeader.vue`, `DialogTitle.vue`, `DialogDescription.vue`, `DialogFooter.vue`):
  - Diálogos acessíveis com overlay escurecido (backdrop blur), fechamento por tecla `Esc` e animação de fade/zoom.
- **Badge** (`Badge.vue`):
  - *Variants*: `default`, `secondary`, `outline`, `priority` (ouro/âmbar para relatos > 60% quórum)
- **Input** (`Input.vue`) & **Textarea** (`Textarea.vue`):
  - Campos tipados e estilizados para formulário rápido e modal de login/cadastro.
- **Toast** (`Toaster.vue`, `use-toast.ts`):
  - Sistema de feedback flutuante para notificações de sucesso.

---

## 2. Componentes de Apresentação (Domain Components)

Localizados em `resources/js/components/presentation/`:

### `Navbar.vue`
- **Props**:
  - `isAuthenticated`: `Boolean`
  - `currentUser`: `UserSessionState | null`
- **Events**:
  - `@toggle-auth`: Altera o modo simulado (Visitante vs. Morador)
  - `@open-login`: Dispara o modal de login

### `HeroSection.vue`
- **Props**:
  - `title`: `String` (Slogan cívico de alto impacto)
  - `location`: `String` (`"Pouso Alegre, MG"`)
- **Events**:
  - `@explore-click`: Scroll suave para a seção de carrossel
  - `@report-click`: Scroll suave ou foco na seção de novo reporte

### `ReportsCarouselSection.vue`
- **Props**:
  - `reports`: `ReportItem[]`
  - `isAuthenticated`: `Boolean`
- **Events**:
  - `@vote`: `(reportId: String, type: 'relevant' | 'irrelevant') => void`

### `QuickReportSection.vue`
- **Props**:
  - `isAuthenticated`: `Boolean`
- **Events**:
  - `@submit-draft`: `(draft: { title: String, description: String }) => void`

### `AuthDialog.vue`
- **Props**:
  - `open`: `Boolean`
  - `initialTab`: `'login' | 'register'`
- **Events**:
  - `@update:open`: `(val: Boolean) => void`
  - `@success`: `(user: UserSessionState) => void`

### `VoteConfirmDialog.vue`
- **Props**:
  - `open`: `Boolean`
  - `report`: `ReportItem | null`
  - `voteType`: `'relevant' | 'irrelevant'`
- **Events**:
  - `@update:open`: `(val: Boolean) => void`
  - `@confirm`: `() => void`

### `FullReportDialog.vue`
- **Props**:
  - `open`: `Boolean`
  - `initialData`: `{ title: String, description: String }`
- **Events**:
  - `@update:open`: `(val: Boolean) => void`
  - `@submit`: `(reportData: Object) => void`

### `FooterSection.vue`
- **Props**:
  - Sem props obrigatórias (exibe metadados institucionais, ODSs e copyright).
