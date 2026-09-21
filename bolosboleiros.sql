CREATE DATABASE bolosboleiros;
USE bolosboleiros;

CREATE TABLE usuario(
    id_usuario INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    senha VARCHAR(60) NOT NULL,
    nome VARCHAR(255) NOT NULL
);

INSERT INTO usuario(nome, senha)
VALUES ('admin', 'admin'),
       ('teste', '123');

CREATE TABLE cliente(
    id_cliente INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    nome VARCHAR(255) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telefone VARCHAR(15) NOT NULL
);

CREATE TABLE bolo(
    id_bolo INT PRIMARY KEY AUTO_INCREMENT,
    nomebolo VARCHAR(60) NOT NULL,
    descricao VARCHAR(500) NOT NULL,
    
    massa VARCHAR(60) NOT NULL,
    camadas INT,
    recheios VARCHAR(60),
    cobertura VARCHAR(60),
    
    preco DECIMAL(10,2) NOT NULL, -- DECIMAL para evitar erros de arredondamento em centavos
    tamanho FLOAT NOT NULL, -- Peso em gramas ou diâmetro
    quantidade INT NOT NULL
);

INSERT INTO bolo (nomebolo, descricao, massa, camadas, recheios, cobertura, preco, tamanho, quantidade)
VALUES ("Nega maluca", "Um bolo de massa fofa e úmida, ambas massa e cobertura sabor chocolate com um toque de amor", "chocolate", NULL, NULL, "chocolate", 24.99, 250, 1);

CREATE TABLE pedido(
    id_pedido INT PRIMARY KEY AUTO_INCREMENT,
    observacao VARCHAR(1000) NOT NULL,
    data_prazo DATE NOT NULL,
    id_bolo INT NOT NULL,
    id_cliente INT NOT NULL,
    FOREIGN KEY (id_bolo) REFERENCES bolo(id_bolo),
    FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
);

CREATE TABLE historico(
    id_historico INT PRIMARY KEY AUTO_INCREMENT,
    -- Sugestão de situações: 'Pendente', 'Deferido', 'Indeferido', 'Cancelado', 'Vendido/Concluído'
    situacao VARCHAR(60) NOT NULL, 
    datadopedido DATETIME NOT NULL, 
    
    id_pedido INT NOT NULL,
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido) ON DELETE CASCADE -- Se o pedido for excluído, limpa o histórico
);

CREATE TABLE vendas(
    id_vendas INT PRIMARY KEY AUTO_INCREMENT,
    valor_venda DECIMAL(10,2) NOT NULL,
    data_venda DATETIME NOT NULL, -- Alterado de data_prazo para a data real em que a venda foi fechada
    
    id_pedido INT NOT NULL, -- Apontar direto para o Pedido facilita a busca de dados do bolo/cliente
    FOREIGN KEY (id_pedido) REFERENCES pedido(id_pedido)
);
