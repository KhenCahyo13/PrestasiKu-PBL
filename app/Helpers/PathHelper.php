<?php
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

    function images($path = '') {
        return '/assets/images/' . ltrim($path, '/');
    }

    function icons($path = '') {
        return '/assets/icons/' . ltrim($path, '/');
    }

    function css($path = '') {
        return '/assets/css/' . ltrim($path, '/');
    }    

    function js($path = '') {
        return '/assets/js/' . ltrim($path, '/');
    }

    function url($path = '') {
        return '/PrestasiKu-PBL/web/' . ltrim($path, '/');
    }
?>