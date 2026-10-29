# Data Model: Project Presentation README

**Feature**: `001-project-readme`
**Date**: 2026-10-28

Este artefato define as entidades conceituais e os blocos de dados que compõem a apresentação do projeto Voz do Bairro.

## Entidades Conceituais

### 1. AcademicMetadata
Representa a contextualização universitária e institucional do projeto extensionista.

- **Campos**:
  - `course`: String (ex.: "Bacharelado em Sistemas de Informação")
  - `discipline`: String (ex.: "Atividade Extensionista III: Tecnologia Aplicada à Inclusão Digital – Análise")
  - `stage`: String (ex.: "Validação da proposta / Trabalho final")
  - `authors`: Array<{ name: String, ru: String }>
    - `Ariane Barbosa Sóllner Vieira`, RU: `4880805`
    - `João Eduardo de Godoi Nahid`, RU: `4896259`
  - `application_area`: Objeto
    - `initial_neighborhood`: "Recanto dos Fernandes"
    - `city`: "Pouso Alegre"
    - `state`: "MG"
    - `expansion_target`: "Bairros do município e cidades adjacentes"

---

### 2. SDGLink (Alinhamento ODS)
Representa a correlação do projeto com as metas da Agenda 2030 das Nações Unidas.

- **Campos**:
  - `code`: Number (ex.: 3, 6, 9, 10, 11, 16, 17)
  - `title`: String (Nome oficial da ODS)
  - `community_impact`: String (Aplicação prática no contexto de zeladoria e cidadania urbana)
- **Instâncias Obrigatórias**:
  - ODS 03: Saúde e Bem-Estar
  - ODS 06: Água Potável e Saneamento
  - ODS 09: Indústria, Inovação e Infraestrutura
  - ODS 10: Redução das Desigualdades
  - ODS 11: Cidades e Comunidades Sustentáveis
  - ODS 16: Paz, Justiça e Instituições Eficazes
  - ODS 17: Parcerias e Meios de Implementação

---

### 3. RequirementCatalog (Catálogo de Requisitos)
Representa os requisitos funcionais e não-funcionais estabelecidos no documento extensionista.

- **Campos dos Requisitos Funcionais**:
  - `code`: String (RF01 a RF05)
  - `name`: String (ex.: "Cadastro", "Relevância Pública")
  - `rule`: String (ex.: Quórum de 60% de votos comunitários para publicação na Home)
- **Campos dos Requisitos Não-Funcionais**:
  - `code`: String (RNF01 a RNF05)
  - `type`: String (Segurança, Usabilidade, Escalabilidade, Manutenibilidade, Disponibilidade)
  - `criteria`: String

---

### 4. TechnicalGovernance
Representa as definições técnicas, arquiteturais e de repositório.

- **Campos**:
  - `backend`: "Laravel 11.x (PHP 8.2+)"
  - `frontend`: "Vue.js 3 (Composition API)"
  - `database`: "PostgreSQL 16"
  - `optimization`: "Redis (Cache & Queues)"
  - `git_model`: "GitFlow"
  - `remote_url`: "https://github.com/JoaoNahid/Voz-do-Bairro.git"
  - `commit_convention`: "<git-emoji> <type>(<scope>): <subject>"
