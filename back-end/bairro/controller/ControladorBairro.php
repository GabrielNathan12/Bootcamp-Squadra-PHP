<?php
    class ControladorBairro{
        private $bairroDAO;

        public function __construct($conexao){
            $this->bairroDAO = new BairroDAO($conexao);
        }
        public function listarBairros(){
            $dados = $this->bairroDAO->listarBairros();
            return $dados;
        }
        public function criarBairro(){
            try{
                $dados = json_decode(file_get_contents('php://input'), true);
                $codigoMunicipio = $dados['codigoMunicipio'];
                $nome = $dados['nome'];
                $status = $dados['status'];

                $this->verificarCampusNulosParaCriacao($codigoMunicipio, $nome, $status );
                $bairro = new Bairro(null, $codigoMunicipio, $nome, $status);
                $dados = $this->bairroDAO->criarBairro($bairro);
                return $dados;
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI) {
                    http_response_code($e->getCode()); 
                    return json_decode($e->getRespostaJSON());
                }
                else {
                    http_response_code(500); 
                    return json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }
        }

        private function verificarCampusNulosParaCriacao($codigoMunicipio, $nome, $status ){
            if(is_null($codigoMunicipio)){
                throw new ErrosDaAPI('Campo codigoMunicipio está definido como null', 400);
               }
                else if(is_null($nome)){
                    throw new ErrosDaAPI('Campo nome está definido como null', 400);
                }
                else if(is_null($status)){
                    throw new ErrosDaAPI('Campo status está definido como null', 400);
                }
        }

        private function verificarCampusNulasParaAtualizacao($codigoBairro, $codigoMunicipio, $nome,  $status){
            if(is_null($codigoBairro)){
                throw new ErrosDaAPI('Campo codigoBairro está definido como null', 400);
            }
            else if(is_null($codigoMunicipio)){
                throw new ErrosDaAPI('Campo codigoMunicipio está definido como null', 400);
            }
            else if(is_null($nome)){
                throw new ErrosDaAPI('Campo nome está definido como null', 400);
            }
            else if(is_null($status)){
                throw new ErrosDaAPI('Campo status está definido como null', 400);
            }
        }
        public function atualizarBairro(){
            try{
                $dados = json_decode(file_get_contents('php://input'), true);
                $codigoBairro = $dados['codigoBairro'];
                $codigoMunicipio = $dados['codigoMunicipio'];
                $nome = $dados['nome'];
                $status = $dados['status'];

                $this->verificarCampusNulasParaAtualizacao($codigoBairro, $codigoMunicipio, $nome,  $status);
                $bairro = new Bairro($codigoBairro, $codigoMunicipio, $nome, $status);
                $dados = $this->bairroDAO->atualizarBairro($codigoBairro, $bairro);

                return $dados;
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI) {
                    http_response_code($e->getCode()); 
                    return json_decode($e->getRespostaJSON());
                }
                else {
                    http_response_code(500); 
                    return json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }
        }
        public function deletarBairro(){
            try{
                $url = parse_url($_SERVER['REQUEST_URI']);
                $codigoBairro = explode('/bairro', $url['path']);
            
                $id = end($codigoBairro);
                $id = preg_replace('/[^0-9]/', '', $id);
                $id = intval($id);

                $dados = $this->bairroDAO->deletarBairro($id);
                return $dados;
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI) {
                    http_response_code($e->getCode()); 
                    return json_decode($e->getRespostaJSON());
                }
                else {
                    http_response_code(500); 
                    return json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }       
        }
    }