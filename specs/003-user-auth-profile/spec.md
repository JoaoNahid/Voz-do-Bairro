# Feature Specification: Fluxo de Autenticação e Gestão Cadastral do Cidadão (user-auth-profile)

**Feature Branch**: `003-user-auth-profile`  
**Created**: 2026-11-03  
**Status**: Draft  
**Input**: User description: "Crie o fluxo de criação de cadastro, login, logout e edição de dados cadastrais. Para isso algumas observaçõesprecisam ser levadas em conta: 1. utilize a API openCEP para validar os campos de endereço; 2. A tabela de endereço deve ser uma tabela com relacionamento polimorfico, pois futuramente servirá para guardaro endereço dos reportes também; 3. O usuário deve marcar a opção de lembrar login para não ter que logar à plataforma toda vez que acessar."

## Clarifications

### Session 2026-11-03

- Q: O sistema deve restringir o cadastro a munícipes com endereço em Pouso Alegre (MG) ou permitir qualquer localidade do território nacional? → A: Permitir CEP de qualquer localidade nacional (agrupando por bairro e cidade), mas exibir aviso/banner informativo caso o endereço informado esteja fora da área piloto de Pouso Alegre / MG.
- Q: A alteração do e-mail na edição de dados cadastrais deve exigir a confirmação obrigatória da senha atual do munícipe? → A: Sim (Opção A), a alteração do e-mail cadastral requer a confirmação obrigatória da senha atual do usuário como salvaguarda de segurança contra sequestro de conta.
- Q: A tela de perfil deve disponibilizar a opção para o próprio munícipe solicitar a exclusão ou desativação de sua conta nesta versão inicial? → A: Sim (Opção C), disponibilizar a exclusão definitiva da conta e do endereço polimórfico associado mediante confirmação com a senha atual, invalidando a sessão imediatamente.
- Q: Qual política de requisitos mínimos de senha deve ser adotada no cadastro e na redefinição de credenciais? → A: Alta complexidade (Opção C), exigindo no mínimo 8 caracteres contendo ao menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial.
- Q: Como deve ser disparada a consulta automática de endereço via OpenCEP no formulário de cadastro e na edição de perfil? → A: Híbrido (Opção C), disparando busca automática ao completar os 8 dígitos válidos (com indicador de carregamento) e disponibilizando botão dedicado de busca/recarga ao lado do campo CEP.

---

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Cadastro de Novo Munícipe com Endereço Validado via CEP (Priority: P1)

Como um munícipe do bairro, desejo me cadastrar na plataforma informando meus dados pessoais e meu endereço residencial validado via CEP, para que eu possa participar ativamente das decisões e demandas comunitárias da minha localidade.

**Why this priority**: O cadastro é a porta de entrada indispensável para a plataforma. A vinculação correta ao bairro e endereço residencial é mandatória para o cálculo de relevância e quórum comunitário do projeto.

**Independent Test**: Pode ser testado de forma isolada ao preencher o formulário de cadastro com um CEP válido da API OpenCEP, preenchendo os dados pessoais e confirmando a criação da conta e do endereço vinculado.

**Acceptance Scenarios**:

1. **Given** um visitante não autenticado na tela de cadastro, **When** ele digita um CEP válido de 8 dígitos ou aciona o botão de buscar CEP, **Then** o sistema consulta o serviço OpenCEP (com indicador visual de carregamento) e preenche automaticamente os campos de logradouro, bairro, cidade e estado (UF), permitindo ao usuário complementar número e complemento.
2. **Given** um visitante preenchendo o cadastro com todos os campos obrigatórios válidos (nome, e-mail único, senha segura e endereço completo), **When** submeter o formulário, **Then** a conta do munícipe e o registro de endereço polimórfico associado são criados, e o usuário é redirecionado para a plataforma com sessão ativa.
3. **Given** um visitante informando um e-mail já cadastrado, **When** submeter o cadastro, **Then** o sistema recusa a operação e exibe mensagem clara informando que o e-mail já está em uso.
4. **Given** um visitante informando um endereço localizado fora do município piloto de Pouso Alegre/MG, **When** o CEP for consultado e validado via OpenCEP, **Then** o sistema preenche os campos normalmente e exibe um aviso informativo comunicando a fase piloto na região de Pouso Alegre, permitindo a conclusão regular do cadastro.

---

### User Story 2 - Autenticação com Persistência de Sessão ("Lembrar de Mim") (Priority: P1)

Como um munícipe cadastrado, desejo realizar login informando meu e-mail e senha, com a opção de manter minha sessão conectada ("Lembrar de mim"), para que eu não precise digitar minhas credenciais repetidamente em acessos subsequentes no mesmo dispositivo.

