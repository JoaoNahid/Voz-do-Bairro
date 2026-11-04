# Phase 0 Research: Fluxo de Autenticação e Gestão Cadastral do Cidadão (user-auth-profile)

## Research Context & Objectives

A funcionalidade `003-user-auth-profile` introduz a fundação de identidade e localização da plataforma **Voz do Bairro**. O objetivo desta fase é consolidar as decisões arquiteturais e técnicas para:
1. Validação e consumo da API OpenCEP com resiliência a falhas.
2. Modelagem polimórfica de endereços (`Address`) desacoplada e reutilizável para cidadãos e reportes futuros.
3. Mecanismo de persistência de sessão (*Remember Me*) em Laravel compatível com SPA/Inertia/Vue 3.
4. Política rigorosa de senhas (alta complexidade) e proteção criptográfica (RNF01).
5. Exclusão definitiva de conta com confirmação por senha e limpeza em cascata.

---

## 1. Integração com o Serviço OpenCEP

### Decisão
Implementar um serviço backend encapsulado (`App\Services\CepService`) utilizando `Illuminate\Support\Facades\Http`, exposto via endpoint interno `GET /api/cep/{cep}`, com consulta direta ou proxy com timeout estrito de 3 segundos e fallback graceful na interface frontend.

### Racional
- **Segurança e Rate Limiting**: Centralizar a chamada ou permitir validação no backend previne bloqueios de CORS, permite sanitização do CEP (remoção de hífens/pontos) e possibilita cache em memória/Redis de CEPs já consultados recentemente.
- **Resiliência e Fallback**: Se o OpenCEP retornar timeout (> 3s), status diferente de 200 ou corpo com erro, o sistema retorna status de indisponibilidade tratável no frontend, destravando imediatamente os campos de logradouro, bairro, cidade e estado para digitação manual assistida.
- **Formato da API OpenCEP**: A rota pública `https://opencep.com/v1/{cep}` retorna JSON estruturado no formato:
  ```json
  {
    "cep": "37550-000",
    "logradouro": "Avenida Doutor Lisboa",
    "complemento": "",
    "bairro": "Centro",
    "localidade": "Pouso Alegre",
    "uf": "MG",
    "ibge": "3152501"
  }
  ```

### Alternativas Consideradas
- *Consulta puramente no client-side via `fetch`*: Descartada como única opção para evitar problemas de CORS em certos ambientes e permitir que validações no servidor (Form Requests) também possam conferir a consistência dos dados do endereço.
- *API ViaCEP*: O requisito do usuário estipulou explicitamente a API OpenCEP.

---

## 2. Modelagem Polimórfica da Tabela de Endereço (`Address`)

### Decisão
Criar a migration da tabela `addresses` utilizando o padrão polimórfico do Eloquent (`addressable_type` e `addressable_id`), indexando a tupla composta `['addressable_type', 'addressable_id']`.

### Racional
- **Reusabilidade Estrita (Requisito 2)**: A mesma estrutura de endereço servirá para o Munícipe (`App\Models\User`) e futuramente para os Reportes de Zeladoria (`App\Models\Report`).
- **Esquema de Banco Normalizado**:
  - `id`: BIGSERIAL / UUID (chave primária)
  - `addressable_type`: VARCHAR(255) (ex.: `App\Models\User` ou `user`)
  - `addressable_id`: BIGINT (chave estrangeira polimórfica)
  - `cep`: VARCHAR(8) (apenas números normalizados)
  - `logradouro`: VARCHAR(255)
  - `numero`: VARCHAR(20)
  - `complemento`: VARCHAR(100), NULLABLE
  - `bairro`: VARCHAR(100) (campo crítico para agregação e quórum de 60%)
  - `cidade`: VARCHAR(100)
  - `uf`: VARCHAR(2)
  - `timestamps` (`created_at`, `updated_at`)
- **Relacionamento Eloquent**:
  - `Address`: `public function addressable(): MorphTo { return $this->morphTo(); }`
  - `User`: `public function address(): MorphOne { return $this->morphOne(Address::class, 'addressable'); }`

