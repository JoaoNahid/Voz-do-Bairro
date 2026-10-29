# Feature Specification: Project Presentation README

**Feature Branch**: `001-project-readme`

**Created**: 2026-10-28

**Status**: Ready for Review

**Input**: User description: "Crie um readme.md para apresentar este projeto. Ele deve contemplar o que está definido na elaboração do tema do projeto acadêmico em '/home/nahid/Documentos/atvExtensionistaIII/Atividades Extensionistas III - Tema.pdf'"

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Compreensão Institucional e Propósito Social do Projeto (Priority: P1)

Como avaliador acadêmico, munícipe ou novo membro da equipe, desejo acessar a página inicial do repositório e compreender de imediato o tema, os autores, o contexto universitário das Atividades Extensionistas III e o objetivo social de participação cidadã do sistema Voz do Bairro.

**Why this priority**: Estabelece o cartão de visitas institucional e o propósito fundamental do projeto como iniciativa de impacto social comunitário, exigência direta da validação da proposta de extensão acadêmica.

**Independent Test**: Pode ser validado através da leitura da seção de apresentação do README.md, garantindo que os objetivos gerais, área inicial de aplicação (Recanto dos Fernandes, Pouso Alegre/MG) e dados acadêmicos estejam expressos de forma clara e profissional.

**Acceptance Scenarios**:

1. **Given** um visitante acessando a raiz do repositório, **When** ele lê a seção inicial do README, **Then** ele identifica claramente o título do projeto, autoria (Ariane Barbosa Sóllner Vieira e João Eduardo de Godoi Nahid), curso (Sistemas de Informação) e o foco em soluções de infraestrutura local.
2. **Given** um avaliador acadêmico buscando o alinhamento com a Agenda 2030, **When** ele consulta as diretrizes do projeto, **Then** as 7 ODSs da ONU vinculadas (ODS 3, 6, 9, 10, 11, 16 e 17) são visualizadas com sua respectiva relevância.

---

### User Story 2 - Consulta aos Requisitos Funcionais, Não-Funcionais e Regras Comunitárias (Priority: P2)

Como desenvolvedor, mantenedor ou stakeholder, desejo consultar os requisitos de software e o modelo conceitual definidos no projeto (cadastro, login, criação de reports, votação comunitária e quórum de 60% para exibição pública).

**Why this priority**: Define as fronteiras funcionais do sistema, assegurando que o escopo enxuto, autogerido e descentralizado (sem hierarquias complexas de usuários) seja fiel ao planejado na etapa extensionista.

**Independent Test**: Pode ser validado inspecionando a seção de requisitos funcionais (RF01 a RF05) e não funcionais (RNF01 a RNF05) no README.md, verificando consistência com o tema do projeto.

**Acceptance Scenarios**:

1. **Given** um leitor analisando o escopo funcional, **When** ele busca as regras de votação, **Then** encontra a regra de que reportes com mais de 60% dos votos dos usuários totais daquele bairro ganham visibilidade pública na página principal.
2. **Given** um leitor analisando a arquitetura de acesso, **When** ele consulta os perfis de usuário, **Then** compreende que o sistema é autogerido por usuários comuns sem papéis administrativos complexos.

---

### User Story 3 - Guia de Tecnologias, Convenções de Código e Inicialização (Priority: P3)

Como desenvolvedor contribuinte, desejo encontrar orientações sobre a stack tecnológica (Laravel, Vue, PostgreSQL, Redis), modelo de branches (GitFlow), convenção de commits com emojis e link do repositório remoto oficial.

**Why this priority**: Garante que o projeto mantenha os padrões de engenharia e governança acordados na Constituição do projeto (v1.1.0).

**Independent Test**: Pode ser validado seguindo as referências e convenções descritas no README para clonar, commitar e colaborar com o repositório.

**Acceptance Scenarios**:

1. **Given** um desenvolvedor preparando um commit, **When** ele consulta o padrão de mensagens no README, **Then** ele encontra a sintaxe obrigatória `<git-emoji> <type>(<scope>): <subject>` exemplificada.
2. **Given** um novo colaborador verificando o controle de versão, **When** ele acessa o guia de contribuição, **Then** encontra a URL do repositório oficial no GitHub e as regras de GitFlow (`master`, `develop`, `feature/*`).

