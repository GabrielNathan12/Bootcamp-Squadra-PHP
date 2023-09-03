<?php
    class PessoaDao{
        private $conexao;

        public function __construct($conexao){
            $this->conexao = $conexao;
        }
        private function codigoBairroExiste($codigoBairro){
            $sql = "SELECT COUNT(*) FROM TB_BAIRRO WHERE codigoBairro = :codigoBairro";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoBairro', $codigoBairro);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
        private function codigoPessoaExiste($codigoPessoa){
            $sql = "SELECT COUNT(*) FROM TB_PESSOA WHERE codigoPessoa = :codigoPessoa";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoPessoa', $codigoPessoa);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
        private function criarEnderecos($codigoPessoa, $enderecos){
            try{
                $codigoPessoa = intval($codigoPessoa);
                if($this->codigoPessoaExiste($codigoPessoa)){
                    foreach($enderecos as $endereco){
                        $codigoBairro = $endereco['codigoBairro'];
                        $nomeRua = $endereco['nomeRua'];
                        $numero = $endereco['numero'];
                        $complemento = $endereco['complemento'];
                        $cep = $endereco['cep'];

                        if(!$this->codigoBairroExiste($codigoBairro)){
                            continue;
                            throw new ErrosDaAPI('codigoBairro nao existe no Banco de Dados', 400);
                        }
                        if(!is_string($nomeRua)){
                            continue;
                            throw new ErrosDaAPI('nomeRua nao e uma string', 400);
                        }
                        
                        if(!is_string($numero)){
                            continue;
                            throw new ErrosDaAPI('numero nao e um numero', 400);
                        }
                        
                        if(!is_string($complemento)){
                            continue;
                            throw new ErrosDaAPI('complemento nao e uma string', 400);
                        }
                        
                        if(!is_string($cep)){
                            continue;
                            throw new ErrosDaAPI('cep nao e uma string', 400);
                        }
                        
                        else if(strlen($cep) > 9){
                            continue;
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
                }
                else {
                    throw new ErrosDaAPI('codigoPessoa nao existe no Banco de Dados', 400);
                }
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI){
                    http_response_code($e->getCode());
                    echo json_encode(array("mensagem" => $e->getMessage(), "status" => $e->getCode()));
                }
                else {
                    http_response_code(500); 
                    echo json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }
        }
        public function criarPessoa(Pessoa $pessoa, $enderecos){
            try{
                $nome = $pessoa->getNomePessoa();
                
                if(!is_string($nome)){
                    throw new ErrosDaAPI('Nome não é uma string', 400);
                }
                $sobrenome = $pessoa->getSobrenome();

                if(!is_string($sobrenome)){
                    throw new ErrosDaAPI('Sobrenome não é uma string', 400);
                }
                $idade = $pessoa->getIdade();

                if(!is_int($idade)){
                    throw new ErrosDaAPI('Idade não é um numero', 400);
                }
                $login = $pessoa->getLogin();

                if(!is_string($login)){
                    throw new ErrosDaAPI('Login não é uma string', 400);
                }
                $senha = $pessoa->getSenha();

                if(!is_string($senha)){
                    throw new ErrosDaAPI('Senha não é uma string', 400);
                }
                
                $status = $pessoa->getStatus();

                if(!is_int($status)){
                    throw new ErrosDaAPI('Status não é um número', 400);
                }
                else if($status != 1 and $status != 2){
                    throw new ErrosDaAPI('Status com valor inválido', 400);
                }
                $sql = "INSERT INTO TB_PESSOA (nome, sobrenome, idade, login, senha,status ) VALUES(:nome,:sobrenome, :idade, :login, :senha, :status)";
                
                $stmt = $this->conexao->prepare($sql);
                
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':sobrenome', $sobrenome);
                $stmt->bindParam(':idade', $idade);
                $stmt->bindParam(':login', $login);
                $stmt->bindParam(':senha', $senha);
                $stmt->bindParam(':status', $status);
                $stmt->execute();
                
                $codigoPessoa = $this->conexao->lastInsertId();
                
                $this->criarEnderecos($codigoPessoa, $enderecos);

                $resultado = $this->listarPessoa();
                return $resultado;
            }
            catch(PDOException $e){
                if($e->getCode() == '23505'){
                    throw new ErrosDaAPI('Dados duplicados: login já estão inclusos no banco de dados', 400);
                }
                else {
                    throw new ErrosDaAPI('Erro interno no servidor: '. $e->getMessage());
                }
            }  
        }
        public function atualizarPessoa($codigoPessoa, Pessoa $pessoa){

        }
        public function deletarPessoa($codigoPessoa){

        }
        public function listarPessoa(){
            $sql = "SELECT * FROM TB_PESSOA";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        }
    }