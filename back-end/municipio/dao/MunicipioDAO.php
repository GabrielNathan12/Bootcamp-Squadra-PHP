<?php
    class MunicipioDAO{
        private $conexao;

        public function __construct(PDO $conexao){
            $this->conexao = $conexao;
        }
        public function listarMunicipios(){

            if(!empty($_GET)){
                $sql = "SELECT * FROM TB_MUNICIPIO WHERE 1=1";

                if(isset($_GET['codigoMunicipio'])){
                    $codigoMunicipio = intval($_GET['codigoMunicipio']);
                    $sql .= " AND codigoMunicipio = :codigoMunicipio";
                }

                if(isset($_GET['codigoUF'])){
                    $codigoUF = intval($_GET['codigoUF']);
                    $sql .= " AND codigoUF = :codigoUF";
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

                if(isset($codigoMunicipio)){
                    $stmt->bindParam(':codigoMunicipio',$codigoMunicipio);
                }
                if(isset($codigoUF)){
                    $stmt->bindParam(':codigoUF', $codigoUF);
                }
                if(isset($nome)){
                    $stmt->bindParam(':nome', $nome);
                }
                if(isset($status)){
                    $stmt->bindParam(':status', $status);
                }

                $stmt->execute();
                
                if($codigoMunicipio){
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else {
                        return $resultado;
                    }
                }
                else if($codigoUF){
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
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
                $sql = "SELECT * FROM TB_MUNICIPIO";
                $stmt = $this->conexao->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }
        }
        private  function verificaCodigoUF($codigoUF){
            $sql = "SELECT COUNT(*) FROM TB_UF WHERE codigoUF = :codigoUF";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoUF', $codigoUF);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
        public function criarMunicipio(Municipio $municipio){
            try{
                $nome = $municipio->getNomeMunicipio();

                if(!is_string($nome)){
                    throw new ErrosDaAPI('Nome não é uma string', 400); 
                }
            
                $codigoUF = $municipio->getCodigoUf();

                if(!is_int($codigoUF)){
                    throw new ErrosDaAPI('codigoUF não é um número', 400);
                }

                $achou = $this->verificaCodigoUF($codigoUF);

                if(!$achou){
                    throw new ErrosDaAPI('codigoUF não existe no banco de dados', 400);
                }
                $status = $municipio->getStatusMunicipio();

                if(!is_int($status)){
                    throw new ErrosDaAPI('Status não é um número', 400);
                }
                else if($status != 1 and $status != 2){
                    throw new ErrosDaAPI('Status com valor inválido', 400);
                }
                $sql = "INSERT INTO TB_MUNICIPIO (nome, codigoUF, status) VALUES (:nome, :codigoUF, :status)";
                $stmt = $this->conexao->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':codigoUF', $codigoUF);
                $stmt->bindParam(':status', $status);
                $stmt->execute();

                $resultado = $this->listarMunicipios();
                return $resultado;
            }
            catch(PDOException $e){
                if($e->getCode() == '23505'){
                    throw new ErrosDaAPI('Dados duplicados: nome já estão inclusos no banco de dado nesse Estado', 400);
                }
                else {
                    throw new ErrosDaAPI('Erro interno no servidor: '. $e->getMessage());
                }
              }            
        }
    }