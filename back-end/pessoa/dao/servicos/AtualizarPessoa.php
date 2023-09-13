<?php
class AtualizarPessoa extends PessoaDao {
    public function __construct(PDO $conexao) {
        parent::__construct($conexao);
    }

    public function atualizarPessoa($codigoPessoa, Pessoa $pessoa, $enderecos) {
        try {
            if ($this->codigoPessoaExiste($codigoPessoa)) {
                $nome = $pessoa->getNomePessoa();
                $sobrenome = $pessoa->getSobrenome();
                $idade = $pessoa->getIdade();
                $login = $pessoa->getLogin();
                $senha = $pessoa->getSenha();
                $status = $pessoa->getStatus();
    
                if (!is_string($nome)) {
                    throw new ErrosDaAPI('Nome não é uma string', 400);
                }
                if (!is_string($sobrenome)) {
                    throw new ErrosDaAPI('Sobrenome não é uma string', 400);
                }
                if (!is_string($login)) {
                    throw new ErrosDaAPI('Login não é uma string', 400);
                }
                if (!is_string($senha)) {
                    throw new ErrosDaAPI('Senha não é uma string', 400);
                }
    
                if (!is_int($idade)) {
                    throw new ErrosDaAPI('Idade não é um número', 400);
                }
                if (!is_int($status)) {
                    throw new ErrosDaAPI('Status não é um número', 400);
                } else if ($status != 1 && $status != 2) {
                    throw new ErrosDaAPI('Status com valor inválido', 400);
                }
    
                $sql = "UPDATE TB_PESSOA SET nome = :nome, sobrenome = :sobrenome, idade = :idade, login = :login,
                        senha = :senha, status = :status  WHERE codigoPessoa = :codigoPessoa";
    
                $stmt = $this->getConexao()->prepare($sql);

                
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':sobrenome', $sobrenome);
                $stmt->bindParam(':idade', $idade);
                $stmt->bindParam(':login', $login);
                $stmt->bindParam(':senha', $senha);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                $stmt->execute();
    
                $this->atualizarEnderecos($codigoPessoa ,$enderecos);
    
                $resultado = $this->listarPessoa();

                return $resultado;
            } else {
                throw new ErrosDaAPI('Código de Pessoa não existe no banco de dados', 404);
            }
        } catch (PDOException $e) {
            if ($e->getCode() == '23505') {
                throw new ErrosDaAPI('Dados duplicados: email já estão inclusos no banco de dados', 400);
            } else {
                throw new ErrosDaAPI('Erro interno no servidor: ' . $e->getMessage(), 500);
            }
        } catch (ErrosDaAPI $e) {
            throw $e;
        } catch (Exception $e) {
            throw new ErrosDaAPI('Erro interno no servidor: ' . $e->getMessage(), 500);
        }
    }
    

    private function atualizarEnderecos($codigoPessoa, $enderecos) {
        try {
    
            $codigoPessoa = intval($codigoPessoa);
            $novoEnderecos = array();
            
            foreach ($enderecos as &$endereco) {
                $codigoEndereco = $endereco['codigoEndereco'];
                $codigoBairro = $endereco['codigoBairro'];
                $nomeRua = $endereco['nomeRua'];
                $numero = $endereco['numero'];
                $complemento = $endereco['complemento'];
                $cep = $endereco['cep'];
    
                if (!$this->codigoBairroExiste($codigoBairro)) {
                    throw new ErrosDaAPI('Código de Bairro não existe no Banco de Dados', 400);
                }
                if (!is_string($nomeRua)) {
                    throw new ErrosDaAPI('Nome da Rua não é uma string', 400);
                }
    
                if (!is_string($numero)) {
                    throw new ErrosDaAPI('Número não é uma string', 400);
                }
    
                if (!is_string($complemento)) {
                    throw new ErrosDaAPI('Complemento não é uma string', 400);
                }
    
                if (!is_string($cep)) {
                    throw new ErrosDaAPI('CEP não é uma string', 400);
                } else if (strlen($cep) > 9) {
                    throw new ErrosDaAPI('CEP possui mais de 9 caracteres', 400);
                }
    
                if (!is_null($codigoEndereco) && parent::codigoEnderecoExiste($codigoEndereco)) {
                    $this->atualizarEnderecoExistente($codigoEndereco,$codigoPessoa, $codigoBairro, $nomeRua, $numero, $complemento, $cep);
                }
            }
            
            $this->excluirEnderecosAusentes($codigoPessoa, $enderecos);
    
        } catch (ErrosDaAPI $e) {
            http_response_code($e->getCode());
            echo json_encode(array("mensagem" => $e->getMessage(), "status" => $e->getCode()));
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
        }
    }
    
    private function criarNovoEndereco($codigoPessoa, $codigoBairro, $nomeRua, $numero, $complemento, $cep){
        
        $sql = "INSERT INTO TB_ENDERECO (codigoPessoa, codigoBairro, nomeRua, numero, complemento, cep) 
        VALUES(:codigoPessoa, :codigoBairro, :nomeRua, :numero, :complemento, :cep)";
        
        $stmt = $this->getConexao()->prepare($sql);

        $stmt->bindParam(':codigoPessoa', $codigoPessoa); 
        $stmt->bindParam(':codigoBairro', $codigoBairro);
        $stmt->bindParam(':nomeRua', $nomeRua);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':cep', $cep);
        $stmt->execute();
      
    }
    
    private function atualizarEnderecoExistente($codigoEndereco,$codigoPessoa, $codigoBairro, $nomeRua, $numero, $complemento, $cep){
        $sql = "UPDATE TB_ENDERECO SET codigoPessoa = :codigoPessoa, codigoBairro = :codigoBairro,
        nomeRua = :nomeRua, numero = :numero, complemento = :complemento, cep = :cep 
        WHERE codigoEndereco = :codigoEndereco";
        $stmt = $this->getConexao()->prepare($sql);

        $stmt->bindParam(':codigoPessoa', $codigoPessoa);
        $stmt->bindParam(':codigoBairro', $codigoBairro);
        $stmt->bindParam(':nomeRua', $nomeRua);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':complemento', $complemento);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':codigoEndereco', $codigoEndereco);
        $stmt->execute();
    }

    private function excluirEnderecosAusentes($codigoPessoa, $enderecos) {
        try {
            $codigosEnderecosFornecidos = array();
            $codigosEnderecosNovos = array();
    
            foreach ($enderecos as $endereco) {
                $codigoEndereco = $endereco['codigoEndereco'];
    
                if (!is_null($codigoEndereco) && $codigoEndereco !== 0) {
                    $codigosEnderecosFornecidos[] = $codigoEndereco;
                } else {
                    // Se o código do endereço for nulo ou zero, é um novo endereço
                    $codigosEnderecosNovos[] = $endereco;
                }
            }
    
            if (!empty($codigosEnderecosFornecidos)) {
                $codigosEnderecosPessoa = parent::listarCodigosEnderecosPorPessoa($codigoPessoa);
    
                $codigosParaManter = array_intersect($codigosEnderecosPessoa, $codigosEnderecosFornecidos);
    
                if (!empty($codigosParaManter)) {
                    $codigosParaManterStr = implode(",", $codigosParaManter);
                    $sql = "DELETE FROM TB_ENDERECO WHERE codigoEndereco NOT IN ($codigosParaManterStr) AND codigoPessoa = :codigoPessoa";
                    $stmt = parent::getConexao()->prepare($sql);
                    $stmt->bindParam(':codigoPessoa', $codigoPessoa, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }

            foreach ($codigosEnderecosNovos as $novoEndereco) {
                $codigoBairro = $novoEndereco['codigoBairro'];
                $nomeRua = $novoEndereco['nomeRua'];
                $numero = $novoEndereco['numero'];
                $complemento = $novoEndereco['complemento'];
                $cep = $novoEndereco['cep'];
    
                if (!$this->codigoBairroExiste($codigoBairro)) {
                    throw new ErrosDaAPI('Código de Bairro não existe no Banco de Dados', 400);
                }
                if (!is_string($nomeRua)) {
                    throw new ErrosDaAPI('Nome da Rua não é uma string', 400);
                }
    
                if (!is_string($numero)) {
                    throw new ErrosDaAPI('Número não é uma string', 400);
                }
    
                if (!is_string($complemento)) {
                    throw new ErrosDaAPI('Complemento não é uma string', 400);
                }
    
                if (!is_string($cep)) {
                    throw new ErrosDaAPI('CEP não é uma string', 400);
                } else if (strlen($cep) > 9) {
                    throw new ErrosDaAPI('CEP possui mais de 9 caracteres', 400);
                }
    
                $this->criarNovoEndereco($codigoPessoa, $codigoBairro, $nomeRua, $numero, $complemento, $cep);
            }
        } catch (PDOException $e) {
            throw new ErrosDaAPI('Erro ao excluir/endereços ausentes: ' . $e->getMessage(), 500);
        }
    }
}
