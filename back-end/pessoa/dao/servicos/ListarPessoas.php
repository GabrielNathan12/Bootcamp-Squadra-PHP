<?php
    class ListarPessoas extends PessoaDao{
        
        public function __construct(PDO $conexao){
            parent::__construct($conexao);
        }

        public function listarPessoa() {
            if (!empty($_GET)) {
                $sql = "SELECT TP.codigoPessoa, TP.nome, TP.sobrenome, TP.idade, TP.login, TP.senha, TP.status,
                    json_agg(json_build_object(
                        'codigoEndereco', TE.codigoEndereco,
                        'codigoBairro', TB.codigoBairro,
                        'nomeRua', TE.nomeRua,
                        'numero', TE.numero,
                        'complemento', TE.complemento,
                        'cep', TE.cep,
                        'bairro', json_build_object(
                            'codigoBairro', TB.codigoBairro,
                            'codigoMunicipio', TM.codigoMunicipio,
                            'nome', TB.nome,
                            'status', TB.status,
                            'municipio', json_build_object(
                                'codigoMunicipio', TM.codigoMunicipio,
                                'codigoUF', TU.codigoUF,
                                'nome', TM.nome,
                                'status', TM.status,
                                'uf', json_build_object(
                                    'codigoUF', TU.codigoUF,
                                    'sigla', TU.sigla,
                                    'nome', TU.nome,
                                    'status', TU.status
                                )
                            )
                        )
                    )) AS enderecos
                FROM TB_PESSOA TP
                LEFT JOIN TB_ENDERECO TE ON TP.codigoPessoa = TE.codigoPessoa
                LEFT JOIN TB_BAIRRO TB ON TE.codigoBairro = TB.codigoBairro
                LEFT JOIN TB_MUNICIPIO TM ON TB.codigoMunicipio = TM.codigoMunicipio
                LEFT JOIN TB_UF TU ON TM.codigoUF = TU.codigoUF
                WHERE 1=1";
            
                if (isset($_GET['codigoPessoa'])) {
                    $codigoPessoa = intval($_GET['codigoPessoa']);
                    $sql .= " AND TP.codigoPessoa = :codigoPessoa";
                }
                if(isset($_GET['nome'])){
                    $nome = $_GET['nome'];
                    $sql .= " AND TP.nome = :nome";
                }
                if (isset($_GET['idade'])) {
                    $idade = intval($_GET['idade']);
                    $sql .= " AND TP.idade = :idade";
                }
                if (isset($_GET['login'])) {
                    $login = $_GET['login'];
                    $sql .= " AND TP.login = :login";
                }
            
                if (isset($_GET['status'])) {
                    $status = intval($_GET['status']);
                    $sql .= " AND TP.status = :status";
                }
            
                $sql .= " GROUP BY TP.codigoPessoa, TP.nome, TP.sobrenome, TP.idade, TP.login, TP.senha, TP.status";
            
                $stmt = parent::getConexao()->prepare($sql);
            
                if (isset($codigoPessoa)) {
                    $stmt->bindParam(':codigoPessoa', $codigoPessoa);
                }
                if (isset($nome)) {
                    $stmt->bindParam(':nome', $nome);
                }
                if (isset($idade)) {
                    $stmt->bindParam(':idade', $idade);
                }
                if (isset($login)) {
                    $stmt->bindParam(':login', $login);
                }
            
                if (isset($status)) {
                    $stmt->bindParam(':status', $status);
                }
            
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                if (count($resultado) > 0) {
                    foreach ($resultado as &$posicao) {
                        $enderecosJson = json_decode($posicao['enderecos'], true);
                        $posicao['enderecos'] = $enderecosJson;
                    }
                    return $resultado;
                } else {
                    return [];
                }
            }
            else{
                $sql = "SELECT * FROM TB_PESSOA";
                $stmt = parent::getConexao()->prepare($sql);
                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
                foreach ($resultado as &$pessoa) {
                    $pessoa['enderecos'] = [];
                }   
                return $resultado;  
            }
           
        }
    }