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
                        $url = parse_url($_SERVER['REQUEST_URI']);
                        $codigoMunicipio = explode('/municipio', $url['path']);
                        
                        $id = end($codigoMunicipio);
                        $id = preg_replace('/[^0-9]/', '', $id);
                        $id = intval($id);

                       // $listaMunicipios = $this->controladorMunicipio->deletarMunicipio($id);
                        //return $listaMunicipios;
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