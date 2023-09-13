<?php

    include_once('../pessoa/dao/servicos/ListarPessoas.php');
    include_once('../pessoa/dao/servicos/CriarPessoa.php');
    include_once('../pessoa/dao/servicos/AtualizarPessoa.php');

    class PessoaDao{
        private $conexao;

        public function __construct($conexao){
            $this->conexao = $conexao;
        }

        protected function getConexao(){
            return $this->conexao;
        }
        //se eu passar o codigo de endereco eu altero os dados 
        // se eu nao passar o codigo de endereco eu adiciono aqui,
        // e de acordo com os enderecos que estao no registrados, se eu nao passar esses enderecos no PUT, eu apago o que eu não passei
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
        private function criarEnderecos($codigoPessoa, $enderecos) {
        
            $codigoPessoa = intval($codigoPessoa);
            if ($this->codigoPessoaExiste($codigoPessoa)) {
                foreach ($enderecos as $endereco) {
                    $codigoBairro = $endereco['codigoBairro'];
                    $nomeRua = $endereco['nomeRua'];
                    $numero = $endereco['numero'];
                    $complemento = $endereco['complemento'];
                    $cep = $endereco['cep'];
    
                    if (!$this->codigoBairroExiste($codigoBairro)) {
                        throw new ErrosDaAPI('codigoBairro nao existe no Banco de Dados', 400);
                    }
    
                    if (!is_string($nomeRua) || empty($nomeRua)) {
                        throw new ErrosDaAPI('nomeRua nao e uma string ou esta vazio', 400);
                    }
    
                    if (!is_string($numero) || empty($numero)) {
                        throw new ErrosDaAPI('numero nao e uma string ou esta vazio', 400);
                    }
    
                    if (!is_string($complemento) || empty($complemento)) {
                        throw new ErrosDaAPI('complemento nao e uma string ou esta vazio', 400);
                    }
    
                    if (!is_string($cep) || empty($cep)) {
                        throw new ErrosDaAPI('cep nao e uma string ou esta vazio', 400);
                    } else if (strlen($cep) > 9) {
                        throw new ErrosDaAPI('cep possui mais de 9 caracteres', 400);
                    }
    
                    $sql = "INSERT INTO TB_ENDERECO (codigoPessoa, codigoBairro, nomeRua, numero, complemento, cep) VALUES(:codigoPessoa,:codigoBairro, :nomeRua, :numero, :complemento, :cep)";
                    $stmt = $this->conexao->prepare($sql);
    
    
                    $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                    $stmt->bindParam(':codigoBairro', $codigoBairro);
                    $stmt->bindParam(':nomeRua', $nomeRua);
                    $stmt->bindParam(':numero', $numero);
                    $stmt->bindParam(':complemento', $complemento);
                    $stmt->bindParam(':cep', $cep);
                    $stmt->execute();
                }
            } else {
                throw new ErrosDaAPI('codigoPessoa nao existe no Banco de Dados', 400);
            }
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
                // Trate erros de banco de dados aqui
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
            if($this->codigoPessoaExiste($codigoPessoa)){
                $sql = 'DELETE FROM TB_PESSOA WHERE codigoPessoa = :codigoPessoa';
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                $stmt->execute();
                return $this->listarPessoa();
            }
            else {
                throw new ErrosDaAPI('Código da pessoa não existe no Banco de Dados', 400);
            }
        }
        
    }