**Why this priority**: Garante o acesso recorrente simplificado e ágil à plataforma, reduzindo atrito para munícipes de diferentes perfis e níveis de alfabetização digital.

**Independent Test**: Pode ser testado ao realizar login com credenciais válidas marcando a opção "Lembrar de mim", fechando a aba/navegador e reabrindo a plataforma, verificando que o usuário permanece autenticado.

**Acceptance Scenarios**:

1. **Given** um usuário cadastrado na tela de login, **When** ele insere e-mail e senha válidos marcando a opção "Lembrar de mim" e clica em entrar, **Then** o sistema autentica com sucesso, gera um identificador de sessão persistente segura e redireciona para a visão principal.
2. **Given** um usuário que realizou login com "Lembrar de mim" ativado, **When** ele fecha a janela do navegador e retorna à plataforma posteriormente dentro do período de validade, **Then** o sistema o reconhece automaticamente sem exigir novo login.
3. **Given** um usuário que realizou login SEM marcar a opção "Lembrar de mim", **When** a sessão do navegador expira ou é encerrada, **Then** o sistema exige nova autenticação no próximo acesso.
4. **Given** um usuário fornecendo credenciais incorretas, **When** tentar realizar login, **Then** o sistema exibe mensagem de erro genérica e segura ("Credenciais inválidas") sem expor se o e-mail existe ou se apenas a senha estava incorreta.

---

### User Story 3 - Encerramento Seguro de Sessão (Logout) (Priority: P2)

Como um munícipe autenticado, desejo encerrar minha sessão a qualquer momento através de uma opção clara de logout, para garantir que outras pessoas que usem o mesmo dispositivo não acessem meus dados ou realizem ações em meu nome.

**Why this priority**: Essencial para a privacidade e segurança do cidadão, especialmente em computadores compartilhados ou dispositivos públicos.

**Independent Test**: Pode ser testado ao clicar em "Sair" em qualquer página da plataforma com sessão ativa, confirmando o cancelamento imediato da sessão e do token de persistência, e a impossibilidade de acessar rotas restritas sem novo login.

**Acceptance Scenarios**:

1. **Given** um usuário autenticado (com ou sem token persistente "Lembrar de mim"), **When** ele aciona a ação de logout/sair, **Then** o sistema invalida a sessão atual e o identificador persistente, e redireciona o usuário para a página inicial/pública.
2. **Given** um usuário que acabou de realizar logout, **When** tentar navegar para telas exclusivas de usuários autenticados ou acionar o botão de voltar do navegador, **Then** o sistema impede o acesso e redireciona para a tela de login.

---

### User Story 4 - Visualização e Edição de Dados Cadastrais e Endereço (Priority: P2)

Como um munícipe autenticado, desejo visualizar meus dados atuais e poder atualizar meu nome, e-mail, senha e endereço residencial (com revalidação de CEP), para manter minhas informações de contato e localização sempre atualizadas.

**Why this priority**: Permite que munícipes mantenham suas informações corretas caso mudem de endereço ou queiram atualizar suas credenciais de acesso, impactando diretamente o bairro de referência para votações.

**Independent Test**: Pode ser testado editando os campos de perfil e endereço, submetendo novo CEP para autocompletar e salvando as alterações, verificando a persistência atualizada dos dados.

**Acceptance Scenarios**:

1. **Given** um usuário autenticado na tela de perfil/dados cadastrais, **When** ele acessa a tela, **Then** visualiza seus dados cadastrais e endereço atuais pré-carregados nos respectivos campos.
2. **Given** o usuário alterando seu endereço residencial, **When** ele digita um novo CEP válido, **Then** o sistema busca os novos dados de logradouro, bairro, cidade e UF via OpenCEP para validação e preenchimento.
3. **Given** o usuário alterando sua senha de acesso, **When** ele fornece a senha atual correta e a nova senha que atenda aos requisitos de complexidade, **Then** a senha é atualizada com criptografia segura e uma confirmação de sucesso é exibida.
4. **Given** o usuário alterando a senha, **When** ele informa a senha atual incorreta, **Then** o sistema recusa a alteração e exibe alerta de erro específico.
5. **Given** o usuário alterando seu e-mail cadastrado, **When** submeter o novo e-mail fornecendo a senha atual correta, **Then** o e-mail é atualizado após validação de unicidade; caso a senha atual esteja incorreta, a alteração é bloqueada.
6. **Given** um usuário autenticado na área de perfil optando por excluir sua conta, **When** ele aciona a ação de exclusão e digita sua senha atual correta, **Then** o sistema remove definitivamente os dados da conta e seu respectivo endereço polimórfico, revoga todas as sessões e tokens persistentes, e redireciona para a página inicial com mensagem de confirmação.

