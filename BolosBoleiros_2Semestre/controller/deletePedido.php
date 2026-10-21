<?php
require_once __DIR__ . '/verificarSessao.php';
require_once("../model/Entity.class.php");
$entity = new Entity();

$idHistorico = $_POST["id"] ?? null;

if (!empty($idHistorico)) {
    try {
        $entity->delete("pedido", $idHistorico);
        $_SESSION["msg"] = "Registro de pedido deletado com sucesso.";
    } catch (Exception $e) {
        $_SESSION["msg_error"] = "Erro ao deletar registro: " . $e->getMessage();
    }
}

header("Location: ../view/pgHistoricos.php");
exit;
