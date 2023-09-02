<?php
    require_once('../sistema/ConectarBD.php');
    require_once('../sistema/ErrosDaAPI.php');
    require_once('../bairro/model/Bairro.php');
    require_once('../bairro/dao/BairroDAO.php');
    require_once('../bairro/controller/ControladorBairro.php');
    require_once('../bairro/ExecutarBairro.php');

    header('Content-Type: application/json');

    $requisicao = $_SERVER['REQUEST_METHOD'];

    $executarBairro = new ExcecutarBairro($conexao);

    http_response_code(200);

    echo json_encode($executarBairro->executar($requisicao));