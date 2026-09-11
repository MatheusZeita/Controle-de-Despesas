<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/config/database.php';
require_once dirname(__DIR__) . '/src/config/expenses.php';

$database = getDatabase();
$selectedCategory = trim((string) ($_GET['category'] ?? ''));
$dateFrom = trim((string) ($_GET['date_from'] ?? ''));
$dateTo = trim((string) ($_GET['date_to'] ?? ''));
$selectedSort = (string) ($_GET['sort'] ?? 'date_desc');

if (!in_array($selectedCategory, expenseCategories(), true)) {
    $selectedCategory = '';
}

$sortOptions = [
    'date_desc' => 'expense_date DESC, id DESC',
    'date_asc' => 'expense_date ASC, id ASC',
    'amount_desc' => 'amount DESC, id DESC',
    'amount_asc' => 'amount ASC, id ASC',
];
$orderBy = $sortOptions[$selectedSort] ?? $sortOptions['date_desc'];
$conditions = [];
$parameters = [];

if ($selectedCategory !== '') {
    $conditions[] = 'category = :category';
    $parameters['category'] = $selectedCategory;
}
if ($dateFrom !== '') {
    $conditions[] = 'expense_date >= :date_from';
    $parameters['date_from'] = $dateFrom;
}
if ($dateTo !== '') {
    $conditions[] = 'expense_date <= :date_to';
    $parameters['date_to'] = $dateTo;
}

$where = $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);
$statement = $database->prepare("SELECT id, description, amount, category, expense_date FROM expenses{$where} ORDER BY {$orderBy}");
$statement->execute($parameters);
$expenses = $statement->fetchAll();

$totalAmount = array_sum(array_map(static fn (array $expense): float => (float) $expense['amount'], $expenses));
$expenseCount = count($expenses);
$averageAmount = $expenseCount > 0 ? $totalAmount / $expenseCount : 0;
$categoryTotals = [];

foreach ($expenses as $expense) {
    $category = $expense['category'];
    $categoryTotals[$category] = ($categoryTotals[$category] ?? 0) + (float) $expense['amount'];
}
arsort($categoryTotals);

$successMessages = [
    'created' => 'Despesa cadastrada com sucesso.',
    'updated' => 'Despesa atualizada com sucesso.',
    'deleted' => 'Despesa excluída com sucesso.',
];
$success = $successMessages[$_GET['success'] ?? ''] ?? null;
$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Despesas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <div>
                <p class="brand-kicker">Finanças pessoais</p>
                <h1>Controle de Despesas</h1>
            </div>
            <a class="button header-button" href="create.php">+ Nova despesa</a>
        </div>
    </header>

    <main class="container">
        <section class="summary-section" aria-label="Resumo financeiro">
            <div class="summary-intro"><p class="eyebrow">Visão geral</p><h2>Seu mês em números</h2></div>
            <div class="summary-grid">
                <article class="summary-card"><span>Total gasto</span><strong>R$ <?= number_format($totalAmount, 2, ',', '.') ?></strong></article>
                <article class="summary-card"><span>Despesas registradas</span><strong><?= $expenseCount ?></strong></article>
                <article class="summary-card"><span>Média por despesa</span><strong>R$ <?= number_format($averageAmount, 2, ',', '.') ?></strong></article>
            </div>
        </section>

        <?php if ($success !== null): ?><div class="alert success-alert"><?= $escape($success) ?></div><?php endif; ?>

        <details class="filters-panel" <?= ($selectedCategory !== '' || $dateFrom !== '' || $dateTo !== '' || $selectedSort !== 'date_desc') ? 'open' : '' ?>>
            <summary><span class="filter-title">Filtros</span><span class="filter-hint">Categoria, período e ordenação</span><span class="summary-chevron">⌄</span></summary>
            <form method="get" class="filters-form">
                <label>Categoria
                    <select name="category"><option value="">Todas</option>
                        <?php foreach (expenseCategories() as $category): ?><option value="<?= $escape($category) ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>><?= $escape($category) ?></option><?php endforeach; ?>
                    </select>
                </label>
                <label>De<input type="date" name="date_from" value="<?= $escape($dateFrom) ?>"></label>
                <label>Até<input type="date" name="date_to" value="<?= $escape($dateTo) ?>"></label>
                <label>Ordenar por
                    <select name="sort"><option value="date_desc" <?= $selectedSort === 'date_desc' ? 'selected' : '' ?>>Mais recentes</option><option value="date_asc" <?= $selectedSort === 'date_asc' ? 'selected' : '' ?>>Mais antigas</option><option value="amount_desc" <?= $selectedSort === 'amount_desc' ? 'selected' : '' ?>>Maior valor</option><option value="amount_asc" <?= $selectedSort === 'amount_asc' ? 'selected' : '' ?>>Menor valor</option></select>
                </label>
                <div class="filter-actions"><button class="button" type="submit">Aplicar</button><a class="button secondary-button" href="index.php">Limpar</a></div>
            </form>
        </details>

        <section class="expenses-section">
            <div class="section-heading"><div><p class="eyebrow">Lançamentos</p><h2>Despesas</h2></div><span class="record-count"><?= $expenseCount ?> registro(s)</span></div>
            <?php if ($expenses === []): ?>
                <div class="empty-state"><div class="empty-icon">+</div><h3>Nenhuma despesa encontrada</h3><p>Cadastre uma despesa ou altere os filtros selecionados.</p></div>
            <?php else: ?>
                <div class="table-wrapper"><table>
                    <thead><tr><th>Descrição</th><th>Categoria</th><th>Data</th><th>Valor</th><th></th></tr></thead>
                    <tbody><?php foreach ($expenses as $expense): ?><tr>
                        <td class="description-cell" data-label="Descrição"><?= $escape($expense['description']) ?></td><td data-label="Categoria"><span class="category-label"><?= $escape($expense['category']) ?></span></td><td data-label="Data"><?= date('d/m/Y', strtotime($expense['expense_date'])) ?></td><td class="amount-cell" data-label="Valor">R$ <?= number_format((float) $expense['amount'], 2, ',', '.') ?></td>
                        <td class="actions-cell" data-label="Ações"><a href="edit.php?id=<?= (int) $expense['id'] ?>">Editar</a><form method="post" action="delete.php" onsubmit="return confirm('Excluir esta despesa?');"><input type="hidden" name="id" value="<?= (int) $expense['id'] ?>"><button type="submit" class="delete-button">Excluir</button></form></td>
                    </tr><?php endforeach; ?></tbody>
                </table></div>
            <?php endif; ?>
        </section>

        <?php if ($categoryTotals !== []): ?><section class="category-summary"><div class="section-heading"><div><p class="eyebrow">Distribuição</p><h2>Gastos por categoria</h2></div></div><div class="category-list"><?php foreach ($categoryTotals as $category => $amount): ?><div class="category-row"><span><?= $escape($category) ?></span><strong>R$ <?= number_format($amount, 2, ',', '.') ?></strong></div><?php endforeach; ?></div></section><?php endif; ?>
    </main>
</body>
</html>
