<?php
    use Dotenv\Dotenv;
    use Dotenv\Exception\InvalidPathException;

    // Load library
    require_once '../vendor/autoload.php';
    // Load routes
    require_once '../routes/app.php';
    // Load helpers
    require_once '../app/Helper/path.php';

    try {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    } catch (InvalidPathException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
?>