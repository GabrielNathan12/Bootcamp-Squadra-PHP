<?php
    class Pessoa{
        private $codigoPessoa;
        private $nome;
        private $sobrenome;
        private $idade;
        private $login;
        private $senha;
        private $status;
        
        public function __construct($codigoPessoa, $nome, $sobrenome, $idade, $login, $senha, $status){
            $this->codigoPessoa = $codigoPessoa;
            $this->nome = $nome;
            $this->sobrenome = $sobrenome;
            $this->idade = $idade;
            $this->login = $login;
            $this->senha = $senha;
            $this->status = $status;
        }

        public function getCodigoPessoa(){
            return $this->codigoPessoa;
        }
        public function getNomePessoa(){
            return $this->nome;
        }
        public function getSobrenome(){
            return $this->sobrenome;
        }
        public function getIdade(){
            return $this->idade;
        }
        public function getLogin(){
            return $this->login;
        }
        public function getSenha(){
            return $this->senha;
        }
        public function getStatus(){
            return $this->status;
        }

        public function setCodigoPessoa($codigoPessoa){
            $this->codigoPessoa = $codigoPessoa;
        }
        public function setNomePessoa($nome){
            $this->nome = $nome;
        }
        public function setSobrenome($sobrenome){
            $this->sobrenome = $sobrenome;
        }
        public function setIdade($idade){
            $this->idade = $idade;
        }
        public function setLogin($login){
            $this->login = $login;
        }
        public function setSenha($senha){
            $this->senha = $senha;
        }
        public function setStatusPessoa($status){
            $this->status = $status;
        }
    }   
    