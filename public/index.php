<?php
    use Dotenv\Dotenv;
    use Dotenv\Exception\InvalidPathException;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once '../vendor/autoload.php';
    require_once '../routes/api.php';

    try {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    } catch (InvalidPathException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
?>