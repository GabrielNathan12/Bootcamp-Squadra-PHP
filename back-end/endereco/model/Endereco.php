<?php
    class Endereco{
        private $codigoEndereco;
        private $codigoPessoa;
        private $codigoBairro;
        private $nomeRua;
        private $numero;
        private $complemento;
        private $cep;

        public function __construct($codigoEndereco, $codigoPessoa, $codigoBairro, $nomeRua, $numero,$complemento, $cep){
            $this->codigoEndereco = $codigoEndereco;
            $this->codigoPessoa = $codigoPessoa;
            $this->codigoBairro = $codigoBairro;
            $this->nomeRua = $nomeRua;
            $this->numero = $numero;
            $this->complemento = $complemento;
            $this->cep = $cep;
        }

        public function getCodigoEndereco(){
            return $this->codigoEndereco;
        }
        public function getCodigoPessoa(){
            return $this->codigoPessoa;
        }
        public function getCodigoBairro(){
            return $this->codigoBairro;
        }
        public function getNomerua(){
            return $this->nomeRua;
        }
        public function getNumero(){
            return $this->numero;
        }
        public function getComplemento(){
            return $this->complemento;
        }
        public function getCep(){
            return $this->cep;
        }
        public function setCodigoEndereco($codigoEndereco){
            $this->codigoEndereco = $codigoEndereco;
        }
        public function setCodigoPessoa($codigoPessoa){
            $this->codigoPessoa = $codigoPessoa;
        }
        public function setCodigoBairro($codigoBairro){
            $this->codigoBairro = $codigoBairro;
        }
        public function setNomeRua($nomeRua){
            $this->nomeRua = $nomeRua;
        }
        public function setNumero($numero){
            $this->numero = $numero;
        }
        public function setComplemento($complemento){
            $this->complemento = $complemento;
        }
        public function setCep($cep){
            $this->cep = $cep;
        }
    }