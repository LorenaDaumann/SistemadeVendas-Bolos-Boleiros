
<?php
require_once __DIR__ . '/verificarSessao.php';
require_once __DIR__ . '/../model/Entity.class.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/pgAlterar.php');
    exit;
}

$idBolo = filter_input(
    INPUT_POST,
    'id_bolo',
    FILTER_VALIDATE_INT
);

if (!$idBolo || $idBolo < 1) {
    $_SESSION['msg_error'] = 'Identificador do bolo inválido.';
    header('Location: ../view/pgAlterar.php');
    exit;
}

try {
    $entity = new Entity();
    $entity->delete('bolo', $idBolo);

    $_SESSION['msg'] = 'Bolo excluído com sucesso!';
    unset($_SESSION['msg_error']);

} catch (Throwable $e) {
    error_log($e->getMessage());
    $_SESSION['msg_error'] =
        'Não foi possível excluir o bolo.';

}

header('Location: ../view/pgAlterar.php');
exit;
