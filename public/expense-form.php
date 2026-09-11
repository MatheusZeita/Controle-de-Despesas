<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/config/expenses.php';

$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escape($pageTitle) ?> | Controle de Despesas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container narrow-container">
        <a class="back-link" href="index.php">← Voltar para o dashboard</a>
        <section class="content-card form-card">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">Controle financeiro</p>
                    <h1><?= $escape($pageTitle) ?></h1>
                    <p>Preencha os dados abaixo para registrar sua despesa.</p>
                </div>
            </div>

            <?php if ($errors !== []): ?>
                <div class="alert error-alert">Revise os campos destacados e tente novamente.</div>
            <?php endif; ?>

            <form method="post" action="<?= $escape($formAction) ?>" class="expense-form">
                <label>
                    Descrição
                    <input type="text" name="description" maxlength="120" required value="<?= $escape($data['description'] ?? '') ?>">
                    <?php if (isset($errors['description'])): ?><small class="field-error"><?= $escape($errors['description']) ?></small><?php endif; ?>
                </label>

                <div class="form-grid">
                    <label>
                        Valor
                        <input type="number" name="amount" min="0.01" step="0.01" required value="<?= $escape($data['amount'] ?? '') ?>">
                        <?php if (isset($errors['amount'])): ?><small class="field-error"><?= $escape($errors['amount']) ?></small><?php endif; ?>
                    </label>
                    <label>
                        Data
                        <input type="date" name="expense_date" required value="<?= $escape($data['expense_date'] ?? '') ?>">
                        <?php if (isset($errors['expense_date'])): ?><small class="field-error"><?= $escape($errors['expense_date']) ?></small><?php endif; ?>
                    </label>
                </div>

                <label>
                    Categoria
                    <select name="category" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach (expenseCategories() as $category): ?>
                            <option value="<?= $escape($category) ?>" <?= ($data['category'] ?? '') === $category ? 'selected' : '' ?>><?= $escape($category) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['category'])): ?><small class="field-error"><?= $escape($errors['category']) ?></small><?php endif; ?>
                </label>

                <div class="form-actions">
                    <a class="button secondary-button" href="index.php">Cancelar</a>
                    <button class="button" type="submit">Salvar despesa</button>
                </div>
            </form>
        </section>
    </main>
</body>
</html>
