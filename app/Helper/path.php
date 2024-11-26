<?php
    function assets($path = '') {
        return '/PrestasiKu-PBL/public/assets/' . ltrim($path, '/');
    }

    function views($path = '') {
        $viewsDir = __DIR__ . '/../../resources/views/pages';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function layouts($path = '') {
        $viewsDir = __DIR__ . '/../../resources/views/layouts';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function components($path = '') {
        $baseDir = __DIR__ . '/../../resources/views/components';
        return $baseDir . '/' . ltrim($path, '/');
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