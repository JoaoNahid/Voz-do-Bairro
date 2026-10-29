# Data Model: Landing Page & Visual Identity Presentation

**Feature**: `002-landing-visual-identity`
**Date**: 2026-10-28

Este artefato define as estruturas de dados no frontend (TypeScript / Reatividade Vue 3) que suportam a apresentação visual e os fluxos interativos da Landing Page.

## Entidades e Modelos de Frontend

### 1. ReportItem (Relato de Zeladoria em Alta)
Representa um card de denúncia exibido no carrossel de relatos urbanos.

- **Campos**:
  - `id`: String (Identificador único, ex.: `"rep-001"`)
  - `title`: String (Título conciso do problema, ex.: `"Cratera na Av. Vicente Simões provocando risco a pedestres"`)
  - `category`: Enum (Um dos 4 eixos definidos):
    - `"vias"` (Vias Públicas & Pavimentação)
    - `"saneamento"` (Saneamento Básico & Água)
    - `"iluminacao"` (Iluminação Pública)
    - `"limpeza"` (Limpeza Urbana & Praças)
  - `neighborhood`: String (Bairro de Pouso Alegre, ex.: `"Recanto dos Fernandes"`, `"Centro"`, `"São Geraldo"`, `"Fátima"`)
  - `street`: String (Referência de rua/avenida)
  - `date`: String (Data amigável do relato, ex.: `"Há 2 dias"`)
  - `imageUrl`: String (Caminho da imagem representativa em `/images/reports/`)
  - `votesRelevant`: Number (Contagem de votos relevantes)
  - `votesIrrelevant`: Number (Contagem de votos irrelevantes)
  - `quorumPercentage`: Number (Cálculo do quórum de votos em relação ao bairro)
  - `isPublicPriority`: Boolean (`true` se quórum > 60%)

---

### 2. UserSessionState (Estado Reativo da Sessão)
Gerencia o estado simulado do morador para demonstração interativa.

- **Campos**:
  - `isAuthenticated`: Boolean (Padrão: `false` para visitantes anônimos; `true` para morador logado)
  - `user`: Objeto | `null`
    - `name`: String (ex.: `"Carlos Eduardo Silva"`)
    - `email`: String (ex.: `"carlos.silva@exemplo.com"`)
    - `neighborhood`: String (ex.: `"Recanto dos Fernandes"`)
    - `city`: String (`"Pouso Alegre, MG"`)

---

### 3. ModalState (Controle de Modais e Diálogos)
Controla a exibição dos modais baseados em `Dialog` do shadcn-vue.

- **Campos**:
  - `authModalOpen`: Boolean (Modal de Login / Cadastro)
  - `authModalTab`: `"login"` | `"register"` (Aba ativa)
  - `voteConfirmModalOpen`: Boolean (Modal de confirmação de voto)
  - `activeVotePayload`: Objeto | `null` (Dados do relato e tipo de voto selecionado)
  - `fullReportModalOpen`: Boolean (Modal com formulário expandido de denúncia)
  - `quickReportDraft`: Objeto
    - `title`: String
    - `description`: String

---

### 4. ToastPayload (Notificações Reativas)
Notificações contextuais disparadas após ações simuladas.

- **Campos**:
  - `title`: String (ex.: `"Bem-vindo ao Voz do Bairro!"`)
  - `description`: String (ex.: `"Sessão iniciada como morador do Recanto dos Fernandes."`)
  - `type`: `"default"` | `"success"` | `"warning"`
