<?php
    class ExcecutarBairro{
        private $controladorBairro;

        public function __construct($conexao){
            $this->controladorBairro = new ControladorBairro($conexao);
        }

        public function executar($requisicao){
            try{
                switch($requisicao){
                    case 'GET':
                        $listaBairros = $this->controladorBairro->listarBairros();
                        return $listaBairros;
                    break;
                    case 'POST':
                        $listaBairros = $this->controladorBairro->criarBairro();
                        return $listaBairros;
                    break;
                    case 'PUT':
                        $listaBairros = $this->controladorBairro->atualizarBairro();
                        return $listaBairros;
                    break;
                    case 'DELETE':
                        $listaBairros = $this->controladorBairro->deletarBairro();
                        return $listaBairros;
                    break;
                    default:
                        http_response_code(500);
                        return json_encode(array('mensagem' => 'Requisição não implementada'));
                }
            }
            catch(Exception $e){
                return $e->getMessage();
            }
        }
    }