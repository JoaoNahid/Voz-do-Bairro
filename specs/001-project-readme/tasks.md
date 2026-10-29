# Tasks: Project Presentation README

**Feature**: `001-project-readme`
**Status**: Completed
**Spec**: [specs/001-project-readme/spec.md](spec.md) | **Plan**: [specs/001-project-readme/plan.md](plan.md)

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Definição da estrutura de apresentação e metadados base

- [X] T001 [P] Define presentation schema contract in specs/001-project-readme/contracts/readme-schema.json
- [X] T002 [P] Configure visual badges (Laravel, Vue, PostgreSQL, Redis, GitFlow, MIT) in README.md

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Estrutura nuclear do documento de apresentação pública

**⚠️ CRITICAL**: Pré-requisitos para estruturação das seções temáticas

- [X] T003 Establish project title, slogan and core section hierarchy in README.md
- [X] T004 Register official repository URLs and governance references in README.md

**Checkpoint**: Estrutura base de cabeçalho e rodapé do documento estabelecida.

---

## Phase 3: User Story 1 - Compreensão Institucional e Propósito Social do Projeto (Priority: P1) 🎯 MVP

**Goal**: Apresentar os dados institucionais da faculdade, autores, proposta extensionista e alinhamento com as 7 ODSs da ONU.

**Independent Test**: Verificar se qualquer avaliador identifica claramente autoria (Ariane Barbosa e João Nahid), RUs, setor de aplicação (Recanto dos Fernandes, Pouso Alegre/MG) e a tabela completa das 7 ODSs da ONU no README.md.

### Implementation for User Story 1

- [X] T005 [P] [US1] Document academic extensionist context, course, authors and application area in README.md
- [X] T006 [P] [US1] Document UN SDG alignment table for the 7 project goals (ODS 3, 6, 9, 10, 11, 16, 17) in README.md
- [X] T007 [US1] Document project civic engagement and community objectives in README.md

**Checkpoint**: Apresentação institucional e alinhamento social 100% legíveis e autoexplicativos no README.md.

---

## Phase 4: User Story 2 - Consulta aos Requisitos Funcionais, Não-Funcionais e Regras Comunitárias (Priority: P2)

**Goal**: Detalhar os requisitos de software e o modelo comunitário autogerido e descentralizado.

**Independent Test**: Confirmar a presença dos requisitos RF01-RF05 (destaque para a regra de quórum de 60% de votos comunitários), RNF01-RNF05 e a decisão de igualdade de papéis entre usuários no README.md.

### Implementation for User Story 2

- [X] T008 [P] [US2] Document functional requirements catalog (RF01 to RF05) with 60-percent quorum rule in README.md
- [X] T009 [P] [US2] Document non-functional requirements (RNF01 to RNF05) and security hashing in README.md
- [X] T010 [US2] Document decentralized community model without user hierarchy in README.md

**Checkpoint**: Escopo funcional e regras de zeladoria urbana comunitária consolidados no README.md.

---

## Phase 5: User Story 3 - Guia de Tecnologias, Convenções de Código e Inicialização (Priority: P3)

**Goal**: Orientar novos contribuidores quanto à stack, convenção de GitFlow, formato de commits e comandos de clone.

**Independent Test**: Executar os comandos de inicialização e verificar se as diretrizes de commit e branching estão devidamente documentadas no README.md.

### Implementation for User Story 3

- [X] T011 [P] [US3] Document core technology stack (Laravel, Vue, PostgreSQL, Redis, Queues) in README.md
- [X] T012 [P] [US3] Document GitFlow model and branching rules in README.md
- [X] T013 [P] [US3] Document hybrid commit message convention with emoji examples in README.md
- [X] T014 [US3] Document official repository cloning and remote setup instructions in README.md

**Checkpoint**: Onboarding técnico e diretrizes de contribuição completos no README.md.

---

## Phase 6: Polish & Cross-Cutting Concerns

**Purpose**: Validação de qualidade, conformidade com contratos e renderização

- [X] T015 [P] Validate README.md against documentation schema in specs/001-project-readme/contracts/readme-schema.json
- [X] T016 Execute validation test commands defined in specs/001-project-readme/quickstart.md
- [X] T017 [P] Review Markdown rendering, table alignment and mobile readability in README.md

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: Sem dependências prévias — inicialização imediata.
- **Foundational (Phase 2)**: Depende do Setup — estrutura a espinha dorsal do documento.
- **User Stories (Phase 3+)**: Dependem da conclusão da Fase Fundamental.
  - Podem ser desenvolvidas de forma incremental (P1 → P2 → P3) ou em paralelo por seções.
- **Polish (Phase 6)**: Depende da finalização de todas as histórias de usuário.

### User Story Dependencies

- **User Story 1 (P1)**: Independente; estabelece o contexto acadêmico institucional e ODSs.
- **User Story 2 (P2)**: Independente da US3; depende apenas da espinha dorsal (Fase 2).
- **User Story 3 (P3)**: Independente da US2; orienta os contribuidores e desenvolvedores.

### Parallel Opportunities

- Tarefas T001 e T002 podem ser executadas em paralelo.
- Na Fase 3 (US1), T005 e T006 podem ser elaboradas em paralelo.
- Na Fase 4 (US2), T008 e T009 podem ser estruturadas em paralelo.
- Na Fase 5 (US3), T011, T012 e T013 podem ser desenvolvidas simultaneamente.
- Na Fase 6, T015 e T017 são tarefas paralelas de inspeção.

---

## Parallel Example: User Story 1

```bash
# Executar simultaneamente a documentação acadêmica e o mapeamento de ODSs:
Task: "Document academic extensionist context, course, authors and application area in README.md"
Task: "Document UN SDG alignment table for the 7 project goals in README.md"
```

---

## Implementation Strategy

### MVP First (User Story 1 Only)

1. Executar Fase 1: Setup
2. Executar Fase 2: Fundações do documento
3. Executar Fase 3: User Story 1 (Apresentação acadêmica e ODSs)
4. Validar apresentação mínima viável

### Incremental Delivery

1. Setup + Fundações → Base pronta
2. User Story 1 → Entrega do MVP institucional
3. User Story 2 → Adição do catálogo de requisitos e regra dos 60%
4. User Story 3 → Adição do guia de tecnologias, GitFlow e commits
5. Polish → Validação via `quickstart.md` e checklist de qualidade
