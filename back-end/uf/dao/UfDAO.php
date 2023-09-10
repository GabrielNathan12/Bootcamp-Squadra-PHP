<?php
    include_once('../uf/dao/servicos/ListarUfs.php');
    include_once('../uf/dao/servicos/CriarUfs.php');
    include_once('../uf/dao/servicos/AtualizarUfs.php');
    include_once('../uf/dao/servicos/DeletarUfs.php');

class UfDAO {
    private $conexao;

    public function __construct(PDO $conexao) {
        $this->conexao = $conexao;
    }

    protected function getConexao(){
        return $this->conexao;
    }

    public function listarTodosUfs(){
        $lista = new ListarUf($this->conexao);
        return $lista->listarTodosUFs();
    }

    public function criarUf(UF $uf){
        $novoUf = new CriarUFS($this->conexao);
        return $novoUf->criarUf($uf);
    }

    public function atualizarUF($codigoUF, UF $uf){
        $ufAtualizado = new AtualizarUF($this->conexao);
        return $ufAtualizado->atualizarUF($codigoUF, $uf);
    }

    public function excluirUF($codigoUF){
       $ufDeletado = new DeletarUfs($this->conexao);
       return $ufDeletado->excluirUF($codigoUF);
    }

    protected function nomeExisteNoBD($nome){
        $sql = "SELECT COUNT(*) FROM TB_UF WHERE nome = :nome";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':nome',$nome);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    protected function siglaExisteNoBD($sigla){
        $sql = "SELECT COUNT(*) FROM TB_UF WHERE sigla = :sigla";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':sigla', $sigla);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    protected function codigoUfExisteNoBD($codigoUF){
        $sql = 'SELECT COUNT(*) FROM TB_UF WHERE codigoUF = :codigoUF';
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindParam(':codigoUF', $codigoUF);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    } 
}

