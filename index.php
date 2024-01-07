<?php

require_once __DIR__ . '/src/routes/api.php';
require_once __DIR__ . '/src/routes/web.php';



try {

    if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
        $router = new Api();
        $router->handleRequest();
    } else {
        $router = new Web();
        $router->handleRequest();
    }


} catch (\Exception $e) {
    echo '<div style="color: red; margin-top: 20px;">Error inesperado: ' . $e->getMessage() . '</div>';
    require_once __DIR__ . '/../../resources/views/error_view.php';
}