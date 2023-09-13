<?php

class CriarPessoa extends PessoaDao{

    public function __construct(PDO $conexao){
        parent::__construct($conexao);
    }

    public function criarPessoa(Pessoa $pessoa, $enderecos){
        $enderecoValido = true;

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

            $sql = "INSERT INTO TB_PESSOA (nome, sobrenome, idade, login, senha, status) VALUES(:nome, :sobrenome, :idade, :login, :senha, :status)";
            $stmt = parent::getConexao()->prepare($sql);

            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':sobrenome', $sobrenome);
            $stmt->bindParam(':idade', $idade);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':status', $status);
            $stmt->execute();

            $codigoPessoa = parent::getConexao()->lastInsertId();

            foreach ($enderecos as $endereco) {
                $codigoBairro = $endereco['codigoBairro'];
                $nomeRua = $endereco['nomeRua'];
                $numero = $endereco['numero'];
                $complemento = $endereco['complemento'];
                $cep = $endereco['cep'];

                try{
                    if (!parent::codigoBairroExiste($codigoBairro)) {
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

                    $sql2 = "INSERT INTO TB_ENDERECO (codigoPessoa, codigoBairro, nomeRua, numero, complemento, cep) 
                    VALUES(:codigoPessoa, :codigoBairro, :nomeRua, :numero, :complemento, :cep)";
                    
                    $stmt = parent::getConexao()->prepare($sql2);

                    $stmt->bindParam(':codigoPessoa', $codigoPessoa); 
                    $stmt->bindParam(':codigoBairro', $codigoBairro);
                    $stmt->bindParam(':nomeRua', $nomeRua);
                    $stmt->bindParam(':numero', $numero);
                    $stmt->bindParam(':complemento', $complemento);
                    $stmt->bindParam(':cep', $cep);
                    $stmt->execute();
                }   
                catch(ErrosDaAPI $e){
                    $enderecoValido = false;
                    if($e instanceof ErrosDaAPI) {
                        http_response_code($e->getCode()); 
                        return json_decode($e->getRespostaJSON());
                    }
                    else {
                        http_response_code(500); 
                        return json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                    }
                    break;
                }
            }
        
            if($enderecoValido){
                
                $resultado = $this->listarPessoa();
                return $resultado;
            }
        }
        catch(PDOException $e){
            if($e->getCode() == '23505'){
            
                throw new ErrosDaAPI('Dados duplicados: login já estão inclusos no banco de dados', 400);
            }
            else {
                throw new ErrosDaAPI('Erro interno no servidor: '. $e->getMessage());
            }
        } 
        catch(Exception $e){
            if($e instanceof ErrosDaAPI) {
                http_response_code($e->getCode()); 
                return json_decode($e->getRespostaJSON());
            }
            else {
                http_response_code(500); 
                return json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
            }
        }
    }
}
