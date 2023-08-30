<?php 
    class Municipio{
        private $codigoMunicipio;
        private $codigoUF;
        private $nome;
        private $status;

        public function __construct($codigoMunicipio, $codigoUF, $nome, $status) {
            $this->codigoMunicipio = $codigoMunicipio;
            $this->codigoUF = $codigoUF;
            $this->nome = $nome;
            $this->status = $status;
        }

        public function getCodigoMunicipio(){
            return $this->codigoMunicipio;
        }
        public function getCodigoUf(){
            return $this->codigoUF;
        }
        public function getNomeMunicipio(){
            return $this->nome;
        }
        public function getStatusMunicipio(){
            return $this->status;
        }
        public function setCodigoMunicipio($codigoMunicipio){
            $this->codigoMunicipio = $codigoMunicipio;
        }
        public function setCodigoUf($codigoUF){
            $this->codigoUF = $codigoUF;
        }
        public function setNomeMunicipio($nome){
            $this->nome = $nome;
        }
        public function setStatusMunicipio($status){
            $this->status = $status;
        }
    }