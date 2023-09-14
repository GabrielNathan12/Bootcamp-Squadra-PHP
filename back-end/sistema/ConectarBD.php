<?php 
    include_once('../index.php');
    
    $dbNome = $dbNome;
    $dbHost = $dbHost;
    $dbUser = $dbUser;
    $dbSenha = $dbSenha;

    try{
        $conexao = new PDO('pgsql:dbname='.$dbNome .';host='.$dbHost, $dbUser, $dbSenha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    catch(Exception $e){
        echo "Erro ao conectar ao banco de dados: $e";
    }
    
    
    