### Alternativas Consideradas
- *Colunas de endereço diretamente na tabela `users`*: Rejeitada categoricamente para cumprir a diretriz mandatória de polimorfismo e viabilizar a reutilização em reportes de zeladoria.

---

## 3. Persistência de Sessão ("Lembrar de Mim")

### Decisão
Utilizar o mecanismo nativo de *Remember Me* do Laravel com sessions baseadas em cookies criptografados seguros (`remember_web_*`), ativado pelo parâmetro booleano `remember` em `Auth::attempt($credentials, $remember)`.

### Racional
- **Segurança Padrão da Indústria**: O Laravel gera um token aleatório criptograficamente seguro de 60 caracteres armazenado na coluna `remember_token` da tabela `users` e envia um cookie assinado `HttpOnly`, `SameSite=Lax` com tempo de vida de longa duração (5 anos por padrão do framework).
- **Invalidação**: O token é automaticamente rotacionado ou invalidado quando o munícipe aciona `Auth::logout()` ou quando altera sua senha através da renovação de credenciais.
- **Sincronização no Frontend**: O estado de autenticação reativo no Vue 3 (`sessionStore.ts`) é hidratado no carregamento da aplicação através do endpoint `GET /api/me` (que retorna o usuário autenticado via sessão de cookie ativa).

### Alternativas Consideradas
- *Tokens JWT em localStorage*: Rejeitada devido a vulnerabilidades a ataques XSS e complexidade desnecessária para monólito web, além de contrariar a arquitetura nativa de sessão do Laravel orientada a cookies HttpOnly.

---

## 4. Política de Complexidade de Senhas e Criptografia

### Decisão
Aplicar a regra nativa do Laravel `Password::min(8)->letters()->mixedCase()->numbers()->symbols()` nos Form Requests de cadastro e atualização de senha, utilizando derivação Bcrypt com fator de custo configurado ou Argon2id (conforme Princípio V e RNF01 da Constituição).

### Racional
- Garante total aderência à Clarificação 4 (alta complexidade exigida pelo usuário).
- Fornece feedback imediato de validação no frontend (indicadores visuais de requisitos atendidos: 8+ caracteres, maiúscula, minúscula, número, caractere especial).
- Senhas são submetidas exclusivamente via HTTPS e protegidas contra força bruta através do middleware de throttle (`throttle:6,1`).

---

## 5. Exclusão Definitiva de Conta

### Decisão
Disponibilizar endpoint `DELETE /api/account` protegido por middleware de autenticação, recebendo `current_password` para confirmação obrigatória. A exclusão remove fisicamente o registro de `Address` polimórfico associado e a conta `User`, finaliza a sessão web (`Auth::logout()`, invalidação de sessão e token de persistência) e retorna confirmação de sucesso.

### Racional
- Atende diretamente à Clarificação 3 e à LGPD (direito ao esquecimento).
- Garante que não restem registros órfãos de endereço na tabela `addresses` através de exclusão atômica (`DB::transaction`).

---

## 6. Componentes de Interface com shadcn-vue

### Decisão
Evoluir o componente existente `AuthDialog.vue` para incluir os campos completos de endereço com máscara de CEP, botão de busca rápida, indicador de carregamento e checkbox de "Lembrar de mim". Criar o componente `ProfileDialog.vue` (ou seção de perfil) com abas para:
1. Dados Cadastrais & Endereço (com revalidação de CEP e confirmação de senha para troca de e-mail).
2. Alteração de Senha (com validação de senha atual e medidor de complexidade).
3. Zona de Perigo (Exclusão Definitiva de Conta com confirmação de senha em dialog de confirmação).

### Racional
- Preserva a identidade visual do projeto construída na Spec 002.
- Utiliza os componentes de acessibilidade do Radix Vue (`Dialog`, `Input`, `Button`, `Badge`, `Checkbox`, `Toast`) mantendo a conformidade com o Princípio IV da Constituição.
