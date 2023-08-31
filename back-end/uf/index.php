<?php 
    header('Content-Type: application/json');
    //https://github.com/vaamonde/dell-linuxmint/blob/master/software/26-postgresql.md
    //require_once('./controller/ControladorUf.php');
    

    $requisicao = $_SERVER['REQUEST_METHOD'];

    switch ($requisicao){
        case 'GET':
            
            break;
        case 'POST':
            break;
        case 'PUT':
            break;
        case 'DELETE':
            break;
        default:
            $this->http_response_code(500, 'Requisição não implementada');
    }
    var_dump($requisicao);