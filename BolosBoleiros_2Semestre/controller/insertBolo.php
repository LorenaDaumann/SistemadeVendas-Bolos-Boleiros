<?php
require_once __DIR__ . '/verificarSessao.php';
require_once("../model/Entity.class.php");
$entity = new Entity();


// Pega todos os dados enviados do formulário
$dadosForm = $_POST; 

// Monta o array trocando apenas a chave 'nome' pelo nome correto da sua coluna no banco
$dados = [
    'nomebolo' => $dadosForm['nomebolo'],
    'descricao' => $dadosForm['descricao'],
    'massa'     => $dadosForm['massa'],
    'camadas'   => $dadosForm['camadas'],
    'recheios'  => $dadosForm['recheios'],
    'cobertura' => $dadosForm['cobertura'],
    'preco'     => $dadosForm['preco'],
    'tamanho'   => $dadosForm['tamanho'],
    'quantidade'=> $dadosForm['quantidade'],
    'foto'      => $dadosForm['foto']
];

if (!empty($_POST)) { // Verifica se o formulário foi postado
    try {
        $entity->insert("bolo", $dados);
        $_SESSION["msg"] = "Cadastrado com sucesso!";
    } catch (Exception $e) {
        $_SESSION["msg_error"] = $e->getMessage(); // Guarda apenas a mensagem do erro para exibição limpa
    }
}

header("Location: ../view/pgCatalogo.php");
exit; 
