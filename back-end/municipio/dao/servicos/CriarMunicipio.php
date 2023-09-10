<?php
    class CriarMunicipio extends MunicipioDAO{
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
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

                $achou = parent::verificaCodigoUF($codigoUF);

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
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':codigoUF', $codigoUF);
                $stmt->bindParam(':status', $status);
                $stmt->execute();

                $resultado = parent::listarMunicipios();
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