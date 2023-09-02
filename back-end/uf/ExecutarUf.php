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
                        $listaUfs = $this->controladorUF->criarUf();
                        return $listaUfs;

                    break;

                    case 'PUT':
                        $dados = json_decode(file_get_contents("php://input"), true);
                        $codigoUF = $dados['codigoUF'];
                        $nome = $dados['nome'];
                        $sigla = $dados['sigla'];
                        $status = $dados['status'];

                        $uf = new UF($codigoUF, $nome, $sigla, $status);
                        $listaUfs = $this->controladorUF->atualizarUF($codigoUF, $uf);

                        return $listaUfs;
                    break;

                    case 'DELETE':
                        $url = parse_url($_SERVER['REQUEST_URI']);
                        $codigoUF = explode('/uf', $url['path']);
                        
                        $id = end($codigoUF);
                        $id = preg_replace('/[^0-9]/', '', $id);
                        $id = intval($id);
                       
                        $listaUfs = $this->controladorUF->deletarUF($id);
                        return $listaUfs;

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
    


   

