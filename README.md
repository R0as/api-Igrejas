# API Igrejas

## 📖 Sobre o Projeto

**API Igrejas** é uma API RESTful desenvolvida em Symfony para gerenciar igrejas e seus respectivos membros.

A API permite o cadastro de igrejas, o gerenciamento completo do ciclo de vida de membros, e implementa regras de negócio complexas como a transferência de membros entre igrejas, validações de documentos e limites de capacidade.

## ✨ Funcionalidades Principais

### Igrejas
* **Cadastro de Igrejas:** Criação de novas igrejas com validação de dados.
* **Código Interno Único:** Garante que cada igreja tenha um identificador único de negócio.
* **Listagem Paginada:** Endpoint para listar igrejas com suporte a paginação.
* **Gerenciamento (CRUD):** Endpoints para visualizar e criar igrejas.

### Membros
* **Gerenciamento Completo (CRUD):** Cadastro, listagem, visualização de detalhes, edição e exclusão de membros.
* **Validação de Limite:** O sistema impede que novos membros sejam adicionados a uma igreja que já atingiu seu limite de capacidade.
* **Validações de Dados:**
    * Documento (CPF/CNPJ) com formato validado e único.
    * E-mail único por igreja, evitando duplicidade.
    * Data de nascimento não pode ser uma data futura.
* **Listagem Paginada por Igreja:** Endpoint para listar todos os membros de uma igreja específica com suporte a paginação.
* **Soft Delete:** Membros não são permanentemente removidos do banco de dados, permitindo auditoria e recuperação.

### Transferências
* **Transferência de Membros:** Processo para mover um membro de uma igreja para outra.
* **Regras de Negócio:**
    * Validação para impedir a transferência caso o e-mail do membro já exista na igreja de destino.
    * Implementação de um tempo mínimo, não permitindo que um membro seja transferido mais de uma vez em um período de 10 dias.

## 🛠️ Tecnologias Utilizadas

* **PHP 8.2**
* **Symfony 7**
* **Doctrine ORM**
* **MySQL**
* **Composer**

## 🚀 Instalação e Execução

Siga os passos abaixo para configurar e executar o projeto em seu ambiente local.

### Pré-requisitos
* PHP 8.2 ou superior
* Composer
* Symfony CLI
* MySQL

### Passo a Passo

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/R0as/api-Igrejas.git
    cd api-Igrejas
    ```

2.  **Configure o ambiente:**
    * Copie o arquivo de exemplo `.env.example` para `.env`:
        ```bash
        cp .env.example .env
        ```
    * Abra o arquivo `.env` e configure a variável `DATABASE_URL` e `JWT_PASSPHRASE` com as credenciais do seu banco de dados MySQL e uma senha da sua escolha.
        ```
        # .env.local
        DATABASE_URL="mysql://usuario:senha@127.0.0.1:3306/api_igrejas"
        ```

3.  **Instale as dependências:**
    ```bash
    composer install
    ```

4.  **Crie e configure o banco de dados:**
    * Crie o banco de dados:
        ```bash
        php bin/console doctrine:database:create
        ```
    * Crie a migration do projeto para posteriormente executa-lá e criar as tabelas:
        ```bash
        php bin/console make:migration
        ```
    * Execute as migrações para criar todas as tabelas:
        ```bash
        php bin/console doctrine:migrations:migrate
        ```

5.  **Inicie o servidor local:**
    ```bash
    symfony server:start
    ```

Pronto! A API estará disponível em `http://127.0.0.1:8000`.

## 📡 Endpoints da API

A seguir está a documentação de todos os endpoints disponíveis na API.

### Igrejas
| Método | Rota                          | Descrição                            |
| :----- | :---------------------------- | :----------------------------------- |
| `GET`  | `/api/churches`               | Lista todas as igrejas (paginado)    |
| `POST` | `/api/churches`               | Cria uma nova igreja                 |

### Membros
| Método | Rota                                   | Descrição                                  |
| :----- | :------------------------------------- | :----------------------------------------- |
| `GET`  | `/api/churches/{internalCode}/members` | Lista os membros de uma igreja (paginado)  |
| `POST` | `/api/churches/{internalCode}/members` | Cria um novo membro em uma igreja          |
| `GET`  | `/api/members/{id}`                    | Exibe os detalhes de um membro             |
| `PUT`  | `/api/members/{id}`                    | Atualiza um membro existente               |
| `DELETE`| `/api/members/{id}`                   | Remove um membro (Soft Delete)             |

### Transferências
| Método | Rota                          | Descrição                                |
| :----- | :---------------------------- | :--------------------------------------- |
| `POST` | `/api/members/{id}/transfer`  | Transfere um membro para uma nova igreja |


Segue arquivo para download e teste via postman: [Download](https://drive.google.com/file/d/1WY2dV-AWbPD16OjdWFkXfOcxhqMtoSJG/view?usp=sharing)




