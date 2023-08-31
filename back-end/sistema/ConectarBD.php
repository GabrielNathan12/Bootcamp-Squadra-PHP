<?php 
    $arquivoEnv = parse_ini_file('./.env', true);

    $dbNome = $arquivoEnv['DB_NOME'];
    $dbHost = $arquivoEnv['DB_HOST'];
    $dbUser = $arquivoEnv['DB_USER'];
    $dbSenha = $arquivoEnv['DB_SENHA'];


    $conexao = new PDO('pgsql:dbname='.$dbNome .';host='.$dbHost, $dbUser, $dbSenha);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
