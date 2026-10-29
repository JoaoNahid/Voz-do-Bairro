# Feature Specification: Landing Page & Visual Identity Presentation

**Feature Branch**: `002-landing-visual-identity`

**Created**: 2026-10-28

**Status**: Ready for Planning

**Input**: User description: "Primeira etapa é criar a indentidade visual. Será um sistema de reportes urbanos de zeladoria, mas precisamos primeiramente de uma apresentação. A idéia será um site, com um banner chamativo e talvez até meio sensacionalista para chamar a atenção do usuário e morador. Ele verá algo como um banner com um texto chamativo, como por exemplo 'A voz do Bairro [...] faça sua denúncia de tal problema'. Algo assim logo de cara. Não será um site de muitas seções, penso em 2 a 4 no máximo - banner, slide de denuncias e sem mais idéias. Scrollando abaixo do banner, penso em uma seção com um slide com algumas denuncias em alta, onde ela pode marcar como relevante ou irrelevante e ao clicar, se estiver logado aparece um popup de confirmação da ação mas se não estiver logada aparece um modal de login/criação de conta. Inicialmente sem funcionalidade, como disse, apenas identidade visual, mas ja deixe preparado e documentado. Após essa seção de slide das denuncias pode ser uma seção de criar a denuncia, com um formulário reduzido com titulo e descrição. Neste momento não será criado nada, é feita a verificação do login. Se estiver logado abre um modal com o fomrulário completo (não se preocupe com os campos no momento) mas se não estiver logado abre o modal de login/criação de conta. Vamos focar primeiramente nessa apresentação inicial. procure referencias de sistemas semelhantes na web. O design tem que ser responsivo e intuitivo com imagens da cidade de pouso alegre, mg (onde é o alvo do projeto). Neta etapa não terá nada funcional, então sem migrations, validações ou qualquer coisa do tipo. Configure os frameworks laravel + vue e monte o front."

## Clarifications

### Session 2026-10-28

- Q: Qual paleta de cores e tom visual deve nortear a identidade visual e o Hero Banner da apresentação? → A: Tom Cívico Institucional: Interface limpa com azul marinho profundo (`#0f172a` / `#1e3a8a`), branco/gelo e acentos em amarelo/dourado (`#eab308` / `#f59e0b`), transmitindo confiança comunitária e seriedade pública.
- Q: Como a interface deve se comportar ao submeter o modal de Login ou Cadastro na apresentação visual? → A: Alternar automaticamente para o estado "Morador Autenticado" e exibir Toast de boas-vindas, permitindo que o usuário teste imediatamente os fluxos de usuário logado (confirmação de votos e formulário completo).
- Q: Quais categorias temáticas devem compor os relatos ilustrativos no carrossel de Pouso Alegre? → A: Diversificada nos 4 eixos principais de zeladoria urbana: Vias Públicas (buracos/asfalto), Saneamento/Água, Iluminação Pública e Limpeza/Praças Públicas.

## User Scenarios & Testing *(mandatory)*

### User Story 1 - Impacto Visual Imediato e Conscientização Cívica no Hero Banner (Priority: P1)

Como munícipe ou morador de Pouso Alegre, desejo acessar a página inicial do Voz do Bairro e ser imediatamente impactado por um banner visual vibrante, com tom cívico institucional e fotos de Pouso Alegre, evidenciando a urgência dos problemas de zeladoria do bairro para que eu me sinta mobilizado a agir e participar.

**Why this priority**: É a primeira impressão do cidadão e define o engajamento na plataforma; um cabeçalho assertivo e fotos representativas locais convertem visitantes casuais em participantes comunitários.

**Independent Test**: Pode ser testado visualmente carregando a página inicial em diferentes resoluções (mobile e desktop) e verificando se o título assertivo, chamada para ação (CTA) e elementos visuais de Pouso Alegre são exibidos com clareza em menos de 2 segundos.

**Acceptance Scenarios**:

1. **Given** um morador acessando o portal pela primeira vez, **When** a página é renderizada, **Then** ele visualiza um Hero Banner impactante com slogan forte de conscientização urbana, paleta cívica azul marinho e dourado, referências visuais de Pouso Alegre e botões de ação ("Ver Problemas em Alta" e "Relatar Ocorrência").
2. **Given** um usuário navegando em dispositivo móvel (smartphone), **When** visualiza o topo da página, **Then** os textos, imagens de fundo e botões de chamada adaptam-se perfeitamente sem rolagem horizontal ou sobreposição de texto.

