
<?php
require_once __DIR__ . '/verificarSessao.php';
require_once("../model/Entity.class.php");

$entity = new Entity();

// Aceita apenas requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/pgHistoricos.php');
    exit;
}

// Recebe os dados do formulário
$idPedido = filter_input(INPUT_POST, 'idPedido', FILTER_VALIDATE_INT);
$novoStatus = $_POST['status'] ?? '';

// Valida os dados
if (!$idPedido || trim($novoStatus) === '') {
    $_SESSION['mensagem_erro'] = "Dados inválidos para atualizar o pedido.";
    header('Location: ../view/pgHistoricos.php');
    exit;
}

try {
    // Prepara os dados para atualização
    $dadosUpdate = [
        'status' => $novoStatus
    ];

    // Atualiza o pedido pelo ID
    $sucesso = $entity->update("pedido", $dadosUpdate, $idPedido);

    if ($sucesso) {
        $_SESSION['mensagem_sucesso'] =
            "Pedido #{$idPedido} alterado para '{$novoStatus}' com sucesso!";
    } else {
        $_SESSION['mensagem_erro'] =
            "Não foi possível atualizar o status do pedido #{$idPedido}.";
    }

} catch (Exception $e) {
    $_SESSION['mensagem_erro'] =
        "Erro no banco de dados: " . $e->getMessage();
}

// Redireciona para os históricos
header('Location: ../view/pgHistoricos.php');
exit;