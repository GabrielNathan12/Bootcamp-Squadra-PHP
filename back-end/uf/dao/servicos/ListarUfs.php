<?php

    class ListarUf extends UfDAO{

        public function __construct(PDO $conexao){
            parent::__construct($conexao);
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
        
                $stmt = $this->getConexao()->prepare($sql);
        
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
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }
        
        }
    }