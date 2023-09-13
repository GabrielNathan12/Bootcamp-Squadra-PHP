<?php 
    class DeletarPessoa extends PessoaDao{
        
        public function __construct(PDO $conexao) {
            parent::__construct($conexao);
        }

        public function deletarPessoa($codigoPessoa){
            if($this->codigoPessoaExiste($codigoPessoa)){
                $sql = 'DELETE FROM TB_PESSOA WHERE codigoPessoa = :codigoPessoa';
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                $stmt->execute();
                return $this->listarPessoa();
            }
            else {
                throw new ErrosDaAPI('Código da pessoa não existe no Banco de Dados', 400);
            }
        }
    }