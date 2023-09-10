create table TB_UF(
	codigoUF serial not null primary key, 
	sigla varchar(2) not null unique,
	nome varchar(50) not null unique,
	status INT not null
);

CREATE TABLE TB_MUNICIPIO (
    codigoMunicipio SERIAL NOT NULL PRIMARY KEY,
    codigoUF INT REFERENCES TB_UF(codigoUF) ON DELETE CASCADE NOT NULL,
    nome VARCHAR(50),
    status INT NOT NULL,
    CONSTRAINT nomeMunicipio_codigoUF UNIQUE (nome, codigoUF)
);

CREATE TABLE TB_BAIRRO (
    codigoBairro SERIAL NOT NULL PRIMARY KEY,
    codigoMunicipio INT REFERENCES TB_MUNICIPIO(codigoMunicipio) ON DELETE CASCADE NOT NULL,
    nome VARCHAR(50) NOT NULL,
    status INT NOT null,
    constraint nomeBairro_codigoMunicipio unique (nome, codigoMunicipio)
);

CREATE TABLE TB_PESSOA (
    codigoPessoa SERIAL NOT NULL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    sobrenome VARCHAR(150) NOT NULL,
    idade INT NOT NULL,
    login VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(100) NOT NULL,
    status INT NOT NULL
);

CREATE TABLE TB_ENDERECO (
    codigoEndereco SERIAL NOT NULL PRIMARY KEY,
    codigoPessoa INT REFERENCES TB_PESSOA(codigoPessoa) ON DELETE CASCADE NOT NULL,
    codigoBairro INT REFERENCES TB_BAIRRO(codigoBairro) ON DELETE CASCADE NOT NULL,
    nomeRua VARCHAR(150) NOT NULL,
    numero VARCHAR(150) NOT NULL,
    complemento VARCHAR(150) NOT NULL,
    cep VARCHAR(9) NOT NULL
);
    