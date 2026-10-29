# Quickstart & Validation Guide: Landing Page & Visual Identity

**Feature**: `002-landing-visual-identity`
**Date**: 2026-10-28

Este guia instrui a instalação, execução local e validação interativa da Landing Page do Voz do Bairro construída em Laravel + Vue 3 com shadcn-vue.

---

## 1. Pré-requisitos do Ambiente

- **PHP**: >= 8.2 (instalado: PHP 8.5.6)
- **Composer**: >= 2.x
- **Node.js**: >= 18.x (instalado: v24.14.1)
- **NPM**: >= 9.x

---

## 2. Comandos de Inicialização Local

```bash
# 1. Instalar dependências PHP do Laravel
composer install

# 2. Configurar arquivo de ambiente (caso não exista)
test -f .env || cp .env.example .env
php artisan key:generate

# 3. Instalar dependências JavaScript/Vue
npm install

# 4. Executar o servidor de desenvolvimento frontend (Vite)
npm run dev

# 5. Em outro terminal, iniciar o servidor de aplicação Laravel
php artisan serve
```

A aplicação estará disponível em `http://127.0.0.1:8000`.

---

## 3. Roteiro de Validação Visual & Interativa

### Cenário 1: Impacto no Hero Banner (User Story 1 - P1)
- Acesse `http://127.0.0.1:8000`.
- Verifique se a paleta Cívica Institucional (azul marinho `#0f172a` e dourado `#f59e0b`) e o slogan de impacto sobre os bairros de Pouso Alegre são renderizados no topo.
- Clique no botão "Ver Problemas em Alta" e verifique a rolagem suave até a seção de carrossel.

### Cenário 2: Votação em Relatos no Modo Deslogado (User Story 2 - P2)
- Certifique-se de que o seletor na barra de navegação indica "Visitante (Deslogado)".
- No carrossel de denúncias, clique em "Relevante" ou "Irrelevante" em qualquer card.
- **Resultado Esperado**: O Modal de Autenticação (Login / Cadastro) abre instantaneamente na tela.
- Submeta o modal (preencha dados fictícios ou clique em Entrar):
  - O modal deve fechar.
  - A sessão deve mudar reativamente para "Morador Autenticado".
  - Um Toast de boas-vindas com o nome do cidadão deve surgir no canto da tela.

### Cenário 3: Votação em Relatos no Modo Logado (User Story 2 - P2)
- Com o estado em "Morador Autenticado", clique novamente em "Relevante" em um card do carrossel.
- **Resultado Esperado**: Abre-se um diálogo/popup de confirmação de voto ("Deseja confirmar seu voto de relevância?").

### Cenário 4: Criação Rápida de Relato (User Story 3 - P3)
- Na seção inferior de reporte rápido, preencha o Título e a Descrição resumida.
- Se o modo for "Logado", clique em "Continuar Denúncia":
  - **Resultado Esperado**: Abre-se o modal do formulário completo de ocorrência urbana preenchido com os dados iniciais.
- Alterne o modo para "Deslogado" no navbar e clique novamente:
  - **Resultado Esperado**: Abre-se o modal de autenticação.

### Cenário 5: Responsividade Móvel
- Abra o DevTools (F12) e teste a visualização em 375px (iPhone) e 768px (Tablet).
- O carrossel deve permitir navegação por toque (swipe) e os botões devem empilhar de forma elegante sem rolagem horizontal.
