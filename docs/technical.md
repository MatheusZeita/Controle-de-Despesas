# Documentação técnica

Este documento descreve a arquitetura, o funcionamento interno, a configuração e os cuidados de publicação do Controle de Despesas.

## 1. Arquitetura

A aplicação utiliza PHP server-side, sem framework, ORM ou API separada:

```text
Navegador → arquivos PHP em public/ → regras em src/config/ → SQLite ou MySQL
```

O diretório `public/` deve ser o document root do servidor web. `src/`, `database/` e `storage/` são diretórios internos e não devem ficar diretamente expostos.

## 2. Componentes

### `public/index.php`

É o dashboard. Lê filtros enviados por `GET`, valida categoria e ordenação, monta a consulta parametrizada, busca os registros e calcula total, quantidade, média e totais por categoria.

As condições de categoria e período são combinadas com `AND`. A ordenação é limitada a opções previamente definidas, evitando inserir valores arbitrários em `ORDER BY`.

### `public/create.php` e `public/expense-form.php`

`create.php` prepara os valores iniciais, incluindo a data atual. `expense-form.php` contém o formulário reutilizado no cadastro e na edição.

### `public/save.php`

Aceita somente `POST`, chama `validateExpense()` e executa um `INSERT` parametrizado. Em caso de erro, reexibe o formulário com os dados e mensagens de validação.

### `public/edit.php`

Recebe o ID, carrega a despesa com `SELECT`, exibe o formulário e executa um `UPDATE` parametrizado após a validação. O campo `created_at` não é alterado.

### `public/delete.php`

Aceita exclusão somente por `POST`, valida o ID como inteiro e executa `DELETE` parametrizado. A confirmação visual usa `confirm()` no navegador, mas não é a base da segurança.

### `src/config/expenses.php`

Centraliza categorias fixas e validação server-side de descrição, valor, categoria e data. O valor aceita vírgula ou ponto como separador decimal.

### `src/config/database.php`

Expõe `getDatabase()`, que mantém uma instância PDO durante a requisição. Variáveis de ambiente têm prioridade sobre `src/config/database.local.php`.

## 3. Banco de dados

A tabela `expenses` possui:

| Campo | Tipo/função |
|---|---|
| `id` | Identificador único auto incrementável |
| `description` | Descrição obrigatória, até 120 caracteres |
| `amount` | Valor monetário `DECIMAL(10,2)` |
| `category` | Categoria obrigatória |
| `expense_date` | Data do lançamento |
| `created_at` | Data automática de criação |

Os schemas estão em `database/schema.sql` e `database/schema.mysql.sql`. A aplicação executa o schema ao abrir a conexão com `CREATE TABLE IF NOT EXISTS`. Alterações futuras na estrutura exigem migração manual.

### SQLite local

Com `DB_DRIVER=sqlite`, o arquivo utilizado é `storage/database.sqlite`. O diretório precisa existir e permitir escrita pelo processo do PHP. O arquivo é ignorado pelo Git para não versionar dados pessoais.

### MySQL em produção

Com `DB_DRIVER=mysql`, a conexão usa PDO com `charset=utf8mb4` e os valores de host, porta, banco, usuário e senha.

Exemplo sem credenciais reais:

```php
return [
    'DB_DRIVER' => 'mysql',
    'DB_HOST' => 'servidor-mysql',
    'DB_PORT' => '3306',
    'DB_NAME' => 'nome_do_banco',
    'DB_USER' => 'usuario',
    'DB_PASSWORD' => 'senha',
];
```

No InfinityFree, a configuração precisa estar disponível no servidor como `src/config/database.local.php`, ou ser fornecida por variáveis de ambiente. O nome `database.infinityfree.php` é apenas uma convenção local e não é carregado automaticamente por `database.php`.

## 4. Fluxos principais

### Cadastro

```text
create.php → expense-form.php → POST save.php → validateExpense()
→ INSERT parametrizado → redirect para index.php
```

### Edição

```text
GET edit.php?id=ID → SELECT parametrizado → formulário preenchido
→ POST edit.php?id=ID → validação → UPDATE parametrizado → index.php
```

### Exclusão

```text
POST delete.php → validação do ID → DELETE parametrizado → index.php
```

## 5. Segurança

Práticas já presentes:

- prepared statements;
- lista controlada para `ORDER BY`;
- validação de categorias e dados no servidor;
- escape HTML com `htmlspecialchars()`;
- exclusão via `POST`;
- separação entre arquivos públicos e internos;
- credenciais e banco local ignorados pelo Git.

Limitações que devem ser tratadas antes de um cenário multiusuário:

- não há autenticação nem autorização;
- não há proteção CSRF;
- qualquer visitante acessa os mesmos registros.

## 6. Publicação

1. Configure PHP e as extensões PDO necessárias;
2. Aponte o document root para `public/`, quando possível;
3. Disponibilize a configuração MySQL fora do controle de versão;
4. Crie o banco no painel do provedor;
5. Confirme host, porta, nome, usuário e senha;
6. Acesse a aplicação para criar o schema;
7. Verifique permissões, HTTPS, logs e backups;
8. Teste cadastro, edição, exclusão, filtros e ordenação.

O `.htaccess` na raiz encaminha requisições para `public/` em hospedagens que usam `htdocs` como document root e bloqueia acesso direto a `src/`, `database/` e `storage/`.

## 7. Verificações de desenvolvimento

```bash
php -l public/index.php
php -l public/save.php
php -l public/edit.php
php -l public/delete.php
php -l src/config/database.php
php -l src/config/expenses.php
```

Também validar todos os fluxos em SQLite antes de publicar no MySQL.
