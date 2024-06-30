<div align="center">
Este projeto é um conversor de moedas.
</div>

## Instalação

<details>
<summary>1. Clone este repositório</summary>

```sh    
git clone https://github.com/rafaelpouzada/currency-exchange
```
</details>

<details>
<summary>2. Acesse a pasta criada</summary>
  
```sh
cd currency-exchange
```

</details>

<details>
<summary>3. Inicie o docker</summary>

```sh
# Inicializa todos os containers em background
docker-compose up -d
```
</details>

<details>
<summary>4. Configure o projeto</summary>

```sh  
#Copie o arquivo env    
cp app/.env.example app/.env

# Cria o banco de dados e garante os devidos acessos
- Acesse o container do mysql
docker exec -ti currency-exchange-mysql sh

- Acesse o banco de dados 
mysql -uroot -pexchange

- Verifique se já existe o banco de dados um usuário User:
SELECT User FROM mysql.user WHERE User = 'user';

- Caso exista o usuário, altere a senha:
ALTER USER 'user'@'%' IDENTIFIED BY '123';

- Caso não exista, crie o usuário:
CREATE USER 'user'@'%' IDENTIFIED BY '123';

- Crie o banco de dados caso não exista e atribua permissões ao usuário:
CREATE DATABASE IF NOT EXISTS `currency-exchange`; GRANT ALL PRIVILEGES ON `currency-exchange`.* TO 'user'@'%';
FLUSH PRIVILEGES;

- Saia do banco de dados
exit

- Saia do container
exit

# Acessa o container php para execução de comandos.
docker exec -it currency-exchange-php-fpm sh

# Acesse a pasta do projeto
cd currency-exchange

# Instala o composer 
composer install 

# Gera a chave da aplicação
php artisan key:generate

# Realiza as migrações no banco de dados
php artisan migrate

# Conceda permissão de escrita para a pasta storage
chmod -R 777 storage

# Saia do container
exit
```
</details>

<details>
<summary>5. Adicione os domínios ao host</summary>
<br/>
Para isso execute o comando:  

- Adicione a linha abaixo no arquivo `/etc/hosts`:
```sh  
# Currency Exchange
127.0.0.1 app.currency-exchange.dev
```
</details>


## Geração do certificado SSL

Para geração do certificado SSL é necessário ter o mkcert instalado. Para instalar siga as instruções do [site oficial](https://github.com/FiloSottile/mkcert#installation).

Rode o comando abaixo na pasta `nginx/ssl`.

```sh
mkcert -key-file currency-exchange.key -cert-file currency-exchange.crt "*.currency-exchange.dev" "currency-exchange.dev"
```Z

## Rodando a aplicação

Uma vez feito todos os passos da instalação, seja de forma automatizada ou manual, a aplicação já estará rodando nos domínios configurados. Caso precise reiniciar em algum momento a aplicação use os comandos abaixo.

```sh
# Para e remove todos os containers e networks.
docker compose down

# Inicializa todos os containers e networks
docker compose up -d
```

## Configuração adicional

Caso desejar, você pode adicionar alguns atalhos que facilitam a utilização do projeto. Os comandos abaixo adicionam esses atalhos no arquivo `.bashrc`. Para adicionar em outro shell que você usa mude o `~/.bashrc` no final do comando informando o nome do arquivo de configuração do shell que usa.

```sh
echo \
'
export CURRENCY_EXCHANGE_DOCKER_PATH="'$(pwd)'/docker-compose.yml"

alias exchange:cd="cd ${HUB_MARKETPLACE_PATH}"
alias exchange:up="docker compose -f ${CURRENCY_EXCHANGE_DOCKER_PATH} up -d"
alias exchange:down="docker compose -f ${CURRENCY_EXCHANGE_DOCKER_PATH} down"
alias exchange:start="docker compose -f ${CURRENCY_EXCHANGE_DOCKER_PATH} start"
alias exchange:stop="docker compose -f ${CURRENCY_EXCHANGE_DOCKER_PATH} stop"
alias exchange:restart="docker compose -f ${CURRENCY_EXCHANGE_DOCKER_PATH} restart"
alias exchange:bash="docker exec -ti currency-exchange-php-fpm sh"
alias exchange:logs="docker logs -f currency-exchange-php-fpm"' >> ~/.bashrc
```

## Acesso

Para verificar se tudo está funcionando corretamente, acesso a url abaixo:

```
https://app.currency-exchange.dev/api/healthcheck