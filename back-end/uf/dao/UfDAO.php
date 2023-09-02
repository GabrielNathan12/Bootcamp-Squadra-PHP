<?php

class UfDAO {
    private $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

public function listarTodosUFs() {
    if (!empty($_GET)) {
        $sql = "SELECT * FROM TB_UF WHERE 1=1";
        
        if (isset($_GET['codigoUF'])) {
            $codigoUF = intval($_GET['codigoUF']);
            $sql .= " AND codigoUF = :codigoUF";
        }

        if (isset($_GET['nome'])) {
            $nome = $_GET['nome'];
            $sql .= " AND nome = :nome";
        }

        if (isset($_GET['sigla'])) {
            $sigla = $_GET['sigla'];
            $sql .= " AND sigla = :sigla";
        }

        if (isset($_GET['status'])) {
            $status = intval($_GET['status']);
            $sql .= " AND status = :status";
        }

        $stmt = $this->conexao->prepare($sql);

        if (isset($codigoUF)) {
            $stmt->bindParam(':codigoUF', $codigoUF);
        }
        if (isset($nome)) {
            $stmt->bindParam(':nome', $nome);
        }
        if (isset($sigla)) {
            $stmt->bindParam(':sigla', $sigla);
        }
        if (isset($status)) {
            $stmt->bindParam(':status', $status);
        }

        $stmt->execute();

        if($codigoUF){
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$resultado){
                return [];
            }else{
                return $resultado;
            }
        }
        else if($sigla){
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!$resultado){
                return [];
            }
            else {
                return $resultado;
            }
        }
        else if($status){
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
            if(!$resultado){
                return [];
            }
            else{
                return $resultado;
            }
        }
        else{
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        }
    }
    else {
        $sql = "SELECT * FROM TB_UF";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $resultado;
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

    private function nomeExisteNoBD($nome){
        $sql = "SELECT COUNT(*) FROM TB_UF WHERE nome = :nome";
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':nome',$nome);
        
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    private function siglaExisteNoBD($sigla){
        $sql = "SELECT COUNT(*) FROM TB_UF WHERE sigla = :sigla";
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindParam(':sigla', $sigla);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    private function codigoUfExisteNoBD($codigoUF){
        $sql = 'SELECT COUNT(*) FROM TB_UF WHERE codigoUF = :codigoUF';
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':codigoUF', $codigoUF);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public function atualizarUF($codigoUF, UF $uf){
      try{
        if ($this->codigoUfExisteNoBD($codigoUF)) {
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
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':codigoUF', $codigoUF);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':sigla', $siglaUperCase);
            $stmt->bindParam(':status', $status);

            $stmt->execute();

            return $this->listarTodosUFs();  
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

    public function excluirUF($codigoUF){
        if($this->codigoUfExisteNoBD($codigoUF)){
            $sql = 'DELETE FROM TB_UF WHERE codigoUF = :codigoUF';
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':codigoUF', $codigoUF);
            $stmt->execute();

            return $this->listarTodosUFs();

        }else {
            throw new ErrosDaAPI('Código da UF não existe no Banco de Dados', 400);
        }
    }
    
}

