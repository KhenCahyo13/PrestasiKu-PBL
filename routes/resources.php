<?php
    $app->group('/resources', function ($group) {
        // Serve CSS files
        $group->get('/css/{file}', function ($request, $response, $args) {
            $filePath = __DIR__ . '/../resources/css/' . $args['file'];

            if (file_exists($filePath)) {
                $mimeType = mime_content_type($filePath);
                $response = $response->withHeader('Content-Type', $mimeType);
                $response->getBody()->write(file_get_contents($filePath));
                return $response;
            }

            return $response->withStatus(404)->write('File not found.');
        });

        // Serve JS files
        $group->get('/js/{file}', function ($request, $response, $args) {
            $filePath = __DIR__ . '/../resources/js/' . $args['file'];

            if (file_exists($filePath)) {
                $mimeType = mime_content_type($filePath);
                $response = $response->withHeader('Content-Type', $mimeType);
                $response->getBody()->write(file_get_contents($filePath));
                return $response;
            }

            return $response->withStatus(404)->write('File not found.');
        });

        // Serve icons
        $group->get('/icons/{file}', function ($request, $response, $args) {
            $filePath = __DIR__ . '/../public/assets/icons/' . $args['file'];

            if (file_exists($filePath)) {
                $mimeType = mime_content_type($filePath);
                $response = $response->withHeader('Content-Type', $mimeType);
                $response->getBody()->write(file_get_contents($filePath));
                return $response;
            }

            return $response->withStatus(404)->write('File not found.');
        });
    });
?>