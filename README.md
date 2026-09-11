# Atividade proposta: 

1. O sistema web a ser construído em linguagem PHP.
2. O trabalho é em equipe, com limite máximo de 3 integrantes.
3. TEMA: Livre
4. O que o sistema deve conter:

  a. Site + Sistema Web
  
  b. Um login
  
  c. Dois CRUDs
  
  d. Um report (Relatório)
  
  e. O banco de dados deve ter no mínimo três tabelas. Uma tabela para um
  CRUD deve ter pelo menos 6 colunas.
  
  f. O sistema deve ser responsivo.
  
  g. Controle de sessão.


# Para executar o código:
- Tenha o Xamp instalado e habilite as funções Apache e MySQL dentro do app

- Vá no link: https://demo.phpmyadmin.net/master-config/public/
- Clique em Novo, escreva o nome do banco como "Aula" e crie ele (Enter)
- Vá na aba SQL
- Cole o seguinte código:
  
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


  VER CONEXAO DO GITHUB COM O VSCODE

/** <--! Para conectar o seu código do Visual Studio Code (VS Code) ao GitHub, você precisa ter o Git instalado em sua máquina e uma conta ativa no GitHub. [1] (https://www.reddit.com/r/webdev/comments/1ciyc6j/using_github_and_vs_code/?tl=pt-br), [2] (https://www.youtube.com/watch?v=3-3gGSrWd00)1. Instalar e Configurar o GitBaixe e instale o Git no seu computador. [1] (https://www.youtube.com/watch?v=igALAiE5ZB8), [2] (https://www.youtube.com/watch?v=3-3gGSrWd00)Abra o terminal (ou Git Bash) e configure seu nome e e-mail com os comandos:git config --global user.name "Seu Nome"git config --global user.email "seu_email@exemplo.com" [1] (https://www.youtube.com/watch?v=qPboeo8fn0U)2. Fazer Login no VS CodeAbra o VS Code.Clique no ícone de engrenagem (Configurações) no canto inferior esquerdo ou no ícone de Contas (um bonequinho).Selecione Entrar e escolha Fazer login com o GitHub.Siga as instruções na página do navegador para autorizar o acesso. [1] (https://translate.google.com/translate?u=https://code.visualstudio.com/docs/sourcecontrol/github&hl=pt&sl=en&tl=pt&client=sge), [2] (https://www.youtube.com/watch?v=uKhytBBvCr0)3. Conectar seu Código a um RepositórioAcesse o GitHub e crie um novo repositório (deixe-o vazio ou com um README, conforme preferir).No VS Code, abra a pasta do seu projeto local indo em Arquivo > Abrir Pasta...Clique no ícone de Controle de Código-Fonte na barra lateral esquerda (o terceiro ícone, que se parece com uma Galeria de Ramificações).Clique no botão Publicar no GitHub (Publish to GitHub) ou Inicializar Repositório.Escolha se deseja publicar como um repositório público ou privado. O VS Code fará o envio (push) inicial automaticamente. [1] (https://www.youtube.com/watch?v=oAEwp0AGmSo), [2] (https://www.youtube.com/watch?v=qPboeo8fn0U), [3] (https://www.reddit.com/r/github/comments/1ncarla/how_to_link_my_vscode_to_github/?tl=pt-br), [4] (https://www.youtube.com/watch?v=3-3gGSrWd00), [5] (https://www.youtube.com/watch?v=Mw2wyWPk6z0&t=337)Este vídeo mostra o passo a passo completo para instalar, configurar e sincronizar o VS Code com o GitHub: 

/*https://www.youtube.com/watch?v=igALAiE5ZB8 --> **/



- Cole em seu navegador o link: localhost/nomedapasta - Exemplo: localhost/Trabalho2-2tri-Prog-BibliotecaCRUD (sem o Xamp e o banco dará erro)

❗Atenção: Confira na Entity e na Conexaxao se o banco referenciado é Aula, caso não, apenas altere o nome no próprio código PHP
