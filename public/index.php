<?php
    use Dotenv\Dotenv;
    use Dotenv\Exception\InvalidPathException;

    // Setup HTTP Header
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE");
    header('Access-Control-Allow-Credentials: true');
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Load library
    require_once '../vendor/autoload.php';
    // Load helpers
    require_once '../app/Helpers/PathHelper.php';
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