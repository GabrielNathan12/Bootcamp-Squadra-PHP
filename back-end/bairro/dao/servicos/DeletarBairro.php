<?php 
    class DeletarBairro extends BairroDAO{
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }
        public function deletarBairro($codigoBairro){
            if(parent::codigoBairroExiste($codigoBairro)){
                $sql = 'DELETE FROM TB_BAIRRO WHERE codigoBairro = :codigoBairro';
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->bindParam(':codigoBairro', $codigoBairro);
                $stmt->execute();
                return $this->listarBairros();
            }
            else {
                throw new ErrosDaAPI('Código da Municipio não existe no Banco de Dados', 400);
            }
        }
    }