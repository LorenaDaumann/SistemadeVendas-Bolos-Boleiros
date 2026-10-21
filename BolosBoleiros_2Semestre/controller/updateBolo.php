<?php
require_once __DIR__ . '/verificarSessao.php';
require_once __DIR__ . '/../model/Conexao.class.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/pgAlterar.php');
    exit;
}

$idBolo = filter_input(INPUT_POST, 'id_bolo', FILTER_VALIDATE_INT);

$nomebolo = trim($_POST['nomebolo'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$massa = trim($_POST['massa'] ?? '');
$camadasRaw = trim($_POST['camadas'] ?? '');
$recheios = trim($_POST['recheios'] ?? '');
$cobertura = trim($_POST['cobertura'] ?? '');
$preco = trim($_POST['preco'] ?? '');
$tamanho = trim($_POST['tamanho'] ?? '');
$quantidadeRaw = $_POST['quantidade'] ?? '';
$foto = trim($_POST['foto'] ?? '');

if (!$idBolo || $idBolo < 1) {
    $_SESSION['msg_error'] = 'Bolo não identificado.';
    header('Location: ../view/pgAlterar.php');
    exit;
}

if (
    $nomebolo === '' ||
    $descricao === '' ||
    $massa === '' ||
    !is_numeric($preco) ||
    (float)$preco <= 0 ||
    !is_numeric($tamanho) ||
    (float)$tamanho <= 0 ||
    filter_var($quantidadeRaw, FILTER_VALIDATE_INT) === false ||
    (int)$quantidadeRaw < 0
) {
    $_SESSION['msg_error'] = 'Confira os campos obrigatórios.';
    header('Location: ../view/pgUpdate.php?id_bolo=' . $idBolo);
    exit;
}

$camadas = null;

if ($camadasRaw !== '') {
    $camadas = filter_var($camadasRaw, FILTER_VALIDATE_INT);

    if ($camadas === false || $camadas < 0) {
        $_SESSION['msg_error'] = 'Informe um número válido de camadas.';
        header('Location: ../view/pgUpdate.php?id_bolo=' . $idBolo);
        exit;
    }
}

try {
    $pdo = Conexao::getInstance();

    $sql = "UPDATE bolo SET
                nomebolo = :nomebolo,
                descricao = :descricao,
                massa = :massa,
                camadas = :camadas,
                recheios = :recheios,
                cobertura = :cobertura,
                preco = :preco,
                tamanho = :tamanho,
                quantidade = :quantidade,
                foto = :foto
            WHERE id_bolo = :id_bolo";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nomebolo' => $nomebolo,
        ':descricao' => $descricao,
        ':massa' => $massa,
        ':camadas' => $camadas,
        ':recheios' => $recheios !== '' ? $recheios : null,
        ':cobertura' => $cobertura !== '' ? $cobertura : null,
        ':preco' => $preco,
        ':tamanho' => $tamanho,
        ':quantidade' => (int)$quantidadeRaw,
        ':foto' => $foto !== '' ? $foto : null,
        ':id_bolo' => $idBolo
    ]);

    $_SESSION['msg'] = 'Bolo atualizado com sucesso!';
    unset($_SESSION['msg_error']);

    header('Location: ../view/pgAlterar.php');
    exit;

} catch (Throwable $e) {
    error_log($e->getMessage());

    $_SESSION['msg_error'] = 'Não foi possível atualizar o bolo.';
    header('Location: ../view/pgUpdate.php?id_bolo=' . $idBolo);
    exit;
}