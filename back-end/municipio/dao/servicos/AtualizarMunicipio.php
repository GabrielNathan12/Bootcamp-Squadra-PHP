<?php 
    class AtualizarMunicipio extends MunicipioDAO{
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }

        public function atualizarMunicipio($codigoMunicipio, Municipio $municipio){
            try{
                if(parent::codigoMunicipioExisteNoBD($codigoMunicipio)){
                    $codigoUF = $municipio->getCodigoUf();
                    $nome = $municipio->getNomeMunicipio();
                    $status = $municipio->getStatusMunicipio();
                    
                    $achouUF = parent::verificaCodigoUF($codigoUF);

                    if(!$achouUF){
                        throw new ErrosDaAPI('Esse codigoUF não existe no banco de dados', 400);
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

                    $sql = "UPDATE TB_MUNICIPIO SET codigoUF = :codigoUF, nome = :nome, status = :status WHERE codigoMunicipio = :codigoMunicipio";
                    $stml = parent::getConexao()->prepare($sql);
                    $stml->bindParam(':codigoMunicipio', $codigoMunicipio);
                    $stml->bindParam(':codigoUF', $codigoUF);
                    $stml->bindParam(':nome', $nome);
                    $stml->bindParam(':status', $status);
                    $stml->execute();

                    $resultado = parent::listarMunicipios();
                    return $resultado;

                }
                else{
                    throw new ErrosDaAPI('Código do municipio não existe no Banco de Dados', 400);
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