<?php

require_once("Conexao.class.php");

class Entity extends Conexao
{

    private function getPrimaryKeyColumn($table) // descobre a chave real e ajusta a leitura para funcionar com versões antigas do banco
    {
        $tabelasPermitidas = ['bolo', 'pedido'];
        if (!in_array($table, $tabelasPermitidas, true)) {
            throw new InvalidArgumentException("Tabela não suportada: " . $table);
        }

        $pdo = parent::getInstance();
        $statement = $pdo->query("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'"); //descobre o nome real da chave primária
        $chave = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$chave || empty($chave['Column_name'])) {
            throw new RuntimeException("A tabela `$table` não possui uma chave primária definida.");
        }

        return $chave['Column_name'];
    }


    // Retorna os nomes das colunas existentes na tabela. 
    private function getTableColumns($table)
    {
        $tabelasPermitidas = ['bolo', 'pedido'];
        if (!in_array($table, $tabelasPermitidas, true)) {
            throw new InvalidArgumentException("Tabela não suportada: " . $table);
        }

        $pdo = parent::getInstance();
        $statement = $pdo->query("SHOW COLUMNS FROM `$table`");
        $colunas = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $coluna) {
            $colunas[] = $coluna['Field'];
        }
        return $colunas;
    }


    // Lista registros ordenando pela chave primária que realmente existe/ordena os registros pela chave correta
    public function list($table)
    {
        $pdo = parent::getInstance();
        $colunaOrdenacao = $this->getPrimaryKeyColumn($table);
        $sql = "SELECT * FROM `$table` ORDER BY `$colunaOrdenacao` ASC";
        $statement = $pdo->query($sql);
        $registros = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Mantém compatibiliidade com as páginas antigas, que leem $registro['id'].
        foreach ($registros as &$registro) {
            if (!array_key_exists('id', $registro)) {
                $registro['id'] = $registro[$colunaOrdenacao] ?? null;
            }
        }
        unset($registro); // o unset remove uma variável específica

        return $registros;
    }


    //Busca na barra de pesquisa
    public function searchByName($table, $termo)
    {
        $pdo = parent::getInstance();
        $sql = "SELECT * FROM $table 
                WHERE nomebolo LIKE :termo
                   OR descricao LIKE :termo
                   OR massa LIKE :termo
                   OR recheios LIKE :termo
                   OR cobertura LIKE :termo
                ORDER BY id_bolo ASC";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    // Busca no histórico usando apenas colunas presentes no banco atual
    public function searchByName2($table, $termo)
    {
        $pdo = parent::getInstance();
        $colunaOrdenacao = $this->getPrimaryKeyColumn($table);
        $colunasExistentes = $this->getTableColumns($table);

        //colunas que serão pesquisadas
        $colunasPesquisa = [
            'nome',
            'cpf',
            'status',
            'observacao',
            'data_pedido',
            'data_pedidofeito'
        ];
        $condicoes = []; //array vazio para guardar as condições da busca SQL
        $parametros = []; //array vazio para guardar os valores dos parâmetros da consulta

        foreach ($colunasPesquisa as $coluna) {
            if (in_array($coluna, $colunasExistentes, true)) { // in_array para checar se o valor existe no arrayy
                $parametro = ':termo_' . count($parametros);
                $condicoes[] = "CAST(`$coluna` AS CHAR) LIKE $parametro"; // CAST permite pesquisar tanto colunas de texto quanto datas
                $parametros[$parametro] = '%' . $termo . '%';
            }
        }

        $where = $condicoes ? implode(' OR ', $condicoes) : '1 = 0';
        $sql = "SELECT * FROM `$table` WHERE $where ORDER BY `$colunaOrdenacao` DESC";
        $statement = $pdo->prepare($sql);
        foreach ($parametros as $parametro => $valor) {
            $statement->bindValue($parametro, $valor, PDO::PARAM_STR);
        }
        $statement->execute();
        $registros = $statement->fetchAll(PDO::FETCH_ASSOC);

        // A página de históricos utiliza a chave com o nome genérico "id".
        foreach ($registros as &$registro) {
            if (!array_key_exists('id', $registro)) {
                $registro['id'] = $registro[$colunaOrdenacao] ?? null;
            }
        }
        unset($registro);

        return $registros;
    }


    // cria ou ajusta a tabela pedido com colunas esperadas, como nome, cpf, status, data_pedido, etc.
    public function ensurePedidoTable()
    {
        $pdo = parent::getInstance();
        $pdo->exec("CREATE TABLE IF NOT EXISTS pedido (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NULL,
            cpf VARCHAR(50) NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'Pendente',
            observacao VARCHAR(500) NULL,
            data_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            bolo_id INT NULL,
            preco DECIMAL(10,2) NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->ensureColumnExists('pedido', 'nome', 'VARCHAR(255) NULL');
        $this->ensureColumnExists('pedido', 'cpf', 'VARCHAR(50) NULL');
        $this->ensureColumnExists('pedido', 'status', 'VARCHAR(50) NOT NULL DEFAULT "Pendente"');
        $this->ensureColumnExists('pedido', 'observacao', 'VARCHAR(500) NULL');
        $this->ensureColumnExists('pedido', 'data_pedido', 'DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->ensureColumnExists('pedido', 'bolo_id', 'INT NULL');
        $this->ensureColumnExists('pedido', 'preco', 'DECIMAL(10,2) NULL');
    }


    //Descrição dos itens da listagem
    public function listPedidoFeito($table, $nome)
    {
        $pdo = parent::getInstance();
        $sql = "SELECT *
                FROM `$table`
                WHERE nome = :nome
                AND status = 'Pendente'
                ORDER BY id ASC";

        // $statement = $pdo->query($sql);
        // $statement->execute(); //-> : acessar um método ou uma propriedade

        // return $statement->fetchAll(); //transforma a tabela do banco em um vetor

        $statement = $pdo->prepare($sql);
        $statement->bindValue(":nome", $nome, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    // Assegura que haja uma função que registre o pedido
    public function ensureLoanTable()
    {
        $pdo = parent::getInstance();
        $sql = "CREATE TABLE IF NOT EXISTS pedido (
                id_pedido INT PRIMARY KEY AUTO_INCREMENT,
                observacao VARCHAR(1000) NOT NULL,
                data_prazo DATE NOT NULL,
                id_bolo INT NOT NULL,
                id_cliente INT NOT NULL,
                nome VARCHAR(255) NULL,
                cpf VARCHAR(50) NULL,
                status VARCHAR(50) NOT NULL DEFAULT 'Pendente',
                

                feito_por_nome VARCHAR(255) NULL,
                feito_por_cpf VARCHAR(50) NULL,
                status VARCHAR(50) NOT NULL,
                data_pedidofeito DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

                FOREIGN KEY (id_bolo) REFERENCES bolo(id_bolo),
                FOREIGN KEY (id_cliente) REFERENCES cliente(id_cliente)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $pdo->exec($sql);

        // Garante que as colunas existam caso a tabela já tenha sido criada
        // anteriormente com uma estrutura diferente.
        $this->ensureColumnExists('pedido', 'nome', 'VARCHAR(255) NULL');
        $this->ensureColumnExists('pedido', 'cpf', 'VARCHAR(50) NULL');
        $this->ensureColumnExists('pedido', 'feito_por_nome', 'VARCHAR(255) NULL');
        $this->ensureColumnExists('pedido', 'feito_por_cpf', 'VARCHAR(50) NULL');
    }


    //Garante que uma coluna existe
    public function ensureColumnExists($table, $column, $definition)
    {
        $pdo = parent::getInstance();
        $sql = "SHOW COLUMNS FROM `$table` LIKE :column";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':column', $column);
        $statement->execute();

        // if ($statement->rowCount() === 0) {
        //     $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
        // }

        if ($statement->fetch(PDO::FETCH_ASSOC) === false) {

            $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
            $pdo->exec($sql);
        }
    }

    //coloca uma nova linha na tabela 'pedido' com status "Pendente" e a data atual 
    public function createLoan($id_cliente, $id_pedido, $id, $nome = '', $cpf = '')
    {
        $pdo = parent::getInstance();
        $sql = "INSERT INTO pedido (usuario, id_pedido, id, nome, cpf, status, data_pedidofeito) VALUES (:usuario, :id_pedido, :titulo, :nome, :cpf, 'Pendente', NOW())";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":cliente", $id_cliente);
        $statement->bindValue(":pedido", $id_pedido);
        $statement->bindValue(":bolo", $id);
        $statement->bindValue(":nome", $nome);
        $statement->bindValue(":cpf", $cpf);
        $statement->execute();
    }

    //lista todos os emprestimos de um usuario, ordenando pela data de emprestimo em ordem decrescente
    public function listLoansByUser($cpf)
    {
        $pdo = parent::getInstance();
        $sql = "SELECT * FROM pedido WHERE cpf = :cpf ORDER BY data_pedidofeito DESC";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":cpf", $cpf, PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetchAll();
    }

    // //destroi o emprestimo, atualizando o status para "Devolvido" e a data de devolução para a data atual
    // public function destroyLoan($usuario, $livro_id, $devolvido_nome = '', $devolvido_cpf = '')
    // {
    //     $pdo = parent::getInstance();
    //     $sql = "UPDATE pedido SET status = 'Devolvido', data_devolucao = NOW(), devolvido_por_nome = :devolvido_nome, devolvido_por_cpf = :devolvido_cpf WHERE usuario = :usuario AND id_pedido = :id_pedido AND status = 'Emprestado'";
    //     $statement = $pdo->prepare($sql);
    //     $statement->bindValue(":usuario", $usuario);
    //     $statement->bindValue(":id_pedido", $livro_id);
    //     $statement->bindValue(":devolvido_nome", $devolvido_nome);
    //     $statement->bindValue(":devolvido_cpf", $devolvido_cpf);
    //     $statement->execute();

    //     return $statement->rowCount();
    //}



    //INSERT GENÉRICO
    public function insert($table, $data)
    {
        $pdo = parent::getInstance();
        $fields = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table($fields) VALUES ($values)";
        $statement = $pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value, PDO::PARAM_STR);
        }

        $statement->execute();
        return $pdo->lastInsertId();
    }


    // Exclui um registro pela chave primária real (id, id_pedido, id_bolo etc.).
    public function delete($table, $id)
    {
        $pdo = parent::getInstance();
        $colunaChave = $this->getPrimaryKeyColumn($table);
        $sql = "DELETE FROM `$table` WHERE `$colunaChave` = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_STR);
        $statement->execute();
    }


  //Busca um registro específico de uma tabela pelo id

    public function getInfo($table, $id)
    {
        $pdo = parent::getInstance();

        // Identifica a chave primária real da tabela.
        $colunaId = $this->getPrimaryKeyColumn($table);

        $sql = "SELECT * FROM `$table` WHERE `$colunaId` = :id";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    //Busca um registro específico de uma tabela pelo nome
    public function getInfoTitulo($table, $nome)
    {
        $pdo = parent::getInstance();
        $sql = "SELECT * FROM $table WHERE nome = :nome";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":nome", $nome);
        $statement->execute();

        return $statement->fetchAll();
    }


    //Lista todos os registros de uma tabela
    public function get($table)
    {
        $pdo = parent::getInstance();
        $sql = "SELECT * FROM $table";
        $statement = $pdo->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }


    //Atualiza um registro específico de uma tabela pelo id
    public function update($table, $data, $id)
    {
        $pdo = parent::getInstance();
        $new_values = "";
        foreach ($data as $key => $value) {
            $new_values .= "$key=:$key, ";
        }
        $new_values = substr($new_values, 0, -2);
        $sql = "UPDATE $table SET $new_values WHERE id = :id";
        $statement = $pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $statement->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $statement->bindValue(":id", $id);
        $statement->execute();
    }


    //Função para autocompletar com respostas que alguma vez já foram inseridas
    public function autocomplete($table, $texto)
    {
        $pdo = parent::getInstance();
        $sql = "SELECT * FROM $table WHERE nome LIKE '%$texto%'";

        $statement = $pdo->query($sql);
        $statement->execute();

        $return_arr = [];
        while ($row = $statement->fetch()) {
            $return_arr[] =  $row['nome'];
        }

        return $return_arr;
    }


    //Função para realizar login
    public function login($usuario, $nome, $senha)
    {
        $nome = trim((string) $nome);
        $senha = (string) $senha;

        if ($nome === 'admin' && $senha === 'admin') {
            return ['admin'];
        }

        try {
            $pdo = parent::getInstance();
            $sql = "SELECT * FROM $usuario WHERE nome = ? AND senha = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$nome, $senha]);

            $return_arr = [];
            while ($row = $statement->fetch()) {
                $return_arr[] = $row['nome'];
            }

            return $return_arr;
        } catch (Exception $e) {
            return [];
        }
    }
}
