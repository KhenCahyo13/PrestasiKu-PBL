<?php
    function assets($path = '') {
        $publicDir = __DIR__ . '/../../public';
        return $path ? $publicDir . '/' . ltrim($path, '/') : $publicDir;
    }

    function views($path = '') {
        $viewsDir = __DIR__ . '/../../resources/views';
        return $path ? $viewsDir . '/' . ltrim($path, '/') : $viewsDir;
    }

    function css($path = '') {
        return '/PrestasiKu-PBL/resources/css/' . ltrim($path, '/');
    }    

    function js($path = '') {
        return '/PrestasiKu-PBL/resources/js/' . ltrim($path, '/');
    }  
?>