<?php 
    class CriarUFS extends UfDAO{
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }

        public function criarUf(UF $uf){
            $nome = $uf->getNomeUf();
            
            if(!is_string($nome)){
                throw new ErrosDaAPI('Nome não é uma string', 400); 
            }
    
            $nomeSemEspaco = trim($nome);
            
            if(parent::nomeExisteNoBD($nomeSemEspaco)){
                throw new ErrosDaAPI('Nome ja inserido no Banco de Dados', 400);
            }
    
            $sigla = $uf->getSiglaUf();
    
            if(!is_string($sigla)){
                throw new ErrosDaAPI('Sigla não é uma string', 400); 
            }
    
            $siglaSemEspaco = trim($sigla);
            $siglaUperCase = strtoupper($siglaSemEspaco);
    
            if(strlen($siglaSemEspaco) != 2 ){
                throw new ErrosDaAPI('Sigla não possui 2 caracteres', 400); 
            }
            else if(parent::siglaExisteNoBD($siglaUperCase)){
                throw new ErrosDaAPI('Sigla ja inserido no Banco de Dados', 400);
            }
    
            $status = $uf->getStatusUf();
    
            if(!is_int($status)){
                throw new ErrosDaAPI('Status não é um número', 400);
            }
            else if($status != 1 and $status != 2){
                throw new ErrosDaAPI('Status com valor inválido', 400);
            }
            #||
            
            $sql = "INSERT INTO TB_UF (sigla, nome, status) VALUES (:sigla, :nome, :status)";
    
            $stmt = $this->getConexao()->prepare($sql);
            $stmt->bindParam(':sigla',$siglaUperCase);
            $stmt->bindParam(':nome',$nomeSemEspaco);
            $stmt->bindParam(':status',$status);
    
            $stmt->execute();
            
            $resultado = parent::listarTodosUFs();
            return $resultado;
    
        }
    }