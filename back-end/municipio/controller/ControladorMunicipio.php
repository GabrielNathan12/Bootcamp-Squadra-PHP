<?php
    class ControladorMunicipio{
        private $municipioDAO;

        public function __construct(PDO $conexao){
            $this->municipioDAO = new MunicipioDAO($conexao);
        }

        public function listarMunicipios(){
            $dados = $this->municipioDAO->listarMunicipios();
            return $dados;
        }

        public function criarMunicipio(){
            try{
                $dados = json_decode(file_get_contents('php://input'), true);
                $codigoUF = $dados['codigoUF'];
                $nome = $dados['nome'];
                $status = $dados['status'];

                $municipio = new Municipio(null, $codigoUF, $nome, $status);

                $this->verificarCamposNulosParaCriacao($codigoUF, $nome, $status);

                $dados = $this->municipioDAO->criarMunicipio($municipio);
                
                return $dados;
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI){
                    http_response_code($e->getCode());
                    echo json_encode(array("mensagem" => $e->getMessage(), "status" => $e->getCode()));
                }
                else {
                    http_response_code(500); 
                    echo json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }
        }
        private function verificarCamposNulosParaCriacao($codigoUF,$nome, $status){
           if(is_null($codigoUF)){
            throw new ErrosDaAPI('Campo codigoUF está definido como null', 400);
           }
            else if(is_null($nome)){
                throw new ErrosDaAPI('Campo nome está definido como null', 400);
            }
            else if(is_null($status)){
                throw new ErrosDaAPI('Campo status está definido como null', 400);
            }
        }
        public function atualizarMunicipio(){
            try{
                $dados = json_decode(file_get_contents('php://input'), true);
                $codigoMunicipio = $dados['codigoMunicipio'];
                $codigoUF = $dados['codigoUF'];
                $nome = $dados['nome'];
                $status = $dados['status'];

                $this->verificarCampusNulasParaAtualizacao($codigoMunicipio, $codigoUF, $nome,  $status);
                $municipio = new Municipio($codigoMunicipio, $codigoUF, $nome, $status);
                $dados = $this->municipioDAO->atualizarMunicipio($codigoMunicipio, $municipio);

                return $dados;
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI){
                    http_response_code($e->getCode());
                    echo json_encode(array("mensagem" => $e->getMessage(), "status" => $e->getCode()));
                }
                else {
                    http_response_code(500); 
                    echo json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }
        }
        private function verificarCampusNulasParaAtualizacao($codigoMunicipio, $codigoUF, $nome, $status){
            if(is_null($codigoMunicipio)){
                throw new ErrosDaAPI('Campo codigoMunicipio está definido como null', 400);
            }
            else if(is_null($codigoUF)){
                throw new ErrosDaAPI('Campo codigoUF está definido como null', 400);
            }
            else if(is_null($nome)){
                throw new ErrosDaAPI('Campo nome está definido como null', 400);
            }
            else if(is_null($status)){
                throw new ErrosDaAPI('Campo status está definido como null', 400);
            }
        }
        public function deletarMunicipio(){
            $url = parse_url($_SERVER['REQUEST_URI']);
            $codigoMunicipio = explode('/municipio', $url['path']);
            
            $id = end($codigoMunicipio);
            $id = preg_replace('/[^0-9]/', '', $id);
            $id = intval($id);

            $dados = $this->municipioDAO->deletarMunicipio($id);
            return $dados;
        }
    }