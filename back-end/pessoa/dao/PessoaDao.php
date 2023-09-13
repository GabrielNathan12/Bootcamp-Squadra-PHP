<?php

    include_once('../pessoa/dao/servicos/ListarPessoas.php');
    include_once('../pessoa/dao/servicos/CriarPessoa.php');
    include_once('../pessoa/dao/servicos/AtualizarPessoa.php');
    include_once('../pessoa/dao/servicos/DeletarPessoa.php');

    class PessoaDao{
        private $conexao;

        public function __construct($conexao){
            $this->conexao = $conexao;
        }

        protected function getConexao(){
            return $this->conexao;
        }

        public function listarPessoa() {
            $listarPessoa = new ListarPessoas($this->conexao);
            return $listarPessoa->listarPessoa();
        }
        
        public function criarPessoa(Pessoa $pessoa, $enderecos){
            $novaPessoa = new CriarPessoa($this->conexao);
            return $novaPessoa->criarPessoa($pessoa, $enderecos);
        }

        protected function codigoBairroExiste($codigoBairro){
            $sql = "SELECT COUNT(*) FROM TB_BAIRRO WHERE codigoBairro = :codigoBairro";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoBairro', $codigoBairro);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }

        protected function codigoPessoaExiste($codigoPessoa){
            $sql = "SELECT COUNT(*) FROM TB_PESSOA WHERE codigoPessoa = :codigoPessoa";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoPessoa', $codigoPessoa);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
        protected function codigoEnderecoExiste($codigoEndereco){
            $sql = "SELECT COUNT(*) FROM TB_ENDERECO WHERE codigoEndereco = :codigoEndereco";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoEndereco', $codigoEndereco);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
        
        public function atualizarPessoa($codigoPessoa, Pessoa $pessoa ,$enderecos){
            $pessoaAtualizada = new AtualizarPessoa($this->conexao);
            return $pessoaAtualizada->atualizarPessoa($codigoPessoa, $pessoa ,$enderecos);

        }

        public function listarCodigosEnderecosPorPessoa($codigoPessoa) {
            try {
                $sql = "SELECT codigoEndereco FROM TB_ENDERECO WHERE codigoPessoa = :codigoPessoa";
                $stmt = $this->getConexao()->prepare($sql);
                $stmt->bindParam(':codigoPessoa', $codigoPessoa, PDO::PARAM_INT);
                $stmt->execute();
    
                $codigosEnderecos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
                return $codigosEnderecos;
            } catch (PDOException $e) {
                throw new ErrosDaAPI('Erro ao listar códigos de endereços por pessoa: ' . $e->getMessage(), 500);
            }
        }
        
        protected function buscarEnderecos($codigoPessoa){
            $sql = "SELECT * FROM TB_ENDERECO WHERE codigoPessoa = :codigoPessoa";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoPessoa', $codigoPessoa);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deletarPessoa($codigoPessoa){
            $pessoaDeletada = new DeletarPessoa($this->conexao);

            return $pessoaDeletada->deletarPessoa($codigoPessoa);
        }
        
    }