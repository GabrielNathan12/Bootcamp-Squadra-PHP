<?php
    require_once('../sistema/ConectarBD.php');
    require_once('../sistema/ErrosDaAPI.php');
    require_once('../municipio/ExecutarMunicipio.php');
    require_once('../municipio/dao/MunicipioDAO.php');
    require_once('../municipio/controller/ControladorMunicipio.php');
    require_once('../municipio/model/Municipio.php');

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header('Content-Type: application/json');

    $requisicao = $_SERVER['REQUEST_METHOD'];

    $executarMunicipio = new ExecutarMunicipio($conexao);

    http_response_code(200);

    echo json_encode($executarMunicipio->executar($requisicao));
    