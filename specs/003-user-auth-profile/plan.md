# Implementation Plan: Fluxo de Autenticação e Gestão Cadastral do Cidadão (user-auth-profile)

**Branch**: `003-user-auth-profile` | **Date**: 2026-11-03 | **Spec**: [spec.md](spec.md)

**Input**: Feature specification from `/specs/003-user-auth-profile/spec.md`

---

## Summary

Esta funcionalidade estabelece o núcleo de autenticação, identificação comunitária e gestão cadastral do munícipe na plataforma **Voz do Bairro**. A implementação abrange:
1. Validação de CEP via serviço **OpenCEP** com preenchimento híbrido (automático ao digitar 8 dígitos + botão manual de busca) e suporte a fallback assistido.
2. Persistência de localização através de uma tabela `addresses` polimórfica (`MorphOne` / `MorphTo`), permitindo que a mesma entidade de endereço atenda ao Munícipe agora e aos futuros Reportes de zeladoria.
3. Mecanismo de persistência de sessão de longa duração (*Remember Me*) utilizando cookies seguros nativos do Laravel.
4. Política de senhas de alta complexidade (mínimo 8 caracteres, maiúscula, minúscula, número e símbolo) e hash Bcrypt/Argon2id.
5. Edição cadastral segura (exigindo senha atual para troca de e-mail ou senha) e fluxo de exclusão permanente de conta e endereço.
6. Interface integrada ao ecossistema existente em Vue 3 + `shadcn-vue` (Radix Vue + Tailwind CSS).

---

## Technical Context

**Language/Version**: PHP 8.2+ (PHP 8.5.6 CLI ativo no ambiente) e TypeScript 5+ / JavaScript (Node.js 24.14+)  
**Primary Dependencies**: Laravel Framework 11.x, Vue.js 3.5+, Radix Vue, Tailwind CSS 3.4+, Lucide Icons (`lucide-vue-next`), Guzzle/Laravel HTTP Client  
**Storage**: PostgreSQL (produção) / SQLite / PostgreSQL (local com migrações auditáveis via Laravel Eloquent)  
**Testing**: PHPUnit / Pest (testes de Feature cobrindo autenticação, endereço polimórfico e endpoints de perfil)  
**Target Platform**: Aplicação Web responsiva para navegadores modernos em desktop e mobile  
**Project Type**: Monólito Web Fullstack (Laravel HTTP API/Web Routing + Vue 3 SPA/Components via Vite)  
**Performance Goals**: Tempo de resposta do proxy/serviço OpenCEP < 1.5s; endpoints de autenticação p95 < 200ms  
**Constraints**: 
- Aderência estrita à tipagem forte do PHP (`declare(strict_types=1);`)
- Conformidade de acessibilidade (WCAG) via primitivas Radix Vue
- Validações de entrada estritamente encapsuladas em Form Requests dedicados
- Nenhuma sobre-engenharia desnecessária (princípio YAGNI)
**Scale/Scope**: Validação de endereços em nível nacional com sinalização prioritária para o piloto de Pouso Alegre/MG; 5 fluxos principais (Cadastro, Login com Remember Me, Logout, Edição de Perfil e Exclusão de Conta).

---

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

| Princípio Constitucional | Status | Justificativa / Validação |
|---|:---:|---|
| **I. Spec-Driven Development & Token Economy** | **PASS** | Todas as decisões e contratos estão formalizados em `spec.md`, `research.md`, `data-model.md` e `contracts/` antes da geração de código. |
| **II. Additive Evolution & Zero Regression** | **PASS** | A interface visual da landing page (Spec 002) e o store de sessão são evoluídos de forma aditiva sem quebrar componentes ou estilos anteriores. |
| **III. Automated Verification** | **PASS** | Testes automatizados de Feature do Laravel cobrirão cadastro, login, persistência com Remember Me, proteção de senha e deleção de conta. |
| **IV. Clean Architecture & shadcn-vue** | **PASS** | Backend segmentado em Form Requests, Services e Controllers enxutos; frontend construído com componentes do design system shadcn-vue. |
| **V. Social Utility & ODS Alignment** | **PASS** | Senhas criptografadas com hash forte (RNF01), sem hierarquia de privilégios entre cidadãos, e suporte a cadastro inclusivo com aviso para localidades fora do piloto. |

---

## Project Structure

### Documentation (this feature)

```text
specs/003-user-auth-profile/
├── spec.md              # Especificação de requisitos funcionais e clarificações
├── checklists/          # Checklist de qualidade de requisitos
│   └── requirements.md
├── plan.md              # Este plano de implementação
├── research.md          # Fase 0: Consolidação de decisões arquiteturais
├── data-model.md        # Fase 1: Modelagem relacional e polimórfica (User + Address)
├── contracts/           # Fase 1: Especificação OpenAPI dos endpoints
│   └── auth-profile-api.yaml
├── quickstart.md        # Fase 1: Guia prático de execução e validação
└── tasks.md             # Fase 2: Gerado pelo comando /speckit-tasks
```

### Source Code (repository root)

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php          # Login, Register, Logout, Me
│   │   ├── ProfileController.php       # Update Profile, Update Password, Delete Account
│   │   └── CepController.php           # Consulta e cache do serviço OpenCEP
│   └── Requests/
│       ├── RegisterUserRequest.php     # Validação de cadastro com regras de senha e CEP
│       ├── LoginUserRequest.php        # Validação de credenciais e remember
│       ├── UpdateProfileRequest.php    # Validação de perfil com senha condicional para e-mail
│       ├── UpdatePasswordRequest.php   # Validação de senha atual e nova senha complexa
│       └── DeleteAccountRequest.php    # Confirmação com senha atual
├── Models/
│   ├── User.php                        # Entidade Cidadão com relação morphOne(Address)
│   └── Address.php                     # Entidade Endereço com relação morphTo(addressable)
└── Services/
    └── CepService.php                  # Integração, timeout e fallback da API OpenCEP

database/
└── migrations/
    ├── 2026_11_03_000001_create_users_table.php
    ├── 2026_11_03_000002_create_addresses_table.php
    └── 2026_11_03_000003_create_sessions_table.php

routes/
├── web.php                             # Rota principal para carregar o SPA Blade/Vue
└── api.php                             # Rotas de autenticação, perfil e consulta CEP

resources/
└── js/
    ├── components/
    │   ├── presentation/
    │   │   ├── AuthDialog.vue          # Diálogo de Login (com Remember Me) e Cadastro (com CEP)
    │   │   ├── ProfileDialog.vue       # Diálogo de edição cadastral, senha e exclusão de conta
    │   │   └── Navbar.vue              # Acesso ao perfil e disparo de logout
    │   └── ui/                         # Primitivas shadcn-vue (Input, Button, Checkbox, Dialog)
    ├── stores/
    │   └── sessionStore.ts             # Estado reativo sincronizado com a API real de autenticação
    └── lib/
        └── api.ts                      # Cliente HTTP centralizado (Axios/Fetch) com CSRF token
```

**Structure Decision**: Monólito Web padrão do Laravel 11 integrado com frontend Vue 3 no diretório `resources/js`, aproveitando o build unificado do Vite e as sessões web baseadas em cookies seguros.

---

## Complexity Tracking

> Nenhuma violação das diretrizes da Constituição. A arquitetura mantém separação limpa de responsabilidades sem sobre-engenharia.
