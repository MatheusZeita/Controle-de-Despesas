# Controle de Despesas

Aplicação web para controle financeiro pessoal, desenvolvida como projeto de portfólio com PHP puro, SQLite/MySQL, HTML5, CSS3 e JavaScript nativo.

O projeto demonstra a construção de uma aplicação CRUD completa, com validação de dados, consultas parametrizadas, filtros, ordenação, indicadores financeiros e interface responsiva.

## Demonstração

- **Aplicação online:** https://controle-de-despesas.site.je
- **Repositório:** https://github.com/MatheusZeita/Controle-de-Despesas

## Principais funcionalidades

- Cadastro, listagem, edição e exclusão de despesas;
- Filtros por categoria e período;
- Ordenação por data ou valor;
- Total gasto, quantidade de lançamentos e média por despesa;
- Resumo dos gastos por categoria;
- Validação no servidor;
- Interface responsiva para desktop e dispositivos móveis.

## Tecnologias

- PHP 8.5+;
- PDO;
- SQLite para desenvolvimento local;
- MySQL para hospedagem;
- HTML5, CSS3 e JavaScript nativo.

## Como executar localmente

### Pré-requisitos

- PHP 8.5 ou superior;
- Extensão `PDO`;
- Extensão `pdo_sqlite` e/ou `pdo_mysql`;
- Um servidor web local, como o servidor embutido do PHP.

### Execução

Na raiz do projeto, execute:

```bash
php -S localhost:8000 -t public
```

Depois, acesse http://localhost:8000.

Por padrão, a aplicação utiliza SQLite e cria automaticamente `storage/database.sqlite` e a tabela `expenses` quando necessário.

## Configuração do banco

O projeto suporta dois ambientes:

- **Desenvolvimento:** SQLite, configurado por padrão;
- **Produção:** MySQL, configurado por variáveis de ambiente ou por `src/config/database.local.php`.

O arquivo `database.local.php` não deve ser versionado. Use `src/config/database.local.example.php` como modelo.

Para detalhes sobre arquitetura, banco de dados, publicação e segurança, consulte [`docs/technical.md`](docs/technical.md).

## Estrutura resumida

```text
database/
├── schema.sql              # Schema SQLite
└── schema.mysql.sql        # Schema MySQL

public/
├── index.php               # Dashboard
├── create.php              # Formulário de cadastro
├── save.php                # Processamento do cadastro
├── edit.php                # Formulário e processamento da edição
├── delete.php              # Processamento da exclusão
├── expense-form.php        # Formulário compartilhado
└── assets/css/style.css    # Estilos responsivos

src/config/
├── database.php             # Conexão PDO e inicialização do schema
└── expenses.php             # Categorias e validações

storage/
└── database.sqlite          # Banco local, ignorado pelo Git
```

## Decisões e limitações atuais

O projeto foi mantido intencionalmente simples e não utiliza framework. As categorias são fixas e não há autenticação: todos os visitantes acessam os mesmos lançamentos do banco configurado.

Para uso com dados reais, ainda devem ser implementados autenticação e autorização por usuário, proteção CSRF, política adequada de erros, backups, logs e headers de segurança.

## Próximas melhorias

- Autenticação e isolamento dos dados por usuário;
- Proteção contra CSRF;
- Testes automatizados;
- Exportação para CSV;
- Gráficos financeiros;
- Melhorias de acessibilidade;
- Paginação para grandes volumes de registros.

## Autor

Desenvolvido por **Matheus Zeíta Silva** como projeto de estudo e portfólio.
