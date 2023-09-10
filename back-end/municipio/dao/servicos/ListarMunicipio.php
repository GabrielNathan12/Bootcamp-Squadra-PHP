<?php 
    class ListarMunicipios extends MunicipioDAO{
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }

        public function listarMunicipios(){

            if(!empty($_GET)){
                $sql = "SELECT * FROM TB_MUNICIPIO WHERE 1=1";

                if(isset($_GET['codigoMunicipio'])){
                    $codigoMunicipio = intval($_GET['codigoMunicipio']);
                    $sql .= " AND codigoMunicipio = :codigoMunicipio";
                }

                if(isset($_GET['codigoUF'])){
                    $codigoUF = intval($_GET['codigoUF']);
                    $sql .= " AND codigoUF = :codigoUF";
                }

                if(isset($_GET['nome'])){
                    $nome = $_GET['nome'];
                    $sql .= " AND nome = :nome";
                }

                if(isset($_GET['status'])){
                    $status = intval($_GET['status']);
                    $sql .= " AND status = :status";
                }
                
                $stmt = parent::getConexao()->prepare($sql);

                if(isset($codigoMunicipio)){
                    $stmt->bindParam(':codigoMunicipio',$codigoMunicipio);
                }
                if(isset($codigoUF)){
                    $stmt->bindParam(':codigoUF', $codigoUF);
                }
                if(isset($nome)){
                    $stmt->bindParam(':nome', $nome);
                }
                if(isset($status)){
                    $stmt->bindParam(':status', $status);
                }

                $stmt->execute();
                
                if($codigoMunicipio){
                    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else {
                        return $resultado;
                    }
                }
                else if($codigoUF){
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else{
                        return $resultado;
                    }
                }
                else if($nome){
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else {
                        return $resultado;
                    }
                }
                else if($status){
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if(!$resultado){
                        return [];
                    }else {
                        return $resultado;
                    }
                }
            }
            else {
                $sql = "SELECT * FROM TB_MUNICIPIO";
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }
        }
    }