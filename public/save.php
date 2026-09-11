<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/config/database.php';
require_once dirname(__DIR__) . '/src/config/expenses.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$validation = validateExpense($_POST);

if ($validation['errors'] !== []) {
    $data = array_merge($_POST, $validation['data']);
    $errors = $validation['errors'];
    $pageTitle = 'Nova despesa';
    $formAction = 'save.php';
    require __DIR__ . '/expense-form.php';
    exit;
}

$statement = getDatabase()->prepare(
    'INSERT INTO expenses (description, amount, category, expense_date)
     VALUES (:description, :amount, :category, :expense_date)'
);
$statement->execute($validation['data']);

header('Location: index.php?success=created');
exit;

