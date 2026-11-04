# Quickstart & Validation Guide: Fluxo de Autenticação e Gestão Cadastral (user-auth-profile)

Este guia apresenta o roteiro prático e reprodutível para validar end-to-end o fluxo de cadastro, autenticação com persistência, consulta OpenCEP, edição cadastral e exclusão de conta.

Para detalhes estruturais, consulte o [Data Model](data-model.md) e o [Contrato de API](contracts/auth-profile-api.yaml).

---

## 1. Pré-Requisitos e Preparação

Certifique-se de que o ambiente possui o PHP (>= 8.2), Composer e Node.js ativos.

```bash
# 1. Instalar dependências se necessário
composer install
npm install

# 2. Garantir configuração do ambiente e chave de aplicação
cp -n .env.example .env
php artisan key:generate

# 3. Executar as migrações (criando as tabelas users, addresses e sessions)
php artisan migrate

# 4. Iniciar servidores de desenvolvimento
# Terminal 1:
php artisan serve
# Terminal 2:
npm run dev
```

---

## 2. Cenários de Validação Ponta a Ponta

### Cenário 1: Consulta e Validação de Endereço via OpenCEP (Híbrido)

**Objetivo**: Validar preenchimento automático de endereço e sinalização da região piloto.

1. Acesse `http://localhost:8000` e clique em **"Cadastrar"**.
2. No campo CEP, digite `37550-001` (Pouso Alegre / MG).
   - **Resultado Esperado**: O sistema dispara a consulta à API OpenCEP, exibe indicador de carregamento e autocompleta:
     - Logradouro: *Rua Bom Jesus*
     - Bairro: *Centro*
     - Cidade: *Pouso Alegre*
     - UF: *MG*
     - Sem aviso de piloto (já está na cidade do piloto).
3. Teste com um CEP externo, por exemplo `01001-000` (São Paulo / SP):
   - **Resultado Esperado**: Campos preenchidos automaticamente e exibição de alerta/badge amigável informando que a localidade está fora do piloto inicial de Pouso Alegre, permitindo continuar.
4. Teste o fallback manual digitando um CEP fictício/inexistente (`99999-999`):
   - **Resultado Esperado**: Alerta visual amigável de CEP não encontrado, liberando os campos de logradouro, bairro, cidade e UF para preenchimento manual pelo munícipe.

---

### Cenário 2: Cadastro de Novo Munícipe e Persistência Polimórfica

**Objetivo**: Criar a conta do munícipe e vincular a entidade `Address` via relação polimórfica.

1. No formulário de cadastro, preencha:
   - Nome: `Ana Paula Ferreira`
   - E-mail: `ana.ferreira@exemplo.com`
   - Senha: `SenhaForte@2026!` (confirmando o mesmo valor)
   - CEP: `37550-000`, Número: `45`, Complemento: `Bloco B`
2. Submeta o formulário.
   - **Resultado Esperado**:
     - Status HTTP 201 retornado.
     - Registro criado na tabela `users`.
     - Registro criado na tabela `addresses` com `addressable_type = App\Models\User` e `addressable_id = <id_da_ana>`.
     - Usuário autenticado imediatamente e redirecionado à visão principal com nome no cabeçalho.

---

### Cenário 3: Login com "Lembrar de Mim" (*Remember Me*) e Logout

**Objetivo**: Garantir a persistência prolongada da sessão e invalidação segura no logout.

1. Efetue logout clicando em "Sair".
2. Abra o diálogo de Login e informe:
   - E-mail: `ana.ferreira@exemplo.com`
   - Senha: `SenhaForte@2026!`
   - Marque a caixa de seleção: **"Lembrar de mim"**.
3. Clique em **"Entrar"**.
   - **Resultado Esperado**: Autenticação com sucesso e emissão do cookie `remember_web_*`.
4. Feche a aba do navegador e abra uma nova aba em `http://localhost:8000`.
   - **Resultado Esperado**: A aplicação reconhece a sessão ativa sem solicitar novo login.
5. Clique em **"Sair" (Logout)**.
   - **Resultado Esperado**: Sessão finalizada, cookie e `remember_token` revogados. Ao tentar atualizar ou navegar, a aplicação encontra-se no estado não-autenticado.

---

### Cenário 4: Edição de Perfil e Requisitos de Segurança

**Objetivo**: Validar atualização de endereço e proteção de alteração de e-mail e senha.

1. Faça login novamente com a conta de teste.
2. Acesse a área de perfil (clique no avatar/nome do usuário).
3. **Teste de Segurança na Troca de E-mail**:
   - Altere o e-mail para `ana.nova@exemplo.com` sem informar a senha atual.
   - **Resultado Esperado**: Validação bloqueia o envio com mensagem: *"A senha atual é obrigatória para alterar o e-mail"*.
   - Digite a senha atual correta e confirme: alteração realizada com sucesso.
4. **Teste de Troca de Endereço**:
   - Digite novo CEP e número, salve as alterações.
   - **Resultado Esperado**: Endereço polimórfico existente é atualizado no banco mantendo o mesmo `id`.

---

### Cenário 5: Exclusão Definitiva de Conta

**Objetivo**: Validar remoção atômica da conta, endereço e encerramento de sessão.

1. Na tela de perfil, acesse a aba ou área **"Zona de Perigo / Excluir Conta"**.
2. Clique em **"Excluir Minha Conta"**.
3. Confirme digitando a senha atual.
   - **Resultado Esperado**:
     - Transação no banco remove o registro do munícipe na tabela `users` e em cascata o respectivo registro em `addresses`.
     - Sessão e tokens são invalidados imediatamente.
     - Usuário é redirecionado à página inicial com toast/notificação de confirmação.
     - Tentativas de login subsequentes com o e-mail excluído são rejeitadas.

---

## 3. Execução da Suíte de Testes Automatizados

Para validação automatizada de regressão (conforme o Princípio III da Constituição):

```bash
# Executar a suíte de testes de Feature e Unitários do Laravel
php artisan test --filter=Auth
```
