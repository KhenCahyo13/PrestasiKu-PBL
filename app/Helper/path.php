<?php
    function assets($path = '') {
        $publicDir = __DIR__ . '/../../public';
        return $path ? $publicDir . '/' . ltrim($path, '/') : $publicDir;
    }

    function views($path = '') {
        $viewsDir = __DIR__ . '/../../resources/views';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }
?>