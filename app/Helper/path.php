<?php
    function assets($path = '') {
        return '/PrestasiKu-PBL/public/assets/' . ltrim($path, '/');
    }

    function views($path = '') {
        $viewsDir = __DIR__ . '/../../resources/views';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function layouts($path = '') {
        $viewsDir = __DIR__ . '/../../resources/layouts';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function components($path = '') {
        $viewsDir = __DIR__ . '/../../resources/components';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function css($path = '') {
        return '/PrestasiKu-PBL/resources/css/' . ltrim($path, '/');
    }

    function js($path = '') {
        return '/PrestasiKu-PBL/resources/js/' . ltrim($path, '/');
    }

    function url($path = '') {
        return '/PrestasiKu-PBL/public/web/' . ltrim($path, '/');
    }
?>