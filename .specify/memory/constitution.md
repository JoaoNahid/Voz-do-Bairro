# Voz do Bairro Constitution

## Core Principles

### I. Spec-Driven Development (SDD) & Token Economy
O desenvolvimento do projeto DEVE ser estritamente orientado a especificações formais antes de qualquer linha de código de produção. Todo fluxo de trabalho DEVE seguir o ciclo de vida: Especificação (`spec.md`) → Planejamento (`plan.md`) → Tarefas (`tasks.md`) → Implementação guiada por testes.
- Toda modificação de sistema DEVE estar ancorada em artefatos de especificação validados.
- Documentos de especificação, planos e prompts DEVEM priorizar concisão, objetividade e clareza para otimizar o consumo de contexto e tokens de IA, eliminando redundâncias e arquivos monolíticos.
- Decisões de design devem ser registradas nos artefatos correspondentes antes da execução.

### II. Additive Evolution & Zero Regression
Todas as alterações no código de produção e na camada de persistência DEVEM ser estritamente aditivas e retrocompatíveis.
- Em refatorações ou expansões de uma funcionalidade existente, as demais funcionalidades NÃO PODEM quebrar sob nenhuma hipótese.
- Migrações de banco de dados e contratos de serviços DEVEM manter compatibilidade com versões ativas ou prever estratégias transparentes de transição.
- Remoção ou alteração destrutiva de endpoints ou interfaces públicas requer análise de impacto prévia e ciclo formal de depreciação.

### III. Automated Verification & Non-Regression Testing
A qualidade do software DEVE ser assegurada por testes automatizados implementados para validar o que foi desenvolvido e garantir que refatorações não degradem o sistema.
- O projeto NÃO adota Test-Driven Development (TDD) como metodologia base: a implementação das funcionalidades precede a criação dos testes, os quais são desenvolvidos para validar e consolidar o comportamento esperado do código implementado.
- Testes automatizados DEVEM ser construídos para cobrir regras de negócio essenciais (autenticação, registro de reportes por bairro, cálculo do quórum de 60% para exibição pública na página principal e prevenção de votos duplicados).
- O backend DEVE utilizar a suíte de testes do Laravel (PHPUnit/Pest) para testes de Feature e Unitários, e o frontend DEVE validar componentes críticos com Vitest/Vue Test Utils.
- Nenhum código DEVE ser integrado aos branches de integração (`develop`) ou produção (`master`) com testes automatizados falhando.

### IV. Clean Architecture, Clean Code & Design Patterns
A estrutura do código DEVE aplicar os fundamentos de Clean Architecture e Clean Code, priorizando separação clara de responsabilidades, legibilidade e manutenibilidade por terceiros (RNF04).
- O backend em Laravel DEVE evitar o acoplamento excessivo em Controllers e Views: regras de negócio residem em Use Cases/Actions e Domain Services independentes; requisições DEVEM ser validadas via Form Requests dedicados; transferência de dados deve utilizar DTOs tipados.
- O código DEVE seguir padrões de projeto consolidados (Factory, Repository/Query Objects, Strategy, Observer) apenas onde agregarem simplificação e desacoplamento, sem sobre-engenharia desnecessária (YAGNI).
- O frontend em Vue DEVE ser modular, reativo, com componentes reutilizáveis, gerenciamento de estado previsível e estilização clara e intuitiva (RNF02).

### V. Social Utility & ODS Alignment (UN 2030 Agenda)
O sistema Voz do Bairro DEVE manter como propósito fundamental a utilidade pública e a inclusão social no contexto das Atividades Extensionistas e dos Objetivos de Desenvolvimento Sustentável (ODS) da ONU.
- Toda funcionalidade DEVE apoiar diretamente os objetivos pactuados no projeto: ODS 3 (Saúde e Bem-Estar), ODS 6 (Água Potável e Saneamento), ODS 9 (Indústria, Inovação e Infraestrutura), ODS 10 (Redução das Desigualdades), ODS 11 (Cidades e Comunidades Sustentáveis), ODS 16 (Paz, Justiça e Instituições Eficazes) e ODS 17 (Parcerias e Meios de Implementação).
- O sistema DEVE garantir acessibilidade e usabilidade para cidadãos de diversas faixas etárias, operando com total transparência comunitária e descentralizada, sem privilégios indevidos entre munícipes.
- Segurança e privacidade dos cidadãos são mandatórias: senhas e dados sensíveis DEVEM utilizar algoritmos criptográficos robustos de hash (RNF01) e proteção rigorosa de dados pessoais.

## Technology Stack & Scalability Architecture

### Core Stack
- **Backend**: Laravel (PHP 8.2+) configurado com tipagem estrita (`declare(strict_types=1);`), middlewares de segurança, Form Requests e arquitetura orientada a serviços e ações.
- **Frontend**: Vue.js 3 utilizando Composition API e script setup, priorizando reatividade moderna, alta responsividade e interface acessível.
- **Banco de Dados**: PostgreSQL com modelagem relacional normalizada, chaves estrangeiras, índices adequados e migrations incrementais estritamente auditadas.

### Scalability, Optimization & Asynchronous Services
- **Cache & Sessão**: Redis DEVE ser empregado para cache de dados de alta leitura (ex.: listagem de bairros, relatórios consolidados e contadores em tempo real) e gerenciamento otimizado de sessões.
- **Processamento Assíncrono**: Tarefas pesadas (cálculos periódicos de relevância de votos, envio de notificações e comunicações transacionais) DEVEM ser delegadas para filas e mensageria assíncrona (RabbitMQ ou Laravel Queues com Redis).
- **Eficiência de Custos e Disponibilidade**: Devem ser priorizadas tecnologias abertas, confiáveis e gratuitas para viabilizar hospedagem resiliente e alta disponibilidade contínua (RNF05).

## Development Workflow, GitFlow & Commit Standards

### Git Repository & Remotes
- **Repositório Oficial**: `https://github.com/JoaoNahid/Voz-do-Bairro.git`
- **Configuração de Origem**:
  ```bash
  git remote add origin https://github.com/JoaoNahid/Voz-do-Bairro.git
  ```
- O repositório remoto oficial centraliza o ciclo de vida do código, hospedando os branches de longa duração (`master` e `develop`), além de releases e tags de versão.

### GitFlow Model
O versionamento de código DEVE seguir rigorosamente o modelo GitFlow:
- `master`: Branch de produção contendo apenas código estável e pronto para entrega.
- `develop`: Branch de integração contínua contendo as últimas alterações validadas.
- `feature/<nome-da-funcionalidade>`: Criada a partir de `develop` e integrada exclusivamente a `develop` após revisão e testes aprovados.
- `bugfix/<nome-da-correcao>`: Criada a partir de `develop` para correções durante o ciclo de desenvolvimento.
- `release/<versao>`: Criada a partir de `develop` para preparação de nova versão e mesclada tanto em `master` quanto em `develop`.
- `hotfix/<nome-do-hotfix>`: Criada diretamente a partir de `master` para correções críticas imediatas em produção.

### Hybrid Commit Message Convention
Todas as mensagens de commit DEVEM integrar o padrão da Comissão Europeia (EC Component Library) com a convenção semântica de Git Commit Emojis:
- **Estrutura obrigatória**: `<git-emoji> <type>(<scope>): <subject>`
  - Exemplo com escopo: `:sparkles: feat(reports): implement 60-percent vote threshold filter`
  - Exemplo sem escopo: `:bug: fix: prevent duplicate user vote on community report`
- **Tabela de Tipos e Emojis Padrão**:
  - `:sparkles:` `feat`: Nova funcionalidade para o usuário ou sistema.
  - `:bug:` `fix`: Correção de um bug ou comportamento inesperado.
  - `:memo:` `docs`: Alterações exclusivamente em documentação ou especificações.
  - `:art:` / `:lipstick:` `style`: Ajustes visuais, formatação ou estilo de código sem impacto na lógica.
  - `:recycle:` `refactor`: Refatoração de código que não altera comportamento nem corrige bugs.
  - `:zap:` `perf`: Melhoria de desempenho, queries ou consumo de memória.
  - `:white_check_mark:` `test`: Criação ou atualização de testes automatizados.
  - `:wrench:` `chore`: Tarefas de manutenção, ferramentas, dependências ou infraestrutura de build/ci.
  - `:lock:` `security`: Correções ou melhorias de segurança e proteção de dados.

### Quality Gates
- Nenhum commit direto é permitido nas branches `master` ou `develop`.
- Todo Pull Request DEVE conter descrição clara do escopo, referência à especificação de origem e validação de 100% dos testes automatizados e linters.

## Governance

A presente Constituição é o documento de governança técnica supremo do projeto Voz do Bairro. Suas diretrizes prevalecem sobre quaisquer opiniões informais, atalhos técnicos ou convenções ad-hoc.
- **Validação de Conformidade**: Cada especificação de funcionalidade gerada pelo Spec Kit, cada plano arquitetural e cada Pull Request DEVE ser checado contra as regras desta Constituição.
- **Procedimento de Emendas**: A Constituição pode ser emendada mediante solicitação explícita fundamentada, exigindo a inclusão de um Sync Impact Report no topo do documento antes da homologação.
- **Versionamento Semântico**:
  - **MAJOR (x.0.0)**: Remoção, inversão ou reestruturação de princípios fundamentais ou mudanças drásticas na stack central.
  - **MINOR (1.x.0)**: Adição de novos princípios, inclusão de novas ferramentas de suporte arquitetural ou expansão de fluxos de governança.
  - **PATCH (1.0.x)**: Clarificações textuais, refinamentos ortográficos e correções de formatação não-semânticas.

**Version**: 1.1.0 | **Ratified**: 2026-10-28 | **Last Amended**: 2026-10-28
