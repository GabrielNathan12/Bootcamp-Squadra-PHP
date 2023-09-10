<?php 
    class AtualizarBairro extends BairroDAO{
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }

        public function atualizarBairro($codigoBairro, Bairro $bairro){
            try{
                if(parent::codigoBairroExiste($codigoBairro)){
                    $codigoBairro = $bairro->getCodigoBairro();
                    $codigoMunicipio = $bairro->getCodigoMunicipio();
                    $nome = $bairro->getNomeBairro();
                    $status = $bairro->getStatusBairro();

                    $achouMunicipio = parent::verificarCodigoMunicipio($codigoMunicipio);
                    
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
                    $stmt = parent::getConexao()->prepare($sql);
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
    }