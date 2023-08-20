<?php

function load($controlador, $action){
    try{
        $controleNamespace = "src\\uf\\controlador\\{$controlador}";
    
        if(!class_exists($controleNamespace)){
            throw new Exception("O Controller não existe");
        }
        
        $controller = new $controleNamespace();

        if(!method_exists($controller, $action)){
            throw new Exception("O método não existe");
        }
        $controller->$action();

    }
    catch(Exception $e){
        $e->getMessage();
    }
}

function indexAction() {
    load('UfControlador', 'index');
}

$rotas = [
    'GET' => [
        '/uf' => 'indexAction'
    ]
];

$metodo = 'GET';
$path = '/uf';

if (isset($rotas[$metodo][$path])) {
    $handler = $rotas[$metodo][$path];
    if (is_callable($handler)) {
        call_user_func($handler);
    } else {
        echo "Rota não definida";
    }
} else {
    echo "Rota não encontrada";
}
?>
