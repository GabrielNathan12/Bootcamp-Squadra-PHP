<?php 
    class ExecutarPessoa{
        private $controladorPessoa;

        public function __construct(PDO $conexao){
            $this->controladorPessoa = new ControladorPessoa($conexao);
        }

        public function executar($requisicao){
            try{
                switch ($requisicao){
                    case 'GET':  
                        $listaUfs = $this->controladorPessoa->listarPessoa();
                        return $listaUfs;

                    break;
                    
                    case 'POST':
                        $listaUfs = $this->controladorPessoa->criarPessoa();
                        return $listaUfs;

                    break;

                    case 'PUT':
                        $listaUfs = $this->controladorPessoa->atualizarPessoa();

                        return $listaUfs;
                    break;

                    case 'DELETE':
                        $url = parse_url($_SERVER['REQUEST_URI']);
                        $codigoUF = explode('/uf', $url['path']);
                        
                        $id = end($codigoUF);
                        $id = preg_replace('/[^0-9]/', '', $id);
                        $id = intval($id);
                       
                        $listaUfs = $this->controladorPessoa->deletarPessoa($id);
                        return $listaUfs;

                        break;
                    default:
                        http_response_code(500);
                        return json_encode(array("mensagem" => "Requisição não implementada"));
                }
            }
            catch(Exception $e){
                return $e->getMessage();
           } 
            
        }
    }