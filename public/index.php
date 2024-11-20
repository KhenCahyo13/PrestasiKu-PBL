<?php
    use Dotenv\Dotenv;

    require_once '../vendor/autoload.php';
    require_once '../routes/api.php';

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
?>