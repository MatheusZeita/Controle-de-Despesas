Controle de Despesas

Aplicação web para controle financeiro pessoal, desenvolvida com PHP, SQLite, HTML5, CSS3 e JavaScript nativo.

O projeto foi desenvolvido com foco em simplicidade, funcionalidade e apresentação em portfólio. Permite cadastrar, consultar, editar e excluir despesas, além de acompanhar totais, categorias e filtros por meio de um dashboard responsivo.

Demonstração

Aplicação online:
https://controle-de-despesas.site.je

Repositório:
https://github.com/MatheusZeita/Controle-de-Despesas

Funcionalidades

- Cadastro, listagem, edição e exclusão de despesas;
- Categorias fixas no sistema;
- Exibição do total gasto, quantidade de despesas e média por despesa;
- Filtro por categoria e período;
- Ordenação por data ou valor;
- Agrupamento dos gastos por categoria;
- Validação dos dados dos formulários;
- Interface responsiva para desktop e dispositivos móveis.

Tecnologias

- PHP 8.5+
- SQLite
- PDO
- HTML5
- CSS3
- JavaScript nativo

O JavaScript é utilizado pontualmente, principalmente na confirmação da exclusão de despesas.

Banco de dados

O projeto utiliza SQLite como banco de dados.

O arquivo do banco é criado automaticamente em:

storage/database.sqlite

Na inicialização, "src/config/database.php" estabelece a conexão utilizando PDO e executa, quando necessário, o esquema definido em:

database/schema.sql

A tabela "expenses" possui os seguintes campos:

Campo| Tipo| Descrição
"id"| INTEGER| Identificador único
"description"| TEXT| Descrição da despesa
"amount"| DECIMAL(10, 2)| Valor da despesa
"category"| TEXT| Categoria da despesa
"expense_date"| DATE| Data da despesa
"created_at"| DATETIME| Data de criação do registro

O banco de dados local está incluído no ".gitignore" para evitar que dados pessoais ou registros de teste sejam enviados ao repositório.

Categorias

As categorias são definidas em:

src/config/expenses.php

Atualmente, estão disponíveis:

- Alimentação
- Transporte
- Moradia
- Entretenimento
- Compras
- Saúde
- Educação
- Contas
- Outros

As categorias são fixas por decisão de escopo do projeto. Por isso, não existe CRUD para gerenciamento de categorias.

Estrutura do projeto

Controle-de-Despesas/
├── database/
│   └── schema.sql              # Estrutura da tabela expenses
├── public/
│   ├── index.php               # Dashboard principal
│   ├── create.php              # Tela de nova despesa
│   ├── save.php                # Processamento do cadastro
│   ├── edit.php                # Tela e processamento da edição
│   ├── delete.php              # Processamento da exclusão
│   ├── expense-form.php        # Formulário compartilhado
│   └── assets/
│       └── css/
│           └── style.css       # Estilos responsivos
├── src/
│   └── config/
│       ├── database.php        # Conexão e configuração do banco
│       └── expenses.php        # Categorias e validações
├── storage/
│   └── database.sqlite         # Banco local, ignorado pelo Git
├── .gitignore
└── README.md

Fluxos principais

Cadastro

create.php
    ↓
expense-form.php
    ↓
save.php
    ↓
PDO / SQLite
    ↓
index.php

O formulário envia os dados para "save.php", que realiza as validações necessárias e registra a despesa no banco de dados.

Edição

"edit.php" recebe o ID da despesa, carrega os dados correspondentes e reutiliza o formulário. Após a validação, a alteração é realizada por meio de uma consulta "UPDATE" preparada.

Exclusão

"delete.php" recebe uma requisição "POST", valida o ID informado, executa o "DELETE" e redireciona o usuário para o dashboard.

Validações e boas práticas

O projeto possui validações para os principais dados recebidos pelos formulários:

- Descrição obrigatória e limitada a 120 caracteres;
- Valor numérico maior que zero;
- Categoria pertencente à lista permitida;
- Data válida no formato "YYYY-MM-DD";
- Consultas ao banco utilizando prepared statements;
- Saídas de texto escapadas com "htmlspecialchars";
- Ordenação limitada a opções previamente definidas.

Responsividade

A interface foi desenvolvida para se adaptar a diferentes tamanhos de tela.

Desktop

- Indicadores apresentados lado a lado;
- Lançamentos exibidos em formato de tabela;
- Filtros organizados de acordo com o espaço disponível.

Mobile

- Header reorganizado verticalmente;
- Indicadores empilhados;
- Filtros organizados em uma coluna;
- Tabela adaptada para visualização em formato de cartões;
- Campos apresentados com seus respectivos rótulos;
- Botões e formulários adequados à interação por toque.

Verificações realizadas

Durante o desenvolvimento, foram realizadas verificações envolvendo:

- Sintaxe dos arquivos PHP com "php -l";
- Inicialização e abertura do banco SQLite;
- Acesso ao dashboard;
- Abertura dos formulários;
- Cadastro de uma despesa de teste;
- Exclusão de uma despesa de teste;
- Aplicação de filtros no dashboard;
- Funcionamento da interface em um user agent mobile;
- Disponibilidade das extensões "PDO", "pdo_sqlite" e "sqlite3".

Segurança

O projeto utiliza algumas práticas importantes, incluindo:

- PDO;
- Prepared statements;
- Validação dos dados recebidos;
- Lista controlada para ordenação;
- Validação das categorias;
- Escape de conteúdo HTML;
- Exclusão realizada via "POST".

Para uma utilização em produção com dados reais, ainda é recomendável implementar:

- Proteção contra CSRF;
- Autenticação e autorização de usuários;
- Configuração adequada de erros para produção;
- Backups periódicos do banco de dados;
- Headers de segurança;
- Logs da aplicação.

Publicação

A aplicação está disponível online em:

https://controle-de-despesas.site.je

O projeto utiliza SQLite e, portanto, depende de armazenamento persistente para manter os dados cadastrados.

Para ambientes de produção, é importante garantir que:

- O PHP esteja disponível e configurado corretamente;
- O SQLite esteja habilitado;
- O armazenamento utilizado pela aplicação seja persistente;
- O diretório "storage/" possua as permissões necessárias;
- Arquivos internos da aplicação não sejam expostos diretamente pela web.

Sempre que possível, o document root deve apontar para:

public/

Isso ajuda a evitar a exposição direta de diretórios internos como:

src/
database/
storage/

GitHub

O código-fonte está disponível no GitHub:

https://github.com/MatheusZeita/Controle-de-Despesas

Autor

Desenvolvido por Matheus Zeíta Silva.

Este projeto possui um único programador e contribuidor.

Próximas melhorias

- Implementação de proteção CSRF;
- Testes automatizados para validações e operações CRUD;
- Autenticação de usuários;
- Exportação de dados em CSV;
- Gráficos de gastos por categoria;
- Melhorias de acessibilidade;
- Testes de navegação por teclado e leitores de tela.

Licença

Este projeto foi desenvolvido por Matheus Zeíta Silva para fins de estudo e portfólio.