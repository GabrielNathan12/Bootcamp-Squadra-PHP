<?php

$rotas = [
    '/uf' => 'ufAction',
    '/municipio' => 'municipioAction'
];

function ufAction() {
    echo "Página UF";
}

function municipioAction() {
    echo "Página Município";
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$path = rtrim($path, '/');

if (array_key_exists($path, $rotas)) {
    $handler = $rotas[$path];
    if (function_exists($handler)) {
        call_user_func($handler);
    } else {
        echo "Rota não definida";
    }
} else {
    echo "Rota não encontrada";
}