---

### Edge Cases

- **Acesso em navegadores móveis**: O documento README.md deve ter estrutura em tabelas e títulos legíveis mesmo em telas compactas (responsividade Markdown).
- **Leitura offline ou em terminal de texto**: O documento deve ser legível tanto em renderizadores ricos (GitHub/GitLab) quanto em formato texto puro (sem tags HTML proprietárias quebradas).

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: O README.md DEVE apresentar o título completo do projeto ("Voz do Bairro: Sistema para a participação cidadã em soluções de infraestrutura local").
- **FR-002**: O documento DEVE conter a identificação acadêmica institucional: Curso de Bacharelado em Sistemas de Informação, Disciplina de Atividades Extensionistas III (Tecnologia Aplicada à Inclusão Digital – Análise), alunos responsáveis e seus respectivos RUs (Ariane Barbosa Sóllner Vieira - RU 4880805; João Eduardo de Godoi Nahid - RU 4896259).
- **FR-003**: O documento DEVE detalhar o setor de aplicação inicial (Bairro Recanto dos Fernandes em Pouso Alegre/MG) e o plano de expansão urbana.
- **FR-004**: O documento DEVE listar detalhadamente as 7 ODSs da ONU contempladas na proposta (ODS 03, 06, 09, 10, 11, 16 e 17) com breves contextualizações de impacto.
- **FR-005**: O documento DEVE especificar os objetivos gerais e específicos do projeto (engajamento cívico, categorização de demandas de zeladoria urbana e fomento ao senso de comunidade).
- **FR-006**: O documento DEVE documentar todos os requisitos funcionais (RF01 - Cadastro, RF02 - Login/Logout, RF03 - Criação de Reports, RF04 - Votação, RF05 - Filtro de Relevância com Quórum de 60%).
- **FR-007**: O documento DEVE documentar os requisitos não-funcionais (RNF01 - Segurança/Criptografia, RNF02 - Usabilidade/Inclusão etária, RNF03 - Escalabilidade, RNF04 - Facilidade de manutenção por terceiros, RNF05 - Alta disponibilidade web 24/7).
- **FR-008**: O documento DEVE refletir a decisão de projeto sobre a ausência de hierarquias burocráticas de usuários, mantendo o sistema autogerido e orgânico.
- **FR-009**: O documento DEVE apresentar a stack tecnológica definida (Laravel, Vue, PostgreSQL, Redis, Queues assíncronas).
- **FR-010**: O documento DEVE incluir as convenções de versionamento e contribuição (GitFlow, remote oficial `https://github.com/JoaoNahid/Voz-do-Bairro.git` e padrão híbrido de commit com Git Commit Emojis).

### Key Entities

- **Documento README**: Arquivo raiz do repositório que centraliza a comunicação do projeto para stakeholders acadêmicos, cidadãos e desenvolvedores.
- **Módulo Extensionista**: Representação do escopo acadêmico alinhado à inclusão digital e aos Objetivos de Desenvolvimento Sustentável.
- **Catálogo de Requisitos**: Conjunto formal de RFs e RNFs que guiam a implementação do sistema Voz do Bairro.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: 100% dos tópicos e seções presentes no documento oficial de validação de tema da Atividade Extensionista III estão integrados no README.md.
- **SC-002**: O documento é validado sem nenhum erro de sintaxe Markdown ou links quebrados.
- **SC-003**: Um leitor não técnico consegue compreender o propósito social e as regras comunitárias de votação e publicação em menos de 3 minutos de leitura.
- **SC-004**: Um desenvolvedor consegue identificar a stack tecnológica, o repositório remoto oficial e as regras de commit imediatamente através da seção de engenharia.

## Assumptions

- O documento será mantido no diretório raiz do repositório (`/home/nahid/projects/voz-do-bairro/README.md`) para renderização automática na página principal do GitHub.
- A linguagem principal do README.md é o Português (pt-BR), em consonância com a universidade, o setor de aplicação local (Pouso Alegre, MG) e a comunidade de usuários.
- O documento não substitui a Constituição (.specify/memory/constitution.md), mas funciona como porta de entrada pública e acessível.
