<?php
declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $database->getConnection();
    echo "Connection successful!\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

