<?php 
    class ControladorPessoa{
        private $pessoaDAO;

        public function __construct($conexao){
            $this->pessoaDAO = new PessoaDao($conexao);
        }
        public function criarPessoa(){
            try{
                $dados = json_decode(file_get_contents('php://input'), true);
                $nome = $dados['nome'];
                $sobrenome = $dados['sobrenome'] ;
                $idade =  $dados['idade'];
                $login =  $dados['login'];
                $senha =  $dados['senha'];
                $status = $dados['status'];
                $enderecos = $dados['enderecos'];

                $this->verificarCamposNulosParaCriacaoPessoa($nome, $sobrenome, $idade, $login, $senha, $status);
                $pessoa = new Pessoa(null, $nome, $sobrenome, $idade, $login, $senha, $status);


                if(isset($dados['enderecos']) && is_array($dados['enderecos'])) {
                    foreach ($dados['enderecos'] as $endereco) {
                        $codigoBairro = $endereco['codigoBairro'];
                        $nomeRua = $endereco['nomeRua'];
                        $numero = $endereco['numero'];
                        $complemento = $endereco['complemento'];
                        $cep = $endereco['cep'];
                        $this->verificarCamposNulosParaCriacaoEndereco($codigoBairro, $nomeRua, $numero, $complemento, $cep);
                    }
                }

                
                $listaPessoas = $this->pessoaDAO->criarPessoa($pessoa, $enderecos);

                return $listaPessoas;
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

        private function verificarCamposNulosParaCriacaoPessoa($nome, $sobrenome, $idade, $login, $senha, $status){
            if(is_null($nome)){
                throw new ErrosDaAPI('Campo nome está definido como null', 400);
            }
            else if(is_null($sobrenome)){
                throw new ErrosDaAPI('Campo sobrenome está definido como null', 400);
            }
            else if(is_null($idade)){
                throw new ErrosDaAPI('Campo idade está definido como null', 400);
            }
            else if(is_null($login)){
                throw new ErrosDaAPI('Campo login está definido como null', 400);
            }
            else if(is_null($senha)){
                throw new ErrosDaAPI('Campo senha está definido como null', 400);
            }
            else if(is_null($status)){
                throw new ErrosDaAPI('Campo status está definido como null', 400);
            }
        }
        private function verificarCamposNulosParaCriacaoEndereco($codigoBairro, $nomeRua, $numero, $complemento, $cep){
            if(is_null($codigoBairro)){
                throw new ErrosDaAPI('Campo codigoBairro está definido como null', 400);
            }
            else if(is_null($nomeRua)){
                throw new ErrosDaAPI('Campo nomeRua está definido como null', 400);
            }
            else if(is_null($numero)){
                throw new ErrosDaAPI('Campo numero está definido como null', 400);
            }
            else if(is_null($complemento)){
                throw new ErrosDaAPI('Campo complemento está definido como null', 400);
            }
            else if(is_null($cep)){
                throw new ErrosDaAPI('Campo cep está definido como null', 400);
            }
        }

        public function atualizarPessoa(){

        }
        public function deletarPessoa(){
            $url = parse_url($_SERVER['REQUEST_URI']);
            $codigoPessoa = explode('/pessoa', $url['path']);
            
            $id = end($codigoPessoa);
            $id = preg_replace('/[^0-9]/', '', $id);
            $id = intval($id);

            $dados = $this->pessoaDAO->deletarPessoa($id);
            return $dados;
        }
        public function listarPessoa(){
            $listaPessoas = $this->pessoaDAO->listarPessoa();
            return $listaPessoas;
        }
    }