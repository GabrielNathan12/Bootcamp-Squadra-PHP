<?php
    require_once('../sistema/ConectarBD.php');
    require_once('../pessoa/ExecutarPessoa.php');
    require_once('../pessoa/controller/ControladorPessoa.php');
    require_once('../pessoa/dao/PessoaDao.php');
    require_once('../pessoa/model/Pessoa.php');
    
    require_once('../endereco/model/Endereco.php');

    require_once('../sistema/ErrosDaAPI.php');
    
    header('Content-Type: application/json');


    $requisicao = $_SERVER['REQUEST_METHOD'];

    $executarPessoa = new ExecutarPessoa($conexao);
    http_response_code(200);

    echo json_encode($executarPessoa->executar($requisicao));