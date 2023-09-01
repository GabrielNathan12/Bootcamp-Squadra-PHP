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
}

