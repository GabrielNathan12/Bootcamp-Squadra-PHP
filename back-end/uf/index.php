<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header('Content-Type: application/json');
    require_once('../sistema/ConectarBD.php');
    require_once('../uf/ExecutarUf.php');
    require_once('../uf/controller/ControladorUf.php');
    require_once('../uf/dao/UfDAO.php');
    require_once('../uf/model/UF.php');

    require_once('../sistema/ErrosDaAPI.php');
    
    


    $requisicao = $_SERVER['REQUEST_METHOD'];

    $executarUf = new ExecutarUf($conexao);
    http_response_code(200);

    echo json_encode($executarUf->executar($requisicao));