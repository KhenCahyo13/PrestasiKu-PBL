<?php
    use Dotenv\Dotenv;
    use Dotenv\Exception\InvalidPathException;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Load library
    require_once '../vendor/autoload.php';
    // Load helpers
    require_once '../app/Helpers/path.php';
    // Load routes
    require_once '../routes/app.php';

    try {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    } catch (InvalidPathException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
?>