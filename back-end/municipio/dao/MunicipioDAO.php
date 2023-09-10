<?php
    include_once('../municipio/dao/servicos/ListarMunicipio.php');
    include_once('../municipio/dao/servicos/CriarMunicipio.php');
    include_once('../municipio/dao/servicos/AtualizarMunicipio.php');
    include_once('../municipio/dao/servicos/DeletarMunicipio.php');
    
    class MunicipioDAO{
        private $conexao;

        public function __construct(PDO $conexao){
            $this->conexao = $conexao;
        }

        protected function getConexao(){
            return $this->conexao;
        }

        public function listarMunicipios(){
            $lista = new ListarMunicipios($this->conexao);
            return $lista->listarMunicipios();
        }

        public function criarMunicipio(Municipio $municipio){
            $novoMunicipio = new CriarMunicipio($this->conexao);
            return $novoMunicipio->criarMunicipio($municipio);            
        }

        public function atualizarMunicipio($codigoMunicipio, Municipio $municipio){
            $municipioAtualizado = new AtualizarMunicipio($this->conexao);
            return $municipioAtualizado->atualizarMunicipio($codigoMunicipio, $municipio);
        }

        public function deletarMunicipio($codigoMunicipio){
            $municipioDeletado = new DeletarMunicipio($this->conexao);
            return $municipioDeletado->deletarMunicipio($codigoMunicipio);
        }

        protected  function verificaCodigoUF($codigoUF){
            $sql = "SELECT COUNT(*) FROM TB_UF WHERE codigoUF = :codigoUF";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoUF', $codigoUF);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }

        protected function codigoMunicipioExisteNoBD($codigoMunicipio){
            $sql = "SELECT COUNT(*) FROM TB_MUNICIPIO WHERE codigoMunicipio = :codigoMunicipio";
            $stmt = $this->conexao->prepare($sql);

            $stmt->bindParam(':codigoMunicipio', $codigoMunicipio);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }
    }