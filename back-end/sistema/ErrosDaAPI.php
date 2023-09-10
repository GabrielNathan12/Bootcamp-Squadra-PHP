<?php 
    class ErrosDaAPI extends Exception {
        public function __construct($mensagem = "", $codigo = 400, Throwable $anterior = null) {
            parent::__construct($mensagem, $codigo, $anterior);
        }
    
        public function getRespostaJSON() {
            $resposta = array(
                'mensagem' => $this->getMessage(),
                'status' => $this->getCode()
            );
    
            return json_encode($resposta);
        }
    }
    