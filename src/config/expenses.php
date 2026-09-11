<?php

declare(strict_types=1);

function expenseCategories(): array
{
    return [
        'Alimentação',
        'Transporte',
        'Moradia',
        'Entretenimento',
        'Compras',
        'Saúde',
        'Educação',
        'Contas',
        'Outros',
    ];
}

function validateExpense(array $data): array
{
    $description = trim((string) ($data['description'] ?? ''));
    $amount = str_replace(',', '.', trim((string) ($data['amount'] ?? '')));
    $category = trim((string) ($data['category'] ?? ''));
    $expenseDate = trim((string) ($data['expense_date'] ?? ''));
    $errors = [];

    if ($description === '') {
        $errors['description'] = 'Informe uma descrição.';
    } elseif (strlen($description) > 120) {
        $errors['description'] = 'A descrição deve ter no máximo 120 caracteres.';
    }

    if (!is_numeric($amount) || (float) $amount <= 0) {
        $errors['amount'] = 'Informe um valor maior que zero.';
    }

    if (!in_array($category, expenseCategories(), true)) {
        $errors['category'] = 'Selecione uma categoria válida.';
    }

    $date = DateTime::createFromFormat('Y-m-d', $expenseDate);
    if (!$date || $date->format('Y-m-d') !== $expenseDate) {
        $errors['expense_date'] = 'Informe uma data válida.';
    }

    return [
        'data' => [
            'description' => $description,
            'amount' => number_format((float) $amount, 2, '.', ''),
            'category' => $category,
            'expense_date' => $expenseDate,
        ],
        'errors' => $errors,
    ];
}
