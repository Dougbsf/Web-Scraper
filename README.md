# Web Scraper com Laravel
O intuito deste projeto é armazenar os dados obtidos da url: https://www.questmultimarcas.com.br/estoque 
e armazena-las de forma organizada dentro de um banco de dados relacional.

Para recolher os dados da página foi utilizado o REGEX e os mesmos foram armazenados no banco de dados através de queries SQL.
# Requisitos
- PHP (recomendo utilizar a versão mais recente, no desenvolvimento foi utilizada a versão: 8.0.8)
- Banco de dados MySQL
- NPM
- Composer
- Laravel 8

# Instalação

Primeiramente é necessário clonar o projeto, para isso execute o seguinte comando: 
> git clone https://gitlab.com/Dougbsf/web-scraper.git

Após isso abra o terminal e navegue até a pasta onde o projeto foi clonado e execute os comandos abaixo:

>composer update

>npm install

>npm run dev

Crie uma cópia do arquivo .env.example e renomeie para .env, dentro deste arquivo será necessário
adicionar as credenciais do seu banco de dados, os seguintes campos devem ser preenchidos:

> DB_CONNECTION | DB_HOST | DB_PORT | DB_DATABASE | DB_USERNAME | DB_PASSWORD

Após isso podemos executar as migrações, utilize o comando abaixo para criar as tabelas no banco de dados:

> php artisan migrate

Quase lá, agora é só iniciarmos o servidor para que seja possível utilizar o sistema, execute o último comando:

> php artisan serve

Após isso o sistema estará disponível em seu localhost: http://127.0.0.1:8000
## Como utilizar o sistema

Após a inicialização do sistema você será direcionado para a tela de login:

![Imagem da tela de login](docs/imgs/login.jpg?raw=true)

Aqui podemos realizar login com o usuário padrão:
> Usuário: admin@admin.com | Senha: admin

Ou você criar um novo usuário a partir da tela de registro, no canto superior direito:

![Imagem da tela de registro](docs/imgs/registro.jpg?raw=true)

Ao logar teremos acesso a mais duas páginas: Página de Captura e Lista de Carros:

![Imagem da home](docs/imgs/home.jpg?raw=true)

Na página de captura é onde realizamos a busca e armazenamento dos dados:

![Imagem da tela de captura](docs/imgs/captura.jpg?raw=true)

Temos um capo de texto para realizar uma busca específica e o botão de captura, que irá
solicitar uma requisição AJAX para o Controller de captura, onde será feita uma requisição GET na
url: https://www.questmultimarcas.com.br/estoque(?termo=valor digitado no campo de texto).

Caso a pesquisa seja bem sucedida será retornada uma lista com os veículos encontrados, junto 
com seus dados e seus status, os status podem ser: Inserido ou Atualizado, caso um veículo já 
esteja cadastrado no sistema seus valores são atualizados.

![Imagem da tela de captura com resultados](docs/imgs/captura2.jpg?raw=true)

Esses dados agora estão cadastrados no nosso banco de dados e podem ser acessados 
através da tela: Lista de Carros:

![Imagem da tela de lista de carros](docs/imgs/lista.jpg?raw=true)

Aqui temos todos os veículos cadastrados no sistema, junto de suas especificações.

Também é possível deletar os veículos da nossa base de dados:

![Imagem da tela de lista de carros com delete](docs/imgs/lista2.jpg?raw=true)

