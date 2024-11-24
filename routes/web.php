<?php
    $app->group('/web', function($web) {
        $web->group('/auth', function($auth) {
            $auth->get('/login', function($request, $response) {
                $response->getBody()->write('halo');
                return $response;
            });
        });
    });
?>