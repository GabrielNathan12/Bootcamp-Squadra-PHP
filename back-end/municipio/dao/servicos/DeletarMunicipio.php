<?php 
    class DeletarMunicipio extends MunicipioDAO{
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }
        public function deletarMunicipio($codigoMunicipio){
            if(parent::codigoMunicipioExisteNoBD($codigoMunicipio)){
                $sql = 'DELETE FROM TB_MUNICIPIO WHERE codigoMunicipio = :codigoMunicipio';
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->bindParam(':codigoMunicipio', $codigoMunicipio);
                $stmt->execute();
                return $this->listarMunicipios();
            }
            else {
                throw new ErrosDaAPI('Código da Municipio não existe no Banco de Dados', 400);
            }
        }
    }