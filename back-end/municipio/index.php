<?php
    require_once('../sistema/ConectarBD.php');
    require_once('../sistema/ErrosDaAPI.php');
    require_once('../municipio/ExecutarMunicipio.php');
    require_once('../municipio/dao/MunicipioDAO.php');
    require_once('../municipio/controller/ControladorMunicipio.php');
    require_once('../municipio/model/Municipio.php');


    header('Content-Type: application/json');

    $requisicao = $_SERVER['REQUEST_METHOD'];

    $executarMunicipio = new ExecutarMunicipio($conexao);

    http_response_code(200);

    echo json_encode($executarMunicipio->executar($requisicao));
    