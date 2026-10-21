<?php
require_once __DIR__ . '/verificarSessao.php';
require_once __DIR__ . '/../model/Conexao.class.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../view/pgRegistrarPedido.php");
    exit;
}

$pdo = null;

try {
    // Dados do formulário
    $nome       = trim($_POST["nome"] ?? "");
    $cpf        = trim($_POST["cpf"] ?? "");
    $endereco   = trim($_POST["endereco"] ?? "");
    $email      = trim($_POST["email"] ?? "");
    $telefone   = trim($_POST["telefone"] ?? "");
    $observacao = trim($_POST["obs"] ?? "");
    $dataPrazo  = $_POST["data"] ?? "";
    $idBolo     = filter_input(
        INPUT_POST,
        "id_bolo",
        FILTER_VALIDATE_INT
    );

    // Validação dos campos
    if (
        $nome === "" ||
        $cpf === "" ||
        $endereco === "" ||
        $email === "" ||
        $telefone === "" ||
        $observacao === "" ||
        $dataPrazo === "" ||
        !$idBolo
    ) {
        throw new Exception("Preencha todos os campos do pedido.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Informe um e-mail válido.");
    }

    $data = DateTime::createFromFormat("!Y-m-d", $dataPrazo);

    if (!$data || $data->format("Y-m-d") !== $dataPrazo) {
        throw new Exception("Informe uma data de entrega válida.");
    }

    $pdo = Conexao::getInstance();

    // Inicia a transação
    $pdo->beginTransaction();

    // Busca o bolo e bloqueia a linha durante a operação
    $consulta = $pdo->prepare(
        "SELECT id_bolo, preco, quantidade
         FROM bolo
         WHERE id_bolo = :id_bolo
         FOR UPDATE"
    );

    $consulta->execute([":id_bolo" => $idBolo]);
    $bolo = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$bolo) {
        throw new Exception("O bolo selecionado não existe.");
    }

    if ((int)$bolo["quantidade"] <= 0) {
        throw new Exception("Esse bolo está esgotado.");
    }

    // Registra o pedido
    $sql = "INSERT INTO pedido (
                observacao, data_prazo, id_bolo, bolo_id,
                cpf, nome, endereco, email, telefone,
                status, data_pedido, preco
            ) VALUES (
                :observacao, :data_prazo, :id_bolo, :bolo_id,
                :cpf, :nome, :endereco, :email, :telefone,
                :status, NOW(), :preco
            )";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":observacao" => $observacao,
        ":data_prazo" => $dataPrazo,
        ":id_bolo"    => $idBolo,
        ":bolo_id"    => $idBolo,
        ":cpf"        => $cpf,
        ":nome"       => $nome,
        ":endereco"   => $endereco,
        ":email"      => $email,
        ":telefone"   => $telefone,
        ":status"     => "Pendente",
        ":preco"      => $bolo["preco"]
    ]);

    // Diminui uma unidade do estoque
    $atualizar = $pdo->prepare(
        "UPDATE bolo
         SET quantidade = quantidade - 1
         WHERE id_bolo = :id_bolo
           AND quantidade > 0"
    );

    $atualizar->execute([":id_bolo" => $idBolo]);

    if ($atualizar->rowCount() !== 1) {
        throw new Exception("Não foi possível atualizar o estoque.");
    }

    // Confirma o pedido e o estoque juntos
    $pdo->commit();

    $_SESSION["msg"] = "Pedido registrado com sucesso!";
    unset($_SESSION["msg_error"]);

} catch (Throwable $e) {
    // Desfaz tudo caso o pedido ou o estoque falhe
    if ($pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log($e->getMessage());

    $_SESSION["msg_error"] =
        "Não foi possível registrar o pedido. Verifique os dados e o estoque.";
}

header("Location: ../view/pgRegistrarPedido.php");
exit;