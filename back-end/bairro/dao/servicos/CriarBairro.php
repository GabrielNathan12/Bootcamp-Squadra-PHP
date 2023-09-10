<?php
    class CriarBairro extends BairroDAO{
        
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
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
                $achou = parent::verificarCodigoMunicipio($codigoMunicipio);

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
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->bindParam(':nome', $nome);
                $stmt->bindParam(':codigoMunicipio', $codigoMunicipio);
                $stmt->bindParam(':status', $status);
                $stmt->execute();

                $resultado = parent::listarBairros();
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
    }