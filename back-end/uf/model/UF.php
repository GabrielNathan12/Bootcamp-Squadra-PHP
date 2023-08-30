<?php
    class UF{
        private $codigoUF;
        private $nome;
        private $sigla;
        private $status;

        public function __construct($codigoUF, $nome, $sigla, $status) {
            $this->codigoUF = $codigoUF;
            $this->nome = $nome;
            $this->sigla = $sigla;
            $this->status = $status;  
        }

        public function getCodigoUf(){
            return $this->codigoUF;
        }
        public function getNomeUf(){
            return $this->nome;
        }
        public function getSiglaUf(){
            return $this->sigla;
        }
        public function getStatusUf(){
            return $this->status;
        }
        public function setCodigoUf($codigoUF){
            $this->codigoUF = $codigoUF;
        }
        public function setNomeUF($nome){
            $this->nome = $nome;
        }
        public function setSiglaUf($sigla){
            $this->sigla = $sigla;
        }
        public function setStatusUf($status){
            $this->status = $status;
        }
    }