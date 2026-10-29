# 📢 Voz do Bairro

> **Sistema para a participação cidadã em soluções de infraestrutura local.**

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](#)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg?logo=laravel)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg?logo=vuedotjs)](https://vuejs.org)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-blue.svg?logo=postgresql)](https://www.postgresql.org)
[![Redis](https://img.shields.io/badge/Redis-Cache%20%26%20Queues-dc382d.svg?logo=redis)](https://redis.io)
[![GitFlow](https://img.shields.io/badge/GitFlow-Standard-orange.svg)](#)

---

## 🎓 Contexto Acadêmico

Este projeto integra o trabalho final de curso no âmbito das **Atividades Extensionistas**, promovendo a articulação direta entre a formação universitária e a solução de problemas reais da comunidade.

- **Curso**: Bacharelado em Sistemas de Informação
- **Disciplina**: Atividade Extensionista III: Tecnologia Aplicada à Inclusão Digital – Análise
- **Etapa**: Validação da Proposta / Trabalho Final
- **Alunos Responsáveis**:
  - **Ariane Barbosa Sóllner Vieira** — RU: `4880805`
  - **João Eduardo de Godoi Nahid** — RU: `4896259`
- **Setor de Aplicação**:
  - O projeto será inicialmente aplicado no **bairro Recanto dos Fernandes em Pouso Alegre, Minas Gerais**, estruturado para expansão subsequente para todos os bairros do município e cidades vizinhas.

---

## 🎯 Objetivos do Projeto

1. **Engajamento Cívico**: Criar uma plataforma online intuitiva em Laravel para incentivar os cidadãos a participarem ativamente no apontamento e na priorização de demandas de zeladoria e infraestrutura urbana.
2. **Dados Estruturados para Gestão**: Gerar relatórios e informações devidamente categorizadas para auxiliar no planejamento e na rápida execução de intervenções de manutenção pelo poder público e associações comunitárias.
3. **Fortalecimento Comunitário**: Fomentar o senso de pertencimento e colaboração comunitária dentro do bairro, garantindo que qualquer morador possa relatar insatisfações e propor soluções de forma transparente.

---

## 🌐 Alinhamento com as ODS da ONU

O **Voz do Bairro** alinha seu propósito social aos **Objetivos de Desenvolvimento Sustentável (ODS)** da Agenda 2030 das Nações Unidas:

| ODS | Objetivo | Impacto no Projeto |
| :---: | :--- | :--- |
| **03** | **Saúde e Bem-estar** | Identificação ágil de focos de esgoto a céu aberto, lixo acumulado e riscos sanitários locais. |
| **06** | **Água Potável e Saneamento** | Apontamento de vazamentos de água e carências nas redes de abastecimento e saneamento básico. |
| **09** | **Indústria, Inovação e Infraestrutura** | Aplicação de inovação digital e arquitetura moderna para mapear melhorias em vias públicas, pontes e iluminação. |
| **10** | **Redução das Desigualdades** | Plataforma inclusiva e gratuita que dá voz a bairros periféricos e comunidades historicamente desassistidas. |
| **11** | **Cidades e Comunidades Sustentáveis** | Gestão urbana participativa, tornando os bairros mais resilientes, seguros, limpos e sustentáveis. |
| **16** | **Paz, Justiça e Instituições Eficazes** | Transparência comunitária, acompanhamento público de demandas e prestação de contas mais eficaz. |
| **17** | **Parcerias e Meios de Implementação** | Articulação entre universidade, cidadãos, lideranças comunitárias e poder público municipal. |

---

## 📋 Escopo e Requisitos

### Requisitos Funcionais (RF)
- **RF01: Cadastro**: Cadastro simples e seguro de novos munícipes informando o bairro de residência.
- **RF02: Login / Logout**: Mecanismo de autenticação e encerramento de sessão seguro.
- **RF03: Criação de Reports**: Munícipes autenticados podem relatar problemas de infraestrutura local para o seu respectivo bairro.
- **RF04: Votação Comunitária**: Usuários podem votar em relatórios criados por outros vizinhos para expressar concordância e prioridade.
- **RF05: Relevância Pública (Quórum de 60%)**: O sistema calcula dinamicamente o quórum de votos considerando o total de moradores cadastrados no bairro. **Quando um relato alcança mais de 60% de votos, ele é promovido à página principal do sistema como demanda prioritária pública.**

### Requisitos Não Funcionais (RNF)
- **RNF01: Segurança (Criptografia)**: Senhas de usuários e dados sensíveis protegidos com hashes criptográficos fortes (Argon2id/Bcrypt).
- **RNF02: Usabilidade & Inclusão**: Interface limpa, responsiva e acessível para munícipes de diversas faixas etárias e níveis de letramento digital.
- **RNF03: Escalabilidade**: Estrutura modular preparada para escalonar horizontalmente para novos bairros e cidades.
- **RNF04: Manutenibilidade Operacional**: Código limpo, desacoplado e documentado que permite fácil manutenção por desenvolvedores externos à equipe inicial.
- **RNF05: Disponibilidade**: Sistema operando em nuvem com alta disponibilidade (24/7).

### Modelo Comunitário Autogerido
A fim de simplificar o fluxo operacional e manter o sistema participativo e orgânico, **não há hierarquia burocrática de usuários**. Todos os cidadãos participam de forma igualitária como usuários comuns, validando de maneira descentralizada a relevância das demandas do seu próprio bairro.

---

## 🛠️ Stack Tecnológica & Arquitetura

O projeto adota uma stack robusta, confiável, aberta e de baixo custo de hospedagem:

- **Backend**: [Laravel](https://laravel.com) (PHP 8.2+) adotando separação de responsabilidades (Actions/Services, Form Requests, DTOs e Eloquent otimizado).
- **Frontend**: [Vue.js 3](https://vuejs.org) (Composition API, `<script setup>`, design responsivo e acessível).
- **Banco de Dados**: [PostgreSQL](https://www.postgresql.org) com modelo relacional normalizado, índices estratégicos e migrações incrementais auditáveis.
- **Otimização & Performance**:
  - **Redis**: Cacheamento de consultas de alta frequência e contadores em tempo real.
  - **Filas & Mensageria**: Processamento assíncrono para notificações e recálculos periódicos de relevância de votos.
- **Engenharia de Software**:
  - **Spec-Driven Development (SDD)**: Ciclo formal de especificação antes do desenvolvimento de novas funcionalidades.
  - **Testes Automatizados**: Suíte de testes no backend (PHPUnit/Pest) e testes de componentes para validação e garantia de não-regressão.
  - **Economia de Tokens**: Documentação enxuta, modular e organizada para otimização de agentes de IA e engenharia contínua.

---

## 🌿 Fluxo Git & Padrões de Commit

### Modelo GitFlow
O versionamento do projeto adota estritamente o GitFlow:
- `master`: Código estável pronto para entrega/produção.
- `develop`: Branch de integração contínua das novas funcionalidades.
- `feature/<nome-da-feature>`: Branches de desenvolvimento de novas funcionalidades (originadas de `develop`).
- `bugfix/<nome-do-bug>`: Correções em ambiente de desenvolvimento.
- `hotfix/<nome-do-hotfix>`: Correções emergenciais em produção diretamente a partir de `master`.

### Convenção Híbrida de Commits
As mensagens de commit combinam o padrão da **Comissão Europeia (EC Component Library)** com **Git Commit Emojis**:

```text
<git-emoji> <type>(<scope>): <subject>
```

#### Exemplos:
- `:sparkles: feat(auth): add user registration with neighborhood selection`
- `:bug: fix(report): prevent duplicate user votes on reports`
- `:white_check_mark: test(quorum): validate 60-percent threshold calculation`
- `:memo: docs(readme): add project presentation and extensionist details`
- `:recycle: refactor(report): extract vote quorum verification into dedicated action`
- `:zap: perf(cache): cache top community reports in redis`

---

## 🔗 Repositório Oficial

- **GitHub**: [https://github.com/JoaoNahid/Voz-do-Bairro.git](https://github.com/JoaoNahid/Voz-do-Bairro.git)

```bash
# Clonar o repositório
git clone https://github.com/JoaoNahid/Voz-do-Bairro.git
cd voz-do-bairro

# Configurar o remoto (caso esteja iniciando localmente)
git remote add origin https://github.com/JoaoNahid/Voz-do-Bairro.git
```

---

## 📜 Governança e Especificações

O projeto segue as diretrizes estabelecidas na [Constituição do Projeto (.specify/memory/constitution.md)](.specify/memory/constitution.md) e as especificações criadas via Spec Kit no diretório `specs/`.
