<?php
    
    class ControladorUf{
        private $ufDAO;

        public function __construct(PDO $conexao){
            $this->ufDAO = new UfDAO($conexao);
        }

        public function criarUf(UF $uf){

        }

        public function atualizarUF(UF $uf){

        }

        public function listarUF(){
            $data = $this->ufDAO->listarTodosUFs();
           
            return $data;
    
        }

        public function deletarUF(){
            
        }
    }