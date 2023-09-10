<?php
    
    class ControladorUf{
        private $ufDAO;

        public function __construct(PDO $conexao){
            $this->ufDAO = new UfDAO($conexao);
        }

        public function criarUf(){
            try {
                $dados = json_decode(file_get_contents("php://input"), true);
                #$codigoUF = $dados['codigoUF'];
                $nome = $dados['nome'];
                $sigla = $dados['sigla'];
                $status = $dados['status'];
                
                $uf = new UF(null, $nome, $sigla, $status);

                $this->verificarCamposNulosParaCriacao($nome, $sigla, $status);
                $data = $this->ufDAO->criarUf($uf);
                
                return $data;
            }
            catch (Exception $e) {
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

        public function verificarCamposNulosParaCriacao($nome, $sigla, $status){
            if(is_null($nome)){
                throw new ErrosDaAPI('Campo nome está definido como nulo', 400);
            }
            else if(is_null($sigla)){
                throw new ErrosDaAPI('Campo sigla está definido como nulo', 400);
            }
            else if(is_null($status)){
                throw new ErrosDaAPI('Campo status está definido como nulo', 400);
            }

        }

        public function atualizarUF($codigoUF ,UF $uf){
            try{
                $this->verificarCamposNulosParaAtualizacao($uf->getCodigoUf(), $uf->getNomeUf(), $uf->getSiglaUf(), $uf->getStatusUf());
                return $this->ufDAO->atualizarUF($codigoUF, $uf);
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI) {
                    http_response_code($e->getCode()); 
                    return json_decode($e->getRespostaJSON());
                }
                else {
                    http_response_code(500); 
                    echo json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }
        }
        public function verificarCamposNulosParaAtualizacao($codigoUF, $nome, $sigla, $status){
            if(is_null($codigoUF)){
                throw new ErrosDaAPI('Campo codigoUF está definido como nulo', 400);
            }
            else if(is_null($nome)){
                throw new ErrosDaAPI('Campo nome está definido como nulo', 400);
            }
            else if(is_null($sigla)){
                throw new ErrosDaAPI('Campo sigla está definido como nulo', 400);
            }
            else if(is_null($status)){
                throw new ErrosDaAPI('Campo status está definido como nulo', 400);
            }
        }
        
        public function listarUF(){
            $data = $this->ufDAO->listarTodosUFs();
            return $data;
        }

        public function deletarUF($codigoUF){
            try{
                $data = $this->ufDAO->excluirUF($codigoUF);
                return $data;
            }
            catch(Exception $e){
                if($e instanceof ErrosDaAPI) {
                    http_response_code($e->getCode()); 
                    return json_decode($e->getRespostaJSON());
                }
                else {
                    http_response_code(500); 
                    echo json_encode(array("mensagem" => 'Erro interno no servidor', "status" => 500, 'error' => $e->getMessage()));
                }
            }      
        }
    }