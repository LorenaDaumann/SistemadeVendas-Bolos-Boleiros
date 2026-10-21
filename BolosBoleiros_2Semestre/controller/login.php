<?php
    session_start();

    $nome = $_POST["nome"];
    $senha = $_POST["senha"];

    require_once("../model/Entity.class.php");
    $entity = new Entity();

    $result = $entity->login("usuario", $nome, $senha);
    
if ($result) {
    session_regenerate_id(true);
    $_SESSION["usuario"] = $nome;

    header("Location: ../view/pgCatalogo.php");
    exit;
} else {
    $_SESSION["erro"] = "Usuário ou senha incorreto";

    header("Location: ../view/pgLogin.php");
    exit;
}
?>