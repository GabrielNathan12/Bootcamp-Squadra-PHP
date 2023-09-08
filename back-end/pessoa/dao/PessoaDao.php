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
        private function codigoEnderecoExiste($codigoEndereco){
            $sql = "SELECT COUNT(*) FROM TB_ENDERECO WHERE codigoEndereco = :codigoEndereco";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoEndereco', $codigoEndereco);
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
        public function atualizarPessoa($codigoPessoa, Pessoa $pessoa, $codigoEndereco, $enderecos){
            try {
                if ($this->codigoPessoaExiste($codigoPessoa)) {
                    $codigoPessoa = $pessoa->getCodigoPessoa();
                    $nome = $pessoa->getNomePessoa();
                    $sobrenome = $pessoa->getSobrenome();
                    $idade = $pessoa->getIdade();
                    $login = $pessoa->getLogin();
                    $senha = $pessoa->getSenha();
                    $status = $pessoa->getStatus();
        
                    if(!is_string($nome)) {
                        throw new ErrosDaAPI('Nome não é uma string', 400);
                    }
                    if(!is_string($sobrenome)) {
                        throw new ErrosDaAPI('Sobrenome não é uma string', 400);
                    }
                    if(!is_string($login)) {
                        throw new ErrosDaAPI('Login não é uma string', 400);
                    }
                    if(!is_string($senha)) {
                        throw new ErrosDaAPI('Senha não é uma string', 400);
                    }
        
                    if(!is_int($idade)) {
                        throw new ErrosDaAPI('Idade não é um número', 400);
                    }
                    if(!is_int($status)){
                        throw new ErrosDaAPI('Status não é um número', 400);
                    }
                    else if($status != 1 and $status != 2){
                        throw new ErrosDaAPI('Status com valor inválido', 400);
                    }
                    $sql = "UPDATE TB_PESSOA SET nome = :nome, sobrenome = :sobrenome, idade = :idade, login = :login,
                            senha = :senha, status = :status  WHERE codigoPessoa = :codigoPessoa";
        
                    $stmt = $this->conexao->prepare($sql);
                    $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':sobrenome', $sobrenome);
                    $stmt->bindParam(':idade', $idade);
                    $stmt->bindParam(':login', $login);
                    $stmt->bindParam(':senha', $senha);
                    $stmt->bindParam(':status', $status);
                    $stmt->execute();
        
                    $this->atualizarEndereco($codigoPessoa, $codigoEndereco, $enderecos);

                    $resultado = $this->listarPessoa();
                    return $resultado;
                } else {
                    throw new ErrosDaAPI('Código de Pessoa não existe no banco de dados', 400);
                }
            } catch (PDOException $e) {
                if ($e->getCode() == '23505') {
                    throw new ErrosDaAPI('Dados duplicados: email já estão inclusos no banco de dados', 400);
                } else {
                    throw new ErrosDaAPI('Erro interno no servidor: ' . $e->getMessage());
                }
            }
        }
        
        private function atualizarEndereco($codigoPessoa, $codigoEndereco, $enderecos){
            try {
                if($this->codigoEnderecoExiste($codigoEndereco)){
                    foreach ($enderecos as $endereco) {
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
            
                        $sql = "UPDATE TB_ENDERECO SET codigoPessoa = :codigoPessoa, codigoBairro = :codigoBairro,
                        nomeRua = :nomeRua, numero = :numero, complemento = :complemento, cep = :cep 
                        WHERE codigoEndereco = :codigoEndereco";
                        $stmt = $this->conexao->prepare($sql);

                        $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                        $stmt->bindParam(':codigoBairro', $codigoBairro);
                        $stmt->bindParam(':nomeRua', $nomeRua);
                        $stmt->bindParam(':numero', $numero);
                        $stmt->bindParam(':complemento', $complemento);
                        $stmt->bindParam(':cep', $cep);
                        $stmt->bindParam(':codigoEndereco', $codigoEndereco);
                        $stmt->execute();
                    }
                }else if(!$this->codigoEnderecoExiste($codigoEndereco)) {

                    $this->criarEnderecos($codigoPessoa, $enderecos);
                }
            } catch (ErrosDaAPI $e) {
                http_response_code($e->getCode());
                echo json_encode(array("mensagem" => $e->getMessage(), "status" => $e->getCode()));
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
            }
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
        public function listarPessoa() {
            if (!empty($_GET)) {

                $sql = "SELECT TP.codigoPessoa, TP.nome, TP.sobrenome, TP.idade, TP.login, TP.senha, TP.status,
                    json_agg(json_build_object(
                        'codigoEndereco', TE.codigoEndereco,
                        'codigoBairro', TB.codigoBairro,
                        'nomeRua', TE.nomeRua,
                        'numero', TE.numero,
                        'complemento', TE.complemento,
                        'cep', TE.cep,
                        'bairro', json_build_object(
                            'codigoBairro', TB.codigoBairro,
                            'codigoMunicipio', TM.codigoMunicipio,
                            'nome', TB.nome,
                            'status', TB.status,
                            'municipio', json_build_object(
                                'codigoMunicipio', TM.codigoMunicipio,
                                'codigoUF', TU.codigoUF,
                                'nome', TM.nome,
                                'status', TM.status,
                                'uf', json_build_object(
                                    'codigoUF', TU.codigoUF,
                                    'sigla', TU.sigla,
                                    'nome', TU.nome,
                                    'status', TU.status
                                )
                            )
                        )
                    )) AS enderecos
                FROM TB_PESSOA TP
                LEFT JOIN TB_ENDERECO TE ON TP.codigoPessoa = TE.codigoPessoa
                LEFT JOIN TB_BAIRRO TB ON TE.codigoBairro = TB.codigoBairro
                LEFT JOIN TB_MUNICIPIO TM ON TB.codigoMunicipio = TM.codigoMunicipio
                LEFT JOIN TB_UF TU ON TM.codigoUF = TU.codigoUF
                WHERE 1=1";
            
                if (isset($_GET['codigoPessoa'])) {
                    $codigoPessoa = intval($_GET['codigoPessoa']);
                    $sql .= " AND TP.codigoPessoa = :codigoPessoa";
                }
                if(isset($_GET['nome'])){
                    $nome = $_GET['nome'];
                    $sql .= " AND TP.nome = :nome";
                }
                if (isset($_GET['idade'])) {
                    $idade = intval($_GET['idade']);
                    $sql .= " AND TP.idade = :idade";
                }
                if (isset($_GET['login'])) {
                    $login = $_GET['login'];
                    $sql .= " AND TP.login = :login";
                }
            
                if (isset($_GET['status'])) {
                    $status = intval($_GET['status']);
                    $sql .= " AND TP.status = :status";
                }
            
                $sql .= " GROUP BY TP.codigoPessoa, TP.nome, TP.sobrenome, TP.idade, TP.login, TP.senha, TP.status";
            
                $stmt = $this->conexao->prepare($sql);
            
                if (isset($codigoPessoa)) {
                    $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                }
                if (isset($nome)) {
                    $stmt->bindParam(':nome', $nome);
                }
                if (isset($idade)) {
                    $stmt->bindParam(':idade', $idade);
                }
                if (isset($login)) {
                    $stmt->bindParam(':login', $login);
                }
            
                if (isset($status)) {
                    $stmt->bindParam(':status', $status);
                }
            
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if (count($resultado) > 0) {
                    foreach ($resultado as &$posicao) {
                        $enderecosDecodificados = json_decode($posicao['enderecos'], true);
                        $posicao['enderecos'] = $enderecosDecodificados;
                    }
                    return $resultado;
                } else {
                    return [];
                }
            }
            
            
            else {
                $sql = "SELECT * FROM TB_PESSOA";
                $stmt = $this->conexao->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                foreach ($resultado as &$pessoa) {
                    $pessoa['enderecos'] = [];
                }   
                return $resultado;  
            }
            
        }
    }