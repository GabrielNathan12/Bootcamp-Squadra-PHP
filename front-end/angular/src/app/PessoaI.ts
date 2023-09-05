export interface PessoaI{
  codigoPessoa: number,
  nome: string,
  sobrenome: string,
  idade: number
  login: string
  senha: string,
  status: number,
  enderecos: EnderecoI[]
}

export interface EnderecoI{
  codigoEndereco?: number
  codigoBairro: number;
  nomeRua: string;
  numero: string;
  complemento:string;
  cep :string;
}
