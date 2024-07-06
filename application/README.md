# API de Conversão de Moedas

Este projeto é uma API REST projetada para realizar a conversão de moedas utilizando taxas de conversão atualizadas de um serviço externo. A API registra cada transação de conversão com todas as informações relacionadas e fornece um endpoint para consultar as transações realizadas por um usuário.

## Funcionalidades

1. **Conversão de Moedas**: A API suporta a conversão entre pelo menos quatro moedas: BRL, USD, EUR, JPY.
2. **Taxas de Conversão**: As taxas de conversão são obtidas da [API de Taxas de Câmbio](http://api.exchangeratesapi.io/latest?base=EUR). Note que a versão gratuita desta API tem limitações de requisições e suporta conversões apenas com base na moeda EUR.
3. **Persistência de Transações**: As transações de conversão são persistidas em um banco de dados embutido com as seguintes informações:
    - ID do usuário
    - Moeda de origem
    - Valor de origem
    - Moeda de destino
    - Taxa de conversão utilizada
    - Data/Hora em UTC
4. **Resposta de Transação Bem-sucedida**: Uma transação bem-sucedida retorna as seguintes informações:
    - ID da transação
    - ID do usuário
    - Moeda de origem
    - Valor de origem
    - Moeda de destino
    - Valor de destino
    - Taxa de conversão utilizada
    - Data/Hora em UTC
5. **Tratamento de Erros**: Em caso de falha, a API retorna um código de status pertinente e descrição no corpo.
6. **Consulta de Transações**: Um endpoint é fornecido para listar todas as transações realizadas por um usuário.
7. **Testes**: A API inclui cobertura de testes satisfatória.
8. **Documentação**: Este README fornece instruções sobre como executar a aplicação e uma visão geral do projeto, incluindo seu propósito, funcionalidades, motivação para as escolhas de tecnologia e separação de camadas.
9. **Idioma**: Todo o código é escrito em inglês.
10. **Entrega**: O projeto é entregue via um repositório do GitHub.

## Visão Geral do Projeto

Este projeto é uma API de Conversão de Moedas construída com PHP, Laravel, e Vue.js. A escolha dessas tecnologias foi motivada por suas características únicas e benefícios que elas trazem para o desenvolvimento de aplicações web.

### PHP e Laravel

PHP é uma linguagem de programação amplamente utilizada para o desenvolvimento web. É conhecida por sua simplicidade e flexibilidade, tornando-a uma escolha popular para muitos desenvolvedores.

Laravel, por outro lado, é um framework PHP que simplifica muitas das tarefas comuns no desenvolvimento web, como roteamento, autenticação, sessões e caching.

Além disso, Laravel oferece uma sintaxe expressiva e elegante que torna o código mais legível e fácil de manter. Ele também vem com uma variedade de ferramentas e recursos prontos para uso, como o Eloquent ORM para interação com o banco de dados, e o sistema de migrações para controle de versão do banco de dados.

### Vue.js

Vue.js é um framework JavaScript progressivo para construir interfaces de usuário. Ele é projetado para ser fácil de adotar e integrar com outros projetos. Vue.js é conhecido por sua simplicidade e flexibilidade, permitindo que os desenvolvedores escrevam código limpo e eficiente.

Vue.js também oferece uma variedade de recursos avançados, como componentes reutilizáveis, transições de animação, e um sistema de roteamento. Além disso, Vue.js tem uma curva de aprendizado suave, tornando-o acessível para desenvolvedores de todos os níveis de experiência.

A combinação de PHP, Laravel, e Vue.js permite que este projeto ofereça uma experiência de usuário rica e dinâmica, ao mesmo tempo em que mantém a lógica do servidor robusta e segura.

### Evidências de Testes

//adicionar o gif de testes currency_exchange.gif
![Testes](
