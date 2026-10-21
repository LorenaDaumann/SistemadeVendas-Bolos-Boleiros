
<?php
require_once __DIR__ . '/../controller/verificarSessao.php';
require_once __DIR__ . '/../model/Conexao.class.php';

$idBolo = filter_input(INPUT_GET, 'id_bolo', FILTER_VALIDATE_INT);

if (!$idBolo || $idBolo < 1) {
    $_SESSION['msg_error'] = 'Bolo não identificado.';
    header('Location: pgAlterar.php');
    exit;
}

try {
    $pdo = Conexao::getInstance();

    $stmt = $pdo->prepare(
        'SELECT * FROM bolo WHERE id_bolo = :id_bolo'
    );
    $stmt->execute([':id_bolo' => $idBolo]);

    $bolo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bolo) {
        $_SESSION['msg_error'] = 'Bolo não encontrado.';
        header('Location: pgAlterar.php');
        exit;
    }
} catch (Throwable $e) {
    error_log($e->getMessage());
    $_SESSION['msg_error'] = 'Não foi possível carregar o bolo.';
    header('Location: pgAlterar.php');
    exit;
}

function valorBolo($bolo, $campo) {
    return htmlspecialchars((string)($bolo[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Alterar bolo - Bolos Boleiros</title>

    <link rel="icon" type="image/png"
          href="../assets/img/bolosboleiros.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/stylepgcadastrar.css" rel="stylesheet">

    <style>
        .editar-bolo {
            width: 100%;
            max-width: 620px;
            margin: 30px auto;
            padding: 24px;
            background: #fff;
            border: 1px solid #f0caca;
            border-radius: 18px;
            box-shadow: 0 3px 12px rgba(114, 82, 85, .10);
        }

        .editar-bolo h2 {
            color: #74353d;
            text-align: center;
            font-family: Georgia, 'Times New Roman', serif;
            margin-bottom: 24px;
        }

        .editar-bolo label {
            display: block;
            margin-bottom: 6px;
            color: #74353d;
            font-weight: bold;
        }

        .editar-bolo .form-control {
            margin-bottom: 16px;
            border-radius: 16px;
        }

        .editar-bolo .btn-salvar {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 25px;
            background: #e56979;
            color: #fff;
            font-weight: bold;
        }

        .editar-bolo .btn-salvar:hover {
            background: #d95368;
        }
    </style>
</head>

<body id="B">

    <main class="container">
        <form class="editar-bolo"
              action="../controller/updateBolo.php"
              method="POST">

            <h2>Alterar bolo</h2>

            <input type="hidden" name="id_bolo"
                   value="<?= (int)$bolo['id_bolo'] ?>">

            <label for="nomebolo">Nome do bolo</label>
            <input class="form-control" type="text" id="nomebolo"
                   name="nomebolo" maxlength="60" required
                   value="<?= valorBolo($bolo, 'nomebolo') ?>">

            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao"
                      name="descricao" maxlength="500"
                      rows="3" required><?= valorBolo($bolo, 'descricao') ?></textarea>

            <label for="massa">Massa</label>
            <input class="form-control" type="text" id="massa"
                   name="massa" maxlength="60" required
                   value="<?= valorBolo($bolo, 'massa') ?>">

            <label for="camadas">Camadas</label>
            <input class="form-control" type="number" id="camadas"
                   name="camadas" min="0"
                   value="<?= valorBolo($bolo, 'camadas') ?>">

            <label for="recheios">Recheios</label>
            <input class="form-control" type="text" id="recheios"
                   name="recheios" maxlength="60"
                   value="<?= valorBolo($bolo, 'recheios') ?>">

            <label for="cobertura">Cobertura</label>
            <input class="form-control" type="text" id="cobertura"
                   name="cobertura" maxlength="60"
                   value="<?= valorBolo($bolo, 'cobertura') ?>">

            <label for="preco">Preço (R$)</label>
            <input class="form-control" type="number" id="preco"
                   name="preco" min="0.01" step="0.01" required
                   value="<?= valorBolo($bolo, 'preco') ?>">

            <label for="tamanho">Tamanho</label>
            <input class="form-control" type="number" id="tamanho"
                   name="tamanho" min="0.01" step="0.01" required
                   value="<?= valorBolo($bolo, 'tamanho') ?>">

            <label for="quantidade">Quantidade em estoque</label>
            <input class="form-control" type="number" id="quantidade"
                   name="quantidade" min="0" required
                   value="<?= valorBolo($bolo, 'quantidade') ?>">

            <label for="foto">Link da imagem</label>
            <input class="form-control" type="text" id="foto"
                   name="foto" maxlength="500"
                   value="<?= valorBolo($bolo, 'foto') ?>">

            <button class="btn-salvar" type="submit">
                Salvar alterações
            </button>

            <a href="pgAlterar.php"
               class="btn btn-light btn-block mt-2">
                Cancelar
            </a>
        </form>
    </main>

</body>
</html>
