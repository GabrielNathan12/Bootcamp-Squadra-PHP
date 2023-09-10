<?php 
    class DeletarUfs extends UfDAO{
        
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }

        public function excluirUF($codigoUF){
            if(parent::codigoUfExisteNoBD($codigoUF)){
                $sql = 'DELETE FROM TB_UF WHERE codigoUF = :codigoUF';
                $stmt = $this->getConexao()->prepare($sql);
                $stmt->bindParam(':codigoUF', $codigoUF);
                $stmt->execute();
    
                return parent::listarTodosUFs();
            }
            else {
                throw new ErrosDaAPI('Código da UF não existe no Banco de Dados', 400);
            }
        }
    }