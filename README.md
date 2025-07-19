# Sistema de Cadastro de Fornecedores (Teste Técnico)

Este projeto é um sistema web básico para gerenciamento de fornecedores, desenvolvido em Laravel. Ele permite cadastrar, listar, visualizar, editar e excluir informações de fornecedores, com campos específicos para Pessoa Física (CPF) e Pessoa Jurídica (CNPJ).

**Funcionalidades Principais:**
* Cadastro de Fornecedores (CPF e CNPJ)
* Busca de dados de CNPJ via BrasilAPI.
* Busca de dados de CEP via BrasilAPI.
* Listagem e detalhes dos fornecedores.
* Edição e exclusão de fornecedores.
* Validação de CPF e CNPJ.
* Máscaras de entrada para CPF, CNPJ e Telefone.
* Paginação e ordenação na listagem.
* Filtro de busca na listagem.

Para executar este projeto, você precisará ter instalado em sua máquina:
* **PHP:** Versão 8.2
* **Composer:**
* **Node.js e NPM/Yarn:** 
* **Banco de Dados:** MySQL

Para configurar e executar o projeto localmente, siga os passos abaixo:

1.  **Clone o Repositório:**
    ```bash
    git clone https://github.com/PedroBiudes/teste-dev-php
    cd nome-do-seu-repositorio
    ```

2.  **Instale as Dependências do Composer:**
    composer install

3.  **Copie o Arquivo de Variáveis de Ambiente:**
    cp .env.example .env

4.  **Gere a Chave da Aplicação:**
    php artisan key:generate

5.  **Configure o Banco de Dados:**
    Abra o arquivo `.env` e configure as credenciais do seu banco de dados.

    **Exemplo para MYSQL (recomendado para teste rápido):**
    Mude as linhas de `DB_CONNECTION` para:
    ```dotenv
    DB_CONNECTION=mysql
    DB_DATABASE=[caminho_completo_para_seu_projeto]/database/database.sqlite
    # Remova ou comente as outras linhas de DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD
    ```
    Crie o arquivo `database.sqlite` dentro da pasta `database`:
    ```bash
    touch database/database.sqlite
    ```

    **Exemplo para MySQL:**
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=seu_banco_de_dados
    DB_USERNAME=seu_usuario
    DB_PASSWORD=sua_senha
    (Crie o banco de dados `seu_banco_de_dados` manualmente no MySQL)

6.  **Execute as Migrações do Banco de Dados:**
    Este comando criará as tabelas necessárias no seu banco de dados, incluindo a tabela `fornecedores` com todos os campos.
    ```bash
    php artisan migrate
    ```

7.  **(Opcional) Popule o Banco de Dados com Dados de Teste:**
   
    php artisan db:seed

8.  **Inicie o Servidor de Desenvolvimento Laravel:**
    ```bash
    php artisan serve
    ```

9.  **Acesse a Aplicação:**
    Abra seu navegador e acesse `http://127.0.0.1:8000` (ou a porta indicada pelo comando `php artisan serve`).

    **Estrutura Principal de Diretórios:**

* `app/Http/Controllers`: Lógica de negócio e manipulação de requisições.
* `app/Models`: Modelos Eloquent para interação com o banco de dados.
* `app/Rules`: Regras de validação personalizadas (ex: CPF/CNPJ).
* `database/migrations`: Definição da estrutura do banco de dados.
* `database/seeders`: Dados de teste para o banco de dados.
* `resources/views`: Arquivos Blade para a interface do usuário.
* `public`: Arquivos públicos (CSS, JS, imagens).
* `routes`: Definição das rotas da aplicação (web.php, api.php).  

**Observações:**
* A busca de CNPJ e CEP utiliza a [BrasilAPI](https://brasilapi.com.br/), uma API pública e gratuita.
* As máscaras de CPF/CNPJ/Telefone são aplicadas via JavaScript no frontend.
* A validação de CPF e CNPJ ocorre tanto no frontend (JavaScript) quanto no backend (Laravel).
