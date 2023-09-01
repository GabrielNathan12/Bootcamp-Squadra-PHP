<?php 
    class ErrosDaAPI extends Exception {
        
        public function __construct($mensagem = "", $codigo = 400, Throwable $anterior = null) {
            parent::__construct($mensagem, $codigo, $anterior);
        }   
    }
