# Atividade proposta:
1. O sistema web a ser construído em linguagem PHP.
2. O trabalho é em equipe, com limite máximo de 3 integrantes.
3. TEMA: Livre
4. O que o sistema deve conter:
   
  <ul>a. Site + Sistema Web</ul>
  <ul>b. Um login</ul>
  <ul>c. Dois CRUDs</ul>
  <ul>d. Um report (Relatório)</ul>
  <ul>e. O banco de dados deve ter no mínimo três tabelas. Uma tabela para um
  CRUD deve ter pelo menos 6 colunas.</ul>
  <ul>f. O sistema deve ser responsivo.</ul>
  <ul>g. Controle de sessão.</ul>



# Itens avaliados
  <ul>a. Corretude do conteúdo apresentado.</ul>
  <ul>b. Uso do que foi aprendido em aula.</ul>
  <ul>c. Uso da documentação do PHP.</ul>
  <ul>d. Organização do Projeto</ul>
  <ul>e. Organização do Código</ul>
  <ul>f. Plágio.</ul>
  <ul>g. Apresentação e organização da apresentação.</ul>
  <ul>h. Complexidade do sistema.</ul>
  <ul>i. Layout do sistema</ul>
  <ul>j. Responsividade</ul>
  <ul>k. Participação dos integrantes.</ul>



# Para executar o código
- Tenha o Xamp instalado e habilite as funções Apache e MySQL dentro do app

- Vá no link: http://localhost/dashboard/
- Clique em phpMyAdmin
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
          quantidade INT NOT NULL,
          
          foto varchar(500)
      );
      
      INSERT INTO bolo (nomebolo, descricao, massa, camadas, recheios, cobertura, preco, tamanho, quantidade)
      VALUES ("Nega maluca", "Um bolo de massa fofa e úmida, ambas massa e cobertura sabor chocolate com um toque de amor", "chocolate", NULL, NULL, "chocolate", 24.99, 250, 1);
      
      CREATE TABLE pedido(
          id_pedido INT PRIMARY KEY AUTO_INCREMENT,
          observacao VARCHAR(1000) NOT NULL,
          data_prazo DATE NOT NULL,
          id_bolo INT NOT NULL,
      	cpf VARCHAR(14) NOT NULL,
          nome VARCHAR(255) NOT NULL,
          endereco VARCHAR(255) NOT NULL,
          email VARCHAR(255) NOT NULL,
          telefone VARCHAR(15) NOT NULL,
          FOREIGN KEY (id_bolo) REFERENCES bolo(id_bolo)
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




- Cole em seu navegador o link: localhost/nomedapasta - Exemplo: localhost/Trabalho2-2tri-Prog-BibliotecaCRUD (sem o Xamp e o banco dará erro)
- Usuário e senha de login: Admin, Admin

❗Atenção: Confira na Entity e na Conexaxao se o banco referenciado é Aula, caso não, apenas altere o nome no próprio código PHP




# Objetivo do trabalho
  O objetivo deste trabalho é desenvolver um sistema web responsivo para gerenciamento e venda de bolos, consolidando os conhecimentos práticos adquiridos ao longo do semestre em programação web e banco de dados. A aplicação será construída utilizando as tecnologias HTML, CSS, JavaScript, jQuery e PHP, integrada ao servidor XAMPP com banco de dados MySQL via phpMyAdmin. 

Para atender aos requisitos acadêmicos solicitados, o sistema implementará: 

- Controle de sessão restrito para um único usuário administrador através de uma tela de login dedicada. 
- Dois CRUDs completos e funcionais operando sobre um banco de dados estruturado com no mínimo três tabelas relacionais (Usuários/Administradores, Bolos e Pedidos). A tabela principal de Bolos contém pelo menos 6 colunas para detalhamento do produto (como ID, nome, descrição, preço, quantidade e imagem). 
- As operações permitirão ao administrador cadastrar, alterar, deletar e visualizar tanto o catálogo de bolos quanto o registro de pedidos, além de contar com um sistema de busca integrado para otimizar a localização de dados. 
- Geração de relatórios com a extração de dados dos pedidos finalizados em formato PDF, demonstrando a capacidade de integração do PHP com bibliotecas de exportação de documentos. 
- Interface totalmente responsiva e organizada, garantindo uma navegação fluida em dispositivos desktop e mobile, seguindo as melhores práticas de indentação, componentização e documentação de código.



# Atividades do administrador
- Login ;
- Cadastrar um bolo;
- Alterar as informações de um bolo;
- Deletar um bolo;
- Visualizar o catálogo de bolos;
- Registrar/Cadastrar um pedido;
- Deletar um pedido;
- Visualizar um pedido;
- Realizar uma busca tanto dos bolos quanto de um pedido;
- Extrair um relatório em formato PDF dos registros de pedidos.



---
# Alguns detalhes do nosso trabalho:
- Nas abas de Alterar Catálogo, Cadastrar e Registro de Pedidos há mensagens de sucesso ou falha do procedimento após a execução;
- Caso tente realizar uma busca na barra da pesquisa enquanto estiver na aba de históricos, serão buscados apenas os registros de pedidos;
- Caso erre o usuário ou a senha de login, haverá uma mensagem informando o erro;
- Controle de sessão restrito ao link de login


  
---
# Agradecimentos
  Gostaríamos de agradecer a professora Amanda Luiza Ilg, pelos tantos ensinamentos em sala e pela paciência com nossa turma. Obrigada por ser tão carinhosa conosco e trazer um ar mais leve para nossa semana, você é uma professora incrível e tenha certeza que o Diego ficaria orgulhoso do quanto você evoluiu, cada um com seu jeitinho de ensinar e ambos excelentes profissionais. Sucesso na sua caminhada profe, que nossos caminhos se cruzem novamente, seja no cotidiano ou no âmbito profissional! Amamos você! :)






  
