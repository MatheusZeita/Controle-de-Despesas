<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/config/database.php';
require_once dirname(__DIR__) . '/src/config/expenses.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$database = getDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validation = validateExpense($_POST);

    if ($validation['errors'] !== []) {
        $data = array_merge($_POST, $validation['data']);
        $errors = $validation['errors'];
    } else {
        $statement = $database->prepare(
            'UPDATE expenses
             SET description = :description, amount = :amount, category = :category, expense_date = :expense_date
             WHERE id = :id'
        );
        $statement->execute([...$validation['data'], 'id' => $id]);
        header('Location: index.php?success=updated');
        exit;
    }
} else {
    $statement = $database->prepare('SELECT description, amount, category, expense_date FROM expenses WHERE id = :id');
    $statement->execute(['id' => $id]);
    $data = $statement->fetch();

    if (!$data) {
        header('Location: index.php');
        exit;
    }

    $errors = [];
}

$pageTitle = 'Editar despesa';
$formAction = 'edit.php?id=' . $id;
require __DIR__ . '/expense-form.php';

