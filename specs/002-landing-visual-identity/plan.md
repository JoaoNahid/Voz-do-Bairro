# Implementation Plan: Landing Page & Visual Identity Presentation

**Branch**: `002-landing-visual-identity` | **Date**: 2026-10-28 | **Spec**: [specs/002-landing-visual-identity/spec.md](spec.md)

**Input**: Feature specification from `/specs/002-landing-visual-identity/spec.md`

## Summary

Configurar a base dos frameworks Laravel 11 e Vue 3 com Vite e Tailwind CSS, implementando a biblioteca de componentes e design system **shadcn-vue** (conforme a Constituição v1.2.0). Construir a Landing Page de apresentação cívica do Voz do Bairro em 4 seções principais (Navbar responsiva, Hero Banner de alto impacto visual com temática de Pouso Alegre/MG, Carrossel interativo de relatos urbanos nos 4 eixos de zeladoria e Seção de Criação Rápida de ocorrência), suportada por 3 modais interativos (Login/Cadastro com transição para morador autenticado, confirmação de voto e formulário ampliado) e notificações Toast reativas, sem dependência de banco de dados nesta etapa.

## Technical Context

**Language/Version**: PHP 8.2+ (instalado: PHP 8.5.6) / Node 20+ (instalado: Node v24.14.1) / Vue 3.4+

**Primary Dependencies**:
- *Backend*: Laravel 11 (`laravel/framework`)
- *Frontend*: Vue 3, `@vitejs/plugin-vue`, `vite`, `tailwindcss`, `postcss`, `autoprefixer`
- *Design System (shadcn-vue)*: `radix-vue`, `lucide-vue-next`, `class-variance-authority`, `clsx`, `tailwind-merge`

**Storage**: Estado reativo de sessão em memória no frontend (`sessionStore` em Vue 3) e fixtures estáticas (`mock-reports.json`)

**Testing**: Validação de montagem e interações de componentes Vue e testes de carregamento da rota web

**Target Platform**: Navegadores web modernos (Chrome, Firefox, Safari, Edge) em desktop, tablets e mobile (iOS/Android)

**Project Type**: Web Application (Laravel backend servindo frontend SPA/Vue com Vite)

**Performance Goals**: Tempo de carregamento inicial < 1.5s, animações suaves a 60fps no carrossel e transição de modais < 200ms

**Constraints**: Sem migrações ou tabelas de banco de dados nesta fase; fidelidade à paleta Cívica Institucional (azul marinho e dourado/âmbar) e fotos locais de Pouso Alegre/MG

**Scale/Scope**: 1 Landing Page de alto padrão estético, 4 seções principais, 3 modais dinâmicos, carrossel de 4 relatos e componentes shadcn-vue reutilizáveis

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Princípio Constitucional | Status | Avaliação |
| :--- | :---: | :--- |
| **I. Spec-Driven Development & Token Economy** | **PASS** | Ciclo formal de especificação (`spec.md`) e clarificação executados antes do plano técnico. |
| **II. Additive Evolution & Zero Regression** | **PASS** | A introdução dos frameworks e componentes de frontend não altera arquivos de governança prévios. |
| **III. Automated Verification & Non-Regression** | **PASS** | Validação automatizada descrita no `quickstart.md` para verificar montagem e interatividade; TDD não exigido. |
| **IV. Clean Architecture, Clean Code & shadcn-vue** | **PASS** | Componentes desacoplados instalados em `resources/js/components/ui/` conforme padrão oficial do shadcn-vue. |
| **V. Social Utility & ODS Alignment** | **PASS** | Tema e conteúdo direcionados para a zeladoria urbana do bairro Recanto dos Fernandes em Pouso Alegre. |
| **Technology Stack & Git Conventions** | **PASS** | Stack Laravel 11 + Vue 3 + shadcn-vue em conformidade com a Constituição v1.2.0. |

*Resultado do Gate*: **APROVADO** (Nenhuma violação identificada).

## Project Structure

### Documentation (this feature)

```text
specs/002-landing-visual-identity/
├── spec.md                  # Especificação funcional com clarificações integradas
├── plan.md                  # Este plano arquitetural
├── research.md              # Pesquisa e decisões da stack Laravel + Vue + shadcn-vue
├── data-model.md            # Modelos conceituais e estado reativo de frontend
├── quickstart.md            # Guia de inicialização local e roteiro de validação
├── contracts/               # Contratos e fixtures
│   ├── mock-reports.json    # Fixtures de relatos urbanos de Pouso Alegre
│   └── component-contracts.md # Contratos de props e eventos dos componentes
└── checklists/              # Checklist de qualidade de requisitos
    └── requirements.md
```

### Source Code (repository root)

```text
voz-do-bairro/
├── app/                     # Backend Laravel básico
├── bootstrap/               # Bootstrap da aplicação
├── config/                  # Configurações do framework
├── public/                  # Ponto de entrada web e ativos estáticos
│   └── images/              # Imagens e referências visuais de Pouso Alegre
│       ├── hero-bg.jpg
│       └── reports/
├── resources/
│   ├── css/
│   │   └── app.css          # Variáveis CSS do tema Cívico Institucional & Tailwind
│   ├── js/
│   │   ├── app.js           # Ponto de montagem da aplicação Vue 3
│   │   ├── App.vue          # Componente raiz da Landing Page
│   │   ├── lib/
│   │   │   └── utils.ts     # Utilitário cn (clsx + tailwind-merge)
│   │   ├── components/
│   │   │   ├── ui/          # Componentes shadcn-vue
│   │   │   │   ├── button/
│   │   │   │   ├── card/
│   │   │   │   ├── carousel/
│   │   │   │   ├── dialog/
│   │   │   │   ├── badge/
│   │   │   │   ├── input/
│   │   │   │   ├── textarea/
│   │   │   │   └── toast/
│   │   │   └── presentation/# Componentes da Landing Page
│   │   │       ├── Navbar.vue
│   │   │       ├── HeroSection.vue
│   │   │       ├── ReportsCarouselSection.vue
│   │   │       ├── QuickReportSection.vue
│   │   │       ├── AuthDialog.vue
│   │   │       ├── VoteConfirmDialog.vue
│   │   │       ├── FullReportDialog.vue
│   │   │       └── FooterSection.vue
│   └── views/
│       └── app.blade.php    # Casca HTML do Laravel montando o script Vite
├── routes/
│   └── web.php              # Rota principal servindo app.blade.php
├── composer.json            # Dependências Laravel
├── package.json             # Dependências Vue 3 + shadcn-vue + Tailwind
├── tailwind.config.js       # Configuração Tailwind com tokens do design system
└── vite.config.js           # Configuração Vite com plugin Vue
```

**Structure Decision**: Monorepo Laravel integrado servindo o frontend Vue 3 através do Vite, com design system baseado em componentes reutilizáveis do shadcn-vue em `resources/js/components/ui/`.

## Complexity Tracking

> Nenhuma violação constitucional. A estrutura de componentes e modais do shadcn-vue atende perfeitamente à necessidade de criar uma interface moderna, acessível e de alta qualidade visual para a validação da proposta extensionista.
