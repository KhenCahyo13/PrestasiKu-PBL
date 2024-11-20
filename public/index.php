<?php
    use Dotenv\Dotenv;
    use Dotenv\Exception\InvalidPathException;

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