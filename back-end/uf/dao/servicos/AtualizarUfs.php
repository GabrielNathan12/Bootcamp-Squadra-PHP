<?php 
    class AtualizarUF extends UfDAO{

        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }
        public function atualizarUF($codigoUF, UF $uf){
            try{
                if (parent::codigoUfExisteNoBD($codigoUF)) {
                    $nome = $uf->getNomeUf();
                    $sigla = $uf->getSiglaUf();
                    $status = $uf->getStatusUf();
                    
                    if(!is_string($sigla)){
                        throw new ErrosDaAPI('Sigla não é uma string', 400); 
                    }
                    $siglaSemEspaco = trim($sigla);
                    $siglaUperCase = strtoupper($siglaSemEspaco);
            
                    if(strlen($siglaUperCase) != 2 ){
                        throw new ErrosDaAPI('Sigla não possui 2 caracteres', 400); 
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
    
                    $sql = "UPDATE TB_UF SET nome = :nome, sigla = :sigla, status = :status WHERE codigoUF = :codigoUF";
                    $stmt = $this->getConexao()->prepare($sql);
                    $stmt->bindParam(':codigoUF', $codigoUF);
                    $stmt->bindParam(':nome', $nome);
                    $stmt->bindParam(':sigla', $siglaUperCase);
                    $stmt->bindParam(':status', $status);
    
                    $stmt->execute();
    
                    return parent::listarTodosUFs();  
                }
                else {
                    throw new ErrosDaAPI('Código da UF não existe no Banco de Dados', 400);
                }
            }
            catch(PDOException $e){
                if($e->getCode() == '23505'){
                    throw new ErrosDaAPI('Dados duplicados: A sigla ou nome já estão inclusos no banco de dado', 400);
                }
                else {
                    throw new ErrosDaAPI('Erro interno no servidor: '. $e->getMessage());
                }
            }    
        }
    }