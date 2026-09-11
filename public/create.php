<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/config/expenses.php';

$data = [
    'description' => '',
    'amount' => '',
    'category' => '',
    'expense_date' => date('Y-m-d'),
];
$errors = [];
$pageTitle = 'Nova despesa';
$formAction = 'save.php';

require __DIR__ . '/expense-form.php';

