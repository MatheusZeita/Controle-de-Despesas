# Controle de Despesas

Aplicação web simples para controle financeiro pessoal, desenvolvida com PHP puro, SQLite, HTML5, CSS3 e JavaScript nativo.

O projeto foi pensado como uma aplicação pequena, funcional e apresentável em portfólio. Permite registrar despesas, consultar lançamentos, editar e excluir registros, além de acompanhar totais e filtros em um dashboard responsivo.

## Funcionalidades

- Cadastro, listagem, edição e exclusão de despesas;
- Categorias fixas no sistema;
- Total gasto, quantidade de despesas e média por despesa;
- Filtro por categoria e período;
- Ordenação por data ou valor;
- Gastos agrupados por categoria;
- Validação dos dados do formulário;
- Interface responsiva para desktop e mobile.

## Tecnologias

- PHP 8.5+;
- SQLite e PDO;
- HTML5 e CSS3;
- JavaScript nativo, usado pontualmente na confirmação de exclusão.

## Requisitos

- PHP 8.1 ou superior;
- Extensões `PDO`, `pdo_sqlite` e `sqlite3`;
- Navegador atualizado.

Verifique o ambiente com:

```powershell
php -v
php -m | Select-String "PDO|sqlite"
```

O resultado deve incluir `PDO`, `pdo_sqlite` e `sqlite3`.

## Como executar localmente

Na pasta do projeto, execute:

```powershell
php -S localhost:8000 -t public
```

Depois acesse:

```text
http://localhost:8000
```

Para encerrar o servidor, pressione `Ctrl + C`.

## Banco de dados

O projeto usa SQLite. O arquivo é criado automaticamente em:

```text
storage/database.sqlite
```

Na primeira execução, [src/config/database.php](src/config/database.php) abre a conexão PDO e executa o esquema de [database/schema.sql](database/schema.sql).

A tabela `expenses` possui:

| Campo | Tipo | Descrição |
|---|---|---|
| `id` | INTEGER | Identificador único |
| `description` | TEXT | Descrição do lançamento |
| `amount` | DECIMAL(10, 2) | Valor da despesa |
| `category` | TEXT | Categoria fixa |
| `expense_date` | DATE | Data da despesa |
| `created_at` | DATETIME | Data de criação |

O banco está no `.gitignore` para evitar o envio de dados pessoais ao repositório.

### Configuração para produção

O arquivo [src/config/database.php](src/config/database.php) aceita dois modos:

- `sqlite` (padrão local);
- `mysql` (recomendado para hospedagem compartilhada).

As variáveis estão documentadas em `.env.example`. Em um painel de hospedagem, configure-as como variáveis de ambiente ou adapte a configuração ao mecanismo de variáveis oferecido pelo provedor. Para MySQL, utilize também [database/schema.mysql.sql](database/schema.mysql.sql).

## Categorias

As categorias são definidas em [src/config/expenses.php](src/config/expenses.php):

- Alimentação
- Transporte
- Moradia
- Entretenimento
- Compras
- Saúde
- Educação
- Contas
- Outros

Não existe CRUD de categorias porque elas são fixas por decisão de escopo.

## Estrutura do projeto

```text
Controle de Despesas/
├── database/
│   └── schema.sql              # Estrutura da tabela expenses
├── public/
│   ├── index.php               # Dashboard principal
│   ├── create.php              # Tela de nova despesa
│   ├── save.php                # Processa o cadastro
│   ├── edit.php                # Tela e processamento da edição
│   ├── delete.php              # Processa a exclusão
│   ├── expense-form.php        # Formulário compartilhado
│   └── assets/css/style.css    # Estilos responsivos
├── src/config/
│   ├── database.php            # Conexão e inicialização do SQLite
│   └── expenses.php            # Categorias e validações
├── storage/database.sqlite     # Banco local, ignorado pelo Git
├── .gitignore
└── README.md
```

## Fluxos principais

### Cadastro

```text
create.php → expense-form.php → save.php → PDO/SQLite → index.php
```

### Edição

`edit.php` recebe o ID, carrega os dados, reutiliza o formulário, valida os valores e executa um `UPDATE` com consulta preparada.

### Exclusão

`delete.php` aceita `POST`, valida o ID, executa o `DELETE` e redireciona para o dashboard.

## Validações implementadas

- Descrição obrigatória e limitada a 120 caracteres;
- Valor numérico maior que zero;
- Categoria pertencente à lista permitida;
- Data válida no formato `YYYY-MM-DD`;
- Consultas ao banco com prepared statements;
- Saídas de texto escapadas com `htmlspecialchars`;
- Ordenação limitada a opções previamente definidas.

## Responsividade

No desktop, os indicadores ficam lado a lado e os lançamentos aparecem em tabela.

No mobile:

- o header é reorganizado verticalmente;
- os indicadores são empilhados;
- os filtros ocupam uma coluna;
- a tabela vira uma sequência de cartões;
- cada campo recebe um rótulo próprio;
- botões e formulários ficam adequados ao toque.

## Verificações realizadas

Os arquivos PHP foram verificados com `php -l` e estão sem erros de sintaxe. Também foram verificados:

- inicialização do SQLite;
- abertura do dashboard e dos formulários;
- cadastro e exclusão de uma despesa de teste;
- carregamento do dashboard com filtros;
- resposta da aplicação em um user agent mobile;
- existência das extensões `PDO`, `pdo_sqlite` e `sqlite3`.

## Segurança atual

O projeto já utiliza PDO, prepared statements, lista controlada para ordenação, validação de categorias, escape de HTML e exclusão via `POST`.

Antes de usar a aplicação em produção com dados reais, recomenda-se adicionar:

- proteção CSRF;
- autenticação de usuário;
- configuração de erros específica para produção;
- backups do banco;
- headers de segurança;
- logs de aplicação.

## Publicação

O serviço escolhido precisa executar PHP e oferecer armazenamento persistente para o SQLite. Serviços estáticos ou serverless podem não preservar alterações no sistema de arquivos.

Alternativas:

1. usar hospedagem tradicional compatível com PHP e SQLite;
2. migrar para MySQL/MariaDB e usar um serviço com banco persistente.

Quando possível, configure o document root para `public/`, evitando expor diretamente `src/`, `database/` e `storage/`.

## Git e portfólio

Esta pasta ainda não é um repositório Git. Para iniciar o versionamento:

```powershell
git init
git add .
git commit -m "feat: cria controle de despesas"
```

Depois, crie um repositório remoto e publique o código. Ao finalizar o deploy, adicione neste README a URL da aplicação e screenshots do projeto.

## Próximas melhorias

- Proteção CSRF;
- Testes automatizados para validações e CRUD;
- Autenticação;
- Exportação CSV;
- Gráfico visual por categoria;
- Suporte a MySQL para produção;
- Melhorias de acessibilidade com testes de teclado e leitor de tela.

## Licença

Este projeto pode ser utilizado como projeto pessoal de estudo e portfólio. Caso seja publicado, adicione aqui a licença escolhida.
