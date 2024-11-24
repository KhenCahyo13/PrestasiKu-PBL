<?php
    function assets($path = '') {
        return '/PrestasiKu-PBL/public/assets/' . ltrim($path, '/');
    }

    function views($path = '') {
        $viewsDir = __DIR__ . '/../../resources/views';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function templates($path = '') {
        $viewsDir = __DIR__ . '/../../resources/templates';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function css($path = '') {
        return '/PrestasiKu-PBL/resources/css/' . ltrim($path, '/');
    }

    function js($path = '') {
        return '/PrestasiKu-PBL/resources/js/' . ltrim($path, '/');
    }  
?>