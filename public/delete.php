<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/src/config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $statement = getDatabase()->prepare('DELETE FROM expenses WHERE id = :id');
        $statement->execute(['id' => $id]);
    }
}

header('Location: index.php?success=deleted');
exit;

