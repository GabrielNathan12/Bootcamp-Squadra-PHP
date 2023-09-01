<?php

class UfDAO {
    private $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

    public function listarTodosUFs() {
        $sql = "SELECT * FROM TB_UF";
        
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function criarUf(UF $uf){
        $nome = $uf->getNomeUf();
        
        if(!is_string($nome)){
           throw new ErrosDaAPI('Nome não é uma string', 400); 
        }

        $nomeSemEspaco = trim($nome);

        if($this->nomeExisteNoBD($nomeSemEspaco)){
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
        else if($this->siglaExisteNoBD($siglaUperCase)){
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

        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':sigla',$siglaUperCase);
        $stmt->bindParam(':nome',$nomeSemEspaco);
        $stmt->bindParam(':status',$status);

        $stmt->execute();

        $resultado = $this->listarTodosUFs();
        
        return $resultado;

    }

    public function nomeExisteNoBD($nome){
        $sql = "SELECT COUNT(*) FROM TB_UF WHERE nome = :nome";
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome',$nome);
        
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public function siglaExisteNoBD($sigla){
        $sql = "SELECT COUNT(*) FROM TB_UF WHERE sigla = :sigla";
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':sigla', $sigla);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;

    }

}