---

### Edge Cases

- **Indisponibilidade ou Lentidão da API OpenCEP**: Caso o serviço de consulta de CEP esteja offline, retorne timeout ou erro HTTP 5xx, o sistema não deve travar o formulário; deve notificar amigavelmente o usuário sobre a falha momentânea da consulta automática e permitir o preenchimento manual dos campos de endereço (logradouro, bairro, cidade e estado).
- **CEP Inexistente na Base Postal**: Se o munícipe digitar um CEP com 8 dígitos válidos sintaticamente mas que não existe na base OpenCEP, o sistema deve exibir alerta indicando "CEP não localizado" e permitir conferência ou digitação manual assistida.
- **Formatação de CEP com Máscara e Caracteres Especiais**: O sistema deve aceitar tanto a digitação pura com 8 números (`37550000`) quanto formatada com traço (`37550-000`), normalizando antes da consulta.
- **Troca de Bairro e Impacto em Votações Anteriores**: Quando um usuário atualiza seu endereço alterando seu bairro de residência, o novo bairro passa a valer para novas participações e cálculos futuros de quórum comunitário; registros históricos anteriores preservam sua integridade.
- **Tentativa de Força Bruta / Múltiplas Tentativas de Login**: Limitar o número de tentativas consecutivas inválidas de login por IP e por conta para mitigar ataques de força bruta, bloqueando temporariamente requisições excessivas.
- **Revogação de "Lembrar de Mim" ao Alterar Senha**: Ao alterar a senha ou realizar logout explícito, quaisquer tokens persistentes antigos gerados em sessões anteriores devem ser invalidados.

---

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: O sistema DEVE disponibilizar formulário de cadastro de novos munícipes solicitando: nome completo, e-mail válido e único, senha segura com confirmação (mínimo de 8 caracteres, exigindo ao menos uma letra maiúscula, uma minúscula, um número e um caractere especial), e dados completos de endereço residencial.
- **FR-002**: O sistema DEVE validar o CEP informado integrando com o serviço OpenCEP (`https://opencep.com/v1/{cep}`), autocompletando os campos de logradouro, bairro, localidade/cidade e UF tanto automaticamente ao completar os 8 dígitos (ou blur) quanto sob demanda através de um botão de busca/recarga.
- **FR-003**: O sistema DEVE conter mecanismo de fallback para permitir a digitação manual de logradouro, bairro, cidade e UF caso a consulta OpenCEP falhe, sofra timeout ou não encontre o CEP.
- **FR-004**: O sistema DEVE modelar o armazenamento de endereços através de uma entidade de endereço desacoplada com relacionamento polimórfico (`addressable`), permitindo associar o endereço ao munícipe no presente e a futuras entidades (ex.: reportes comunitários de infraestrutura).
- **FR-005**: O cadastro de endereço DEVE armazenar obrigatoriamente: CEP, logradouro, número, complemento (opcional), bairro, cidade e UF.
- **FR-006**: O sistema DEVE autenticar munícipes através de e-mail e senha previamente cadastrados.
- **FR-007**: O sistema DEVE fornecer no formulário de login a opção explícita de "Lembrar de mim" (Remember Me), permitindo que o usuário opte por manter sua sessão ativa de forma prolongada e segura através de identificador persistente.
- **FR-008**: Se o usuário marcar "Lembrar de mim", a sessão DEVE permanecer reconhecida em acessos futuros no mesmo navegador mesmo após fechamento da janela ou reinício do navegador, respeitando a validade do token.
- **FR-009**: O sistema DEVE fornecer funcionalidade de logout (encerramento de sessão) acessível em toda a interface logada, invalidando imediatamente a sessão corrente e o token persistente "Lembrar de mim".
- **FR-010**: O sistema DEVE fornecer tela dedicada para consulta e edição de perfil cadastral, permitindo alterar dados pessoais (nome, e-mail) e endereço residencial.
- **FR-011**: A edição de endereço na área de perfil DEVE utilizar a mesma validação e autocompletamento via OpenCEP utilizada no cadastro inicial.
- **FR-012**: O sistema DEVE permitir a alteração de senha mediante confirmação obrigatória da senha atual e validação da nova senha sob a mesma política de alta complexidade (mínimo de 8 caracteres, maiúscula, minúscula, número e caractere especial).
- **FR-013**: As senhas de usuários DEVEM ser protegidas utilizando algoritmos criptográficos robustos de derivação e hash unidirecional (Argon2id ou Bcrypt), em estrita conformidade com a Constituição do Projeto (Princípio V e RNF01).
- **FR-014**: O sistema DEVE validar a unicidade de e-mail tanto no cadastro inicial quanto na alteração de perfil, impedindo duplicidades.
- **FR-015**: As interfaces de cadastro, login e edição cadastral DEVEM seguir os padrões visuais e componentes do `shadcn-vue` (Radix Vue + Tailwind CSS), garantindo responsividade, acessibilidade e mensagens de validação em português (pt-BR).
- **FR-016**: O sistema DEVE permitir o cadastro com CEPs de qualquer localidade nacional, exibindo um aviso amigável na interface caso o município e UF informados difiram da área piloto inicial (Pouso Alegre / MG).
- **FR-017**: A alteração do endereço de e-mail na edição de dados cadastrais DEVE exigir a confirmação obrigatória da senha atual do munícipe para autorizar a modificação.
- **FR-018**: O sistema DEVE disponibilizar na tela de perfil uma área de exclusão definitiva de conta, exigindo a confirmação obrigatória da senha atual para excluir os dados cadastrais e o registro de endereço polimórfico associado, invalidando imediatamente a sessão e redirecionando à página pública.

