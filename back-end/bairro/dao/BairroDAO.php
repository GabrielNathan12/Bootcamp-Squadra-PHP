<?php
    include_once('../bairro/dao/servicos/ListarBairro.php');
    include_once('../bairro/dao/servicos/CriarBairro.php');
    include_once('../bairro/dao/servicos/AtualizarBairro.php');
    include_once('../bairro/dao/servicos/DeletarBairro.php');
    
    class BairroDAO{
        private $conexao;

        public function __construct($conexao){
            $this->conexao = $conexao;
        }

        protected function getConexao(){
            return $this->conexao;
        }

        public function listarBairros(){
            $listaBairros = new ListarBairro($this->conexao);
            return $listaBairros->listarBairros();
        }

        public function criarBairro(Bairro $bairro){
            $novoBairro = new CriarBairro($this->conexao);
            return $novoBairro->criarBairro($bairro);             
        }

        public function atualizarBairro($codigoBairro, Bairro $bairro){
            $bairroAtualizado = new AtualizarBairro($this->conexao);
            return $bairroAtualizado->atualizarBairro($codigoBairro, $bairro);    
        }

        public function deletarBairro($codigoBairro){
            $bairroDeletado = new DeletarBairro($this->conexao);
            return $bairroDeletado->deletarBairro($codigoBairro);
        }

        protected function verificarCodigoMunicipio($codigoMunicipio){
            $sql = "SELECT COUNT(*) FROM TB_MUNICIPIO WHERE codigoMunicipio = :codigoMunicipio";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoMunicipio', $codigoMunicipio);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }

        protected function codigoBairroExiste($codigoBairro){
            $sql = "SELECT COUNT(*) FROM TB_BAIRRO WHERE codigoBairro = :codigoBairro";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoBairro', $codigoBairro);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }


    }