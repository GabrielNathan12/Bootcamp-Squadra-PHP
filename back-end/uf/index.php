<?php
    //header('Content-Type: application/json');
    class ExecutarUf{
        private $controladorUF;

        public function __construct(PDO $conexao){
           $this->controladorUF = new ControladorUf($conexao);

        }

        public function executar($requisicao){
           try{
                switch ($requisicao){
                    case 'GET':
                    
                    $listaUfs = $this->controladorUF->listarUF();
                    
                    return $listaUfs;

                    break;
                    
                    case 'POST':
                        $dados = json_decode(file_get_contents("php://input"), true);
                        $codigoUF = $dados['codigoUF'];
                        $nome = $dados['nome'];
                        $sigla = $dados['sigla'];
                        $status = $dados['status'];
                        $uf = new UF($codigoUF, $nome, $sigla, $status);
                        $this->controladorUF->criarUf($uf);
                    break;

                    case 'PUT':
                        break;
                    case 'DELETE':
                        break;
                    default:
                        http_response_code(500);
                        return json_encode(array("message" => "Requisição não implementada"));
                }
           }
           catch(Exception $error){
                return $error->getMessage();
           } 
            
        }
    }
    


   