---

### User Story 2 - Exploração de Relatos em Alta e Reação Comunitária Condicional (Priority: P2)

Como cidadão interessado no seu bairro, desejo navegar por um carrossel interativo de relatos urbanos em alta distribuídos nos 4 eixos de zeladoria (vias públicas, saneamento, iluminação e limpeza) e tentar marcar um relato como "Relevante" ou "Irrelevante", recebendo o feedback visual apropriado conforme meu estado de acesso (confirmação direta se conectado ou modal de login/cadastro se anônimo).

**Why this priority**: Demonstra interativamente a dinâmica central da plataforma (votação de relevância comunitária com o quórum de 60%) e incentiva o cadastro imediato do munícipe.

**Independent Test**: Clicar nos botões de voto ("Relevante" / "Irrelevante") de um card do carrossel nos dois estados de simulação: quando deslogado, deve abrir o modal de autenticação; quando logado, deve abrir o popup de confirmação de voto.

**Acceptance Scenarios**:

1. **Given** um visitante não autenticado explorando os cards do carrossel de denúncias, **When** ele clica no botão "Relevante" ou "Irrelevante", **Then** a interface apresenta um modal de login e criação de conta explicando que o voto comunitário requer identificação de morador.
2. **Given** um usuário submetendo o formulário do modal de login/cadastro, **When** clica em "Entrar" ou "Criar Conta", **Then** a sessão muda reativamente para "Morador Autenticado", o modal se fecha e um Toast de boas-vindas é exibido no canto da tela.
3. **Given** um usuário em estado autenticado explorando o carrossel, **When** ele clica no botão "Relevante", **Then** a interface apresenta uma janela/popup de confirmação da reação (ex.: "Deseja confirmar seu voto de relevância para este relato?").
4. **Given** um leitor interagindo com o carrossel, **When** utiliza controles de navegação (setas ou swipe), **Then** os cards de relatos dos 4 eixos temáticos transitam de forma suave e responsiva.

---

### User Story 3 - Prévia de Criação Rápida de Relato com Interceptação de Autenticação (Priority: P3)

Como munícipe com um problema urgente na sua rua, desejo visualizar uma seção de envio rápido com campos de título e descrição para iniciar um reporte, sendo conduzido ao formulário completo caso esteja logado ou ao modal de acesso caso esteja desconectado.

**Why this priority**: Antecipa o fluxo de captação de novas ocorrências sem exigir cadastro prévio imediato para iniciar a redação, reduzindo o atrito de entrada do morador.

**Independent Test**: Preencher os campos resumidos de título e descrição e acionar o botão de envio nos dois estados de login, verificando se o modal de formulário detalhado é aberto para usuários logados ou o modal de login para anônimos.

**Acceptance Scenarios**:

1. **Given** um munícipe anônimo preenchendo o título e a descrição resumida na seção rápida, **When** clica em "Continuar Denúncia", **Then** o modal de login/cadastro é aberto preservando visualmente a intenção de participação.
2. **Given** um munícipe logado utilizando o formulário reduzido, **When** clica em "Continuar Denúncia", **Then** um modal com a visualização do formulário completo de detalhes do relato é apresentado na tela.

---

### Edge Cases

- **Telas ultracompactas (smartphones pequenos < 360px)**: Os cards do carrossel e os botões de ação devem empilhar de forma fluida sem truncar o texto nem sobrepor modais.
- **Fechamento de modais**: Todos os modais (login/cadastro, confirmação de voto e formulário completo) devem poder ser fechados via tecla `Esc`, botão de fechar (`X`) ou clique no backdrop escurecido.
- **Alternância de demonstração de estado**: A interface deve disponibilizar um controle sutil e acessível no cabeçalho permitindo alternar entre o modo "Visitante (Deslogado)" e "Morador Autenticado" para permitir a demonstração completa dos fluxos visuais acordados.

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: O sistema DEVE exibir um cabeçalho de navegação (Navbar) responsivo contendo o logotipo do "Voz do Bairro", links de navegação para as seções da página e um indicador/toggle de estado de acesso (Visitante vs. Morador Logado).
- **FR-002**: O sistema DEVE apresentar uma seção Hero (Banner Principal) adotando a paleta Cívica Institucional com azul marinho profundo (`#0f172a` / `#1e3a8a`), branco/gelo e acentos em amarelo/dourado (`#eab308` / `#f59e0b`), tipografia de forte impacto cívico, imagens alusivas a Pouso Alegre/MG e botões de ação primária (CTA).
- **FR-003**: O sistema DEVE conter uma seção de Carrossel/Slide exibindo cards de relatos urbanos em alta distribuídos nos 4 eixos principais de zeladoria (Vias Públicas, Saneamento/Água, Iluminação Pública e Limpeza/Praças), identificando bairros reais de Pouso Alegre (ex.: Recanto dos Fernandes, Centro, São Geraldo, Fátima), imagens ilustrativas e indicador de quórum de votos.
- **FR-004**: Cada card de relato no carrossel DEVE conter controles interativos de votação ("Relevante" e "Irrelevante").
- **FR-005**: Ao clicar em qualquer botão de voto do carrossel, se o estado for "Deslogado", o sistema DEVE abrir um modal de autenticação (Login / Cadastro de Conta); se o estado for "Logado", o sistema DEVE abrir um popup/modal de confirmação da votação.
- **FR-006**: O sistema DEVE apresentar uma seção de Criação Rápida de Relato contendo campos reduzidos de Título e Descrição sumária da ocorrência urbana com botão de submissão preliminar.
- **FR-007**: Ao acionar a submissão preliminar da denúncia, se o estado for "Logado", o sistema DEVE abrir um modal com o formulário detalhado de registro; se for "Deslogado", o sistema DEVE abrir o modal de login/cadastro.
- **FR-008**: O sistema DEVE apresentar um Modal de Autenticação com abas para alternar facilmente entre "Entrar com minha conta" e "Criar conta de morador". Ao submeter o formulário, o sistema DEVE alternar automaticamente para o estado "Morador Autenticado", fechar o diálogo e exibir um Toast de notificação de boas-vindas.
- **FR-009**: O sistema DEVE incluir um rodapé (Footer) institucional limpo e acessível, contendo identificação acadêmica da universidade, setor inicial (Recanto dos Fernandes, Pouso Alegre/MG) e selos de referência às ODSs da ONU.
- **FR-010**: O design da página DEVE ser construído com os componentes acessíveis da biblioteca shadcn-vue (Button, Card, Carousel, Dialog, Form, Input, Badge, Toast), com suporte total a responsividade móvel de 320px a 1920px.

### Key Entities

- **RelatoVisual**: Representação de uma denúncia em alta contendo título, localidade (bairro de Pouso Alegre), categoria (vias públicas, iluminação, saneamento, limpeza de praças), imagem ilustrativa e contagem de votos.
- **EstadoAutenticacao**: Estado reativo da sessão na interface (Visitante não identificado vs. Morador autenticado).
- **ModalInteracao**: Elementos de diálogo sobrepostos para login/cadastro, confirmação de voto e formulário ampliado de denúncia construídos com Dialog do shadcn-vue.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: O layout inicial da Landing Page é totalmente carregado e interativo em menos de 2 segundos em conexões padrão.
- **SC-002**: 100% dos fluxos de clique interativo (voto no carrossel e envio rápido) disparam o modal correto dependendo do estado de autenticação selecionado.
- **SC-003**: A submissão do modal de autenticação altera o estado de simulação e exibe o Toast em menos de 300ms.
- **SC-004**: A interface se ajusta perfeitamente sem barra de rolagem horizontal em larguras de tela de 320px a 1920px.
- **SC-005**: Um morador ou avaliador que visita a página identifica em até 5 segundos o tema central do sistema (participação cidadã e denúncia de problemas urbanos em Pouso Alegre).

## Assumptions

- Nesta primeira etapa de identidade visual, os dados de relatos e estados de usuário são mocks e estados reativos de frontend no Vue 3 / shadcn-vue, sem necessidade de tabelas, migrações de banco ou endpoints de persistência no Laravel.
- A stack técnica integra a fundação do Laravel com Vue 3 no frontend, preparando a base para as etapas subsequentes de backend funcional.
- Imagens representativas de Pouso Alegre/MG e cenários reais de infraestrutura urbana são integradas como ativos estáticos otimizados.
