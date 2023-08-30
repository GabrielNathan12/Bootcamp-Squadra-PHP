<?php
    class Bairro{
        private $codigoBairro;
        private $codigoMunicipio;
        private $nome;
        private $status;

        public function __construct($codigoBairro, $codigoMunicipio, $nome, $status){
            $this->codigoBairro = $codigoBairro;
            $this->codigoMunicipio = $codigoMunicipio;
            $this->nome = $nome;
            $this->status = $status;
        }

        public function getCodigoBairro(){
            return $this->codigoBairro;
        }
        public function getCodigoMunicipio(){
            return $this->codigoMunicipio;
        }
        public function getNomeBairro(){
            return $this->nome;
        }
        public function getStatusBairro(){
            return $this->status;
        }
        public function setCodigoBairro($codigoBairro){
            $this->codigoBairro = $codigoBairro;
        }
        public function setCodigoMunicipio($codigoMunicipio){
            $this->codigoMunicipio = $codigoMunicipio;
        }
        public function setNomeBairro($nome){
            $this->nome = $nome;
        }
        public function setStatusBairro($status){
            $this->status = $status;
        }
    }