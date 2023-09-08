<?php
    class BairroDAO{
        private $conexao;

        public function __construct($conexao){
            $this->conexao = $conexao;
        }

        public function listarBairros(){
            if(!empty($_GET)){
                $sql = "SELECT * FROM TB_BAIRRO WHERE 1=1";

                if(isset($_GET['codigoBairro'])){
                    $codigoBairro = intval($_GET['codigoBairro']);
                    $sql .= " AND codigoBairro = :codigoBairro";
                }
                if(isset($_GET['codigoMunicipio'])){
                    $codigoMunicipio = intval($_GET['codigoMunicipio']);
                    $sql .= " AND codigoMunicipio = :codigoMunicipio";
                }
                if(isset($_GET['nome'])){
                    $nome = $_GET['nome'];
                    $sql .= " AND nome = :nome";
                }
                if(isset($_GET['status'])){
                    $status = intval($_GET['status']);
                    $sql .= " AND status = :status"; 
                }

                $stmt = $this->conexao->prepare($sql);

                if(isset($codigoBairro)){
                    $stmt->bindParam(':codigoBairro',$codigoBairro);
                }
                if(isset($codigoMunicipio)){
                    $stmt->bindParam(':codigoMunicipio',$codigoMunicipio);
                }
                if(isset($nome)){
                    $stmt->bindParam(':nome',$nome);
                }
                if(isset($status)){
                    $stmt->bindParam(':status',$status);
                }

                $stmt->execute();
                  
                if($codigoBairro){
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else {
                        return $resultado;
                    }
                }
                else if($codigoMunicipio){
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else{
                        return $resultado;
                    }
                }
                else if($nome){
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else {
                        return $resultado;
                    }
                }
                else if($status){
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else {
                        return $resultado;
                    }
                }
            }
            else {
                $sql = "SELECT * FROM TB_BAIRRO";
                $stmt = $this->conexao->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }
        }

        private function verificarCodigoMunicipio($codigoMunicipio){
            $sql = "SELECT COUNT(*) FROM TB_MUNICIPIO WHERE codigoMunicipio = :codigoMunicipio";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoMunicipio', $codigoMunicipio);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }

        public function criarBairro(Bairro $bairro){
            try{
                $nome = $bairro->getNomeBairro();
                
                if(!is_string($nome)){
                    throw new ErrosDaAPI('Nome não é uma string', 400);
                }
                $codigoMunicipio = $bairro->getCodigoMunicipio();

                if(!is_int($codigoMunicipio)){
                    throw new ErrosDaAPI('codigoMunicipio não é um número', 400);
                }
                $achou = $this->verificarCodigoMunicipio($codigoMunicipio);

                if(!$achou){
                    throw new ErrosDaAPI('codigoMunicipio não existe no banco de dados', 400);
                }
                $status = $bairro->getStatusBairro();

                if(!is_int($status)){
                    throw new ErrosDaAPI('Status não é um número', 400);
                }
                else if($status != 1 and $status != 2){
                    throw new ErrosDaAPI('Status com valor inválido', 400);
                }
                $sql = "INSERT INTO TB_BAIRRO (nome, codigoMunicipio, status) VALUES(:nome,:codigoMunicipio, :status)";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':codigoMunicipio', $codigoMunicipio);
                $stmt->bindParam(':status', $status);
                $stmt->execute();

                $resultado = $this->listarBairros();
                return $resultado;
            }
            catch(PDOException $e){
                if($e->getCode() == '23505'){
                    throw new ErrosDaAPI('Dados duplicados: nome já estão inclusos no banco de dado nesse Municipio', 400);
                }
                else {
                    throw new ErrosDaAPI('Erro interno no servidor: '. $e->getMessage());
                }
            }            
        }

        private function codigoBairroExiste($codigoBairro){
            $sql = "SELECT COUNT(*) FROM TB_BAIRRO WHERE codigoBairro = :codigoBairro";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoBairro', $codigoBairro);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
        public function atualizarBairro($codigoBairro, Bairro $bairro){
            try{
                if($this->codigoBairroExiste($codigoBairro)){
                    $codigoBairro = $bairro->getCodigoBairro();
                    $codigoMunicipio = $bairro->getCodigoMunicipio();
                    $nome = $bairro->getNomeBairro();
                    $status = $bairro->getStatusBairro();

                    $achouMunicipio = $this->verificarCodigoMunicipio($codigoMunicipio);
                    
                    if(!is_int($codigoBairro)){
                        throw new ErrosDaAPI('codigoBairro não nao e um numero', 400);
                    }
                    if(!$achouMunicipio){
                        throw new ErrosDaAPI('Esse codigoMunicipio não existe no banco de dados', 400);
                    }
                    if(!is_string($nome)){
                        throw new ErrosDaAPI('Nome não é uma string', 400);
                    }
                    if(!is_int($status)){
                        throw new ErrosDaAPI('Status não é um número', 400);
                    }
                    else if($status != 1 and $status != 2){
                        throw new ErrosDaAPI('Status com valor inválido', 400);
                    }
                    $sql = "UPDATE TB_BAIRRO SET codigoMunicipio = :codigoMunicipio, nome = :nome, status = :status WHERE codigoBairro = :codigoBairro";
                    $stmt = $this->conexao->prepare($sql);
                    $stmt->bindParam(':codigoBairro', $codigoBairro);
                    $stmt->bindParam(':codigoMunicipio', $codigoMunicipio);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':status', $status);
                    $stmt->execute();

                    return $this->listarBairros();
                }
                else{
                    throw new ErrosDaAPI('Código do bairro não existe no Banco de Dados', 400);
                }
            }
            catch(PDOException $e){
                if($e->getCode() == '23505'){
                    throw new ErrosDaAPI('Dados duplicados: nome já estão inclusos no banco de dado para essa UF', 400);
                }
                else {
                    throw new ErrosDaAPI('Erro interno no servidor: '. $e->getMessage());
                }
            }
        }
        public function deletarBairro($codigoBairro){
            if($this->codigoBairroExiste($codigoBairro)){
                $sql = 'DELETE FROM TB_BAIRRO WHERE codigoBairro = :codigoBairro';
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindParam(':codigoBairro', $codigoBairro);
                $stmt->execute();
                return $this->listarBairros();
            }
            else {
                throw new ErrosDaAPI('Código da Municipio não existe no Banco de Dados', 400);
            }
        }
    }