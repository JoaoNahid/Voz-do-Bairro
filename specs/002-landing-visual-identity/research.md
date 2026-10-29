# Research & Technical Decisions: Landing Page & Visual Identity Presentation

**Feature**: `002-landing-visual-identity`
**Status**: Completed

## 1. Stack Foundation: Laravel 11 + Vue 3 (Vite)

- **Decision**: Configurar o backend Laravel 11 como host SPA/Blade híbrido servindo o frontend Vue 3 via Vite (`@vitejs/plugin-vue`).
- **Rationale**:
  - Laravel 11 com Vite oferece Hot Module Replacement (HMR) ultrarrápido para desenvolvimento frontend em Vue 3.
  - Prepara a estrutura oficial do projeto sem acoplamento prematuro de banco de dados nesta etapa de apresentação.
  - A rota web principal (`/`) entrega a casca SPA montando o componente raiz Vue no Blade (`resources/views/app.blade.php`).
- **Alternatives considered**:
  - *Inertia.js*: Excelente para etapas com CRUD real e controllers Laravel, mas adiciona complexidade desnecessária nesta fase de pura identidade visual estática/mock.
  - *SPA Vue totalmente isolado sem Laravel*: Rejeitado porque a Constituição e o usuário exigem configurar os frameworks Laravel + Vue já na raiz do repositório.

## 2. Design System & UI Components: shadcn-vue (Radix Vue + Tailwind CSS)

- **Decision**: Adotar os componentes e padrões da biblioteca [shadcn-vue](https://www.shadcn-vue.com/) com `radix-vue`, `tailwind-merge`, `clsx` e `class-variance-authority`.
- **Rationale**:
  - Atende estritamente à nova cláusula da Constituição (v1.2.0).
  - Componentes acessíveis (WAI-ARIA nativo) instalados diretamente no diretório `resources/js/components/ui/`, permitindo customização total de código sem ficar refém de um pacote NPM monolítico opaco.
  - Componentes essenciais necessários para esta entrega:
    - `Button`: Ações de chamada (CTAs), botões de voto e navegação.
    - `Card`: Apresentação estruturada dos relatos urbanos em alta no carrossel.
    - `Carousel`: Slide fluido e responsivo dos 4 eixos de relatos com suporte a touch e botões de navegação.
    - `Dialog / Modal`: Janelas modais para Login/Cadastro, confirmação de voto e formulário expandido de denúncia.
    - `Badge`: Categorização dos eixos de zeladoria (Vias Públicas, Saneamento, Iluminação, Limpeza) e selos de quórum.
    - `Input` & `Textarea`: Formulário rápido e modal de autenticação.
    - `Toast`: Notificação reativa de boas-vindas e feedback de ações.
- **Alternatives considered**:
  - *Bootstrap / Vuetify*: Muito pesados, com estilos engessados e incompatíveis com a diretriz constitucional de shadcn-vue.
  - *CSS puro sem Tailwind*: Maior esforço para manter consistência responsiva em modais e carrossel.

## 3. Visual Identity & Color Palette: Tom Cívico Institucional

- **Decision**: Paleta baseada em tons profundos de azul marinho (`#0f172a` / `#1e3a8a`), branco/gelo (`#f8fafc`) e acentos vibrantes em amarelo/dourado (`#f59e0b` / `#eab308`).
- **Rationale**:
  - Escolhida diretamente na clarificação com o usuário.
  - O azul marinho evoca seriedade institucional, autoridade e segurança comunitária; o dourado/âmbar confere luminosidade e atua como ponto focal de atenção em botões de denúncia e alertas.
  - Configuração via variáveis CSS HSL no `resources/css/app.css` totalmente compatível com o tema shadcn-vue.
- **Alternatives considered**:
  - *Tom Alerta Urbano (preto/laranja)*: Rejeitado na clarificação por parecer sensacionalista em excesso e reduzir a credibilidade acadêmica/institucional.

## 4. Local Imagery & Pouso Alegre Assets

- **Decision**: Curadoria e geração de ativos fotográficos e ilustrações vetoriais otimizadas representando pontos conhecidos de Pouso Alegre (Catedral Metropolitana, vias da cidade, bairros como Recanto dos Fernandes, Centro, praças e áreas verdes) integrados em `public/images/`.
- **Rationale**:
  - Conecta a interface diretamente com o morador local, gerando sensação imediata de pertencimento e realismo.
- **Alternatives considered**:
  - *Placeholders genéricos*: Proibidos pelas diretrizes de desenvolvimento da plataforma.
