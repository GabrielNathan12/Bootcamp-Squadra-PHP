<?php
   
   $arqEnv = __DIR__ . '/.env';

   if (file_exists($arqEnv)) {
      $linhas = file($arqEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      if ($linhas) {
         foreach ($linhas as $linha) {
               list($chaves, $valor) = explode('=', $linha, 2);
               $chaves = trim($chaves);
               $valor = trim($valor);
               putenv("$chaves=$valor");
               $_ENV[$chaves] = $valor;
               $_SERVER[$chaves] = $valor;
         }
      }
   }
   else {
      die('arquivo de configuração do banco de dados ".env" não encontrado.');
   }

   $tipoBanco = getenv('DBTIPO');
   $dbNome = getenv('DBNOME');
   $dbHost = getenv('DBHOST');
   $dbUser = getenv('DBUSER');
   $dbSenha = getenv('DBSENHA');
   


       

    

   

    


    