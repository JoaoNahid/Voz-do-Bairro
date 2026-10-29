# Research & Technology Decisions: Project Presentation README

**Feature**: `001-project-readme`
**Status**: Completed

## 1. Document Format & Standards

- **Decision**: GitHub Flavored Markdown (GFM) com renderização universal em leitores padrão e terminais.
- **Rationale**:
  - GFM é o padrão universal para documentação de repositórios em plataformas Git (GitHub, GitLab).
  - Garante total suporte para tabelas, blocos de código com destaque de sintaxe, listas de tarefas e badges externos.
- **Alternatives considered**:
  - *HTML puro*: Maior controle visual, porém prejudica a legibilidade em texto plano e quebra a simplicidade do ecossistema Markdown.
  - *AsciiDoc*: Rico em recursos acadêmicos, porém tem menor suporte nativo no GitHub sem plugins.

## 2. Visual Identity & Badges

- **Decision**: Shields.io badges estáticos para representar a stack (Laravel, Vue, PostgreSQL, Redis) e metadados de governança (MIT License, GitFlow).
- **Rationale**:
  - Shields.io provê badges SVG padronizados, acessíveis, com carregamento rápido e sem dependência de chaves de API pagas.
  - Alinha-se diretamente ao princípio de ferramentas abertas, gratuitas e confiáveis.
- **Alternatives considered**:
  - *Badges dinâmicos complexos*: Desnecessários nesta fase inicial antes da pipeline de CI/CD estar ativa.

## 3. Academic Traceability & SDG Categorization

- **Decision**: Mapeamento tabular direto das 7 ODS da ONU com colunas de número, nome oficial e aplicação direta nas dores urbanas do bairro Recanto dos Fernandes.
- **Rationale**:
  - O projeto é curricular (Atividades Extensionistas III - Análise / Sistemas de Informação).
  - A transparência e o detalhamento das ODS atendem rigorosamente aos critérios avaliativos da faculdade e ao Princípio V da Constituição (Social Utility & ODS Alignment).
- **Alternatives considered**:
  - *Mencionar ODS apenas genericamente em texto corrido*: Rejeitado por diminuir a clareza e o impacto da avaliação acadêmica.

## 4. GitFlow & Remote Governance Alignment

- **Decision**: Documentar no próprio README as diretrizes essenciais de commit híbrido (`<git-emoji> <type>(<scope>): <subject>`) e o remote oficial (`https://github.com/JoaoNahid/Voz-do-Bairro.git`).
- **Rationale**:
  - Assegura onboarding imediato de qualquer colaborador externo sem obrigá-lo a vasculhar múltiplos diretórios para entender o fluxo de contribuição básico.