---

### Key Entities *(include if feature involves data)*

- **Cidadão / Munícipe (`User`)**:
  - Representa o morador registrado na plataforma.
  - Atributos principais: Identificador único, nome completo, e-mail (único), senha (hash seguro), identificador de sessão persistente (*remember token*), data/hora de criação e atualização.
  - Relacionamentos: Possui um registro de endereço vinculado via relação polimórfica (`hasOne(Address, 'addressable')`). Futuramente, possuirá reportes e votos.

- **Endereço Polimórfico (`Address`)**:
  - Representa uma localização física estruturada e normalizada no sistema.
  - Atributos principais: Identificador único, tipo da entidade proprietária (`addressable_type`), ID da entidade proprietária (`addressable_id`), CEP (8 dígitos), logradouro, número, complemento, bairro, cidade, UF (sigla de 2 caracteres), data/hora de criação e atualização.
  - Relacionamentos: Pertence de forma polimórfica a qualquer entidade identificável (`morphTo('addressable')`), atendendo a `User` nesta etapa e pronta para atender `Report` (reportes de zeladoria) em etapas futuras.

---

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: O munícipe consegue preencher e concluir o fluxo completo de cadastro com validação de CEP em menos de 2 minutos.
- **SC-002**: A busca de dados de endereço por CEP válido via serviço OpenCEP preenche os campos automaticamente em menos de 1,5 segundo em condições normais de conexão.
- **SC-003**: 100% dos usuários que marcam a opção "Lembrar de mim" continuam autenticados ao reabrir o navegador após encerramento da aba/janela, sem necessidade de reinserir credenciais.
- **SC-004**: Ao acionar a opção de logout, 100% das sessões e credenciais temporárias do dispositivo são invalidadas imediatamente, impedindo acesso às rotas restritas via botão de avançar/voltar.
- **SC-005**: 100% das senhas armazenadas no banco utilizam hash criptográfico seguro irreversível, sem armazenamento de senhas em texto puro.
- **SC-006**: A estrutura polimórfica da entidade `Address` permite vinculação sem qualquer alteração de esquema estrutural tanto para contas de usuários quanto para novos módulos futuros do sistema.

---

## Assumptions

- **Serviço OpenCEP**: Assume-se a utilização pública da API OpenCEP (`https://opencep.com/v1/{cep}`), que não exige chave de autenticação (API key) e retorna JSON padronizado com os dados de endereçamento nacional.
- **Bairro como Chave Comunitária**: Assume-se que o campo `bairro` retornado pelo OpenCEP ou complementado pelo munícipe serve de base para identificar a comunidade de atuação do cidadão e apurar quóruns comunitários da plataforma.
- **Validade do Token "Lembrar de mim"**: Por padrão da indústria, o token de persistência segura do "Lembrar de mim" terá validade padrão de longa duração (ex.: até 5 anos ou até logout explícito/troca de senha), armazenado em cookie seguro e criptografado (`HttpOnly`, `SameSite`).
- **Verificação de E-mail**: Para manter o fluxo acessível, sem barreiras de inclusão digital e focado no MVP com baixo atrito, a confirmação obrigatória de link por e-mail fica postergada para fases futuras, autenticando o usuário diretamente no cadastro inicial.
