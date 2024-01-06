<?php

require_once __DIR__ . '/src/routes/api.php';
require_once __DIR__ . '/src/routes/web.php';




if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
    $router = new Api();
    $router->handleRequest();
} else {
    $router = new Web();
    $router->handleRequest();
}