<?php 
    //$arquivoEnv = parse_ini_file('./.env', true);

    $dbNome = 'postgres';
    $dbHost = 'localhost';
    $dbUser = 'postgres';
    $dbSenha = 'node';

    $conexao = new PDO('pgsql:dbname='.$dbNome .';host='.$dbHost, $dbUser, $dbSenha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    