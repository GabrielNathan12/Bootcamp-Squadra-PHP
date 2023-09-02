<?php
    class ExecutarMunicipio{
        private $controladorMunicipio;

        public function __construct(PDO $conexao){
            $this->controladorMunicipio = new ControladorMunicipio($conexao);
        }

        public function executar($requisicao){

            try{
                switch($requisicao){
                    case 'GET':
                        $listaMunicipios = $this->controladorMunicipio->listarMunicipios();
                        return $listaMunicipios;
                    break;

                    case 'POST':
                        $listaMunicipios = $this->controladorMunicipio->criarMunicipio();
                        return $listaMunicipios;
                    break;

                    case 'PUT':
                        $listaMunicipios = $this->controladorMunicipio->atualizarMunicipio();
                        return $listaMunicipios;
                    break;

                    case 'DELETE':
                       $listaMunicipios = $this->controladorMunicipio->deletarMunicipio();
                        return $listaMunicipios;
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