<?php
require_once __DIR__ . '/../controller/verificarSessao.php';
require_once __DIR__ . '/../model/Entity.class.php';
$entity = new Entity();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../assets/img/bolosboleiros.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/stylepgRegistrarPedido.css" rel="stylesheet" />
    <script src="https://jquery.com"></script>
    <script src="https://cloudflare.com"></script>



    <script src="../assets/js/jquery-3.5.1.js"></script>
    <script src="../assets/js/jquery.mask.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/exemplo5.js"></script>

    <title>Bolos Boleiros - Registrar Venda</title>
</head>

<body id="B">
    <header id="Topo">
        <div class="top-bar">
            <div id="IT">
                <img src="../assets/img/bolosboleiros.png" id="Logo">
                <a id="Msg">Registrar Venda</a>
            </div>

            <div class="search-box" id="Pesquisar">
                <form action="../view/pgCatalogo.php" method="GET" id="Barra">
                    <input type="text" name="busca" placeholder="Pesquisar bolo..." id="Campo">
                    <button type="submit" id="Botao">Buscar</button>
                </form>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <form action="../controller/logout.php" method="POST">
                    <button type="submit" class="btn btn-danger" id="Sair">Sair</button>
                </form>
            </div>
        </div>

        <nav class="nav-bar" id="Menu">
            <ul class="nav-menu">
                <li><a href="../view/pgCatalogo.php">Catálogo</a></li>
                <li><a href="../view/pgAlterar.php">Alterar Catálogo</a></li>
                <li><a href="../view/pgCadastrar.php">Cadastrar Bolo</a></li>
                <li><a href="../view/pgRegistrarPedido.php">Registrar Pedido</a></li>
                <li><a href="../view/pgHistoricos.php">Historicos</a></li>
            </ul>
        </nav>


        <?php if (isset($_SESSION['msg'])) : ?> <!--mensagem de sucesso ao fazer o pedido-->
            <div class="alert alert-success"><?php echo $_SESSION['msg']; ?></div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['msg_error'])) : ?> <!--mensagem de erro ao fazer o pedido-->
            <div class="alert alert-danger"><?php echo $_SESSION['msg_error']; ?></div>
            <?php unset($_SESSION['msg_error']); ?>
        <?php endif; ?>
    </header>

    <form method="POST" action="../controller/venda.php">

        <h3>Informações da Venda</h3>

        <div class="form-group">
            <label class="LR">Nome</label>
            <input type="text" class="form-control" id="I1" name="nome" autofocus placeholder="Nome" required />
        </div>

        <div class="form-group mb-3">
            <label class="form-label">CPF:</label>
            <input type="text" class="form-control" id="I2" name="cpf" placeholder="000.000.000-00" required />
        </div>

        <div class=" form-group">
            <label class="LR">Endereço</label>
            <input type="text" name="endereco" class="form-control" placeholder="Endereço" required />
        </div>

        <div class=" form-group">
            <label class="LR">Email</label>
            <input type="email" class="form-control" name="email" placeholder="nome@servidor.com" required />
        </div>

        <div class=" form-group">
            <label class="LR">Telefone</label>
            <input type="text" class="form-control" name="telefone" id="I5" placeholder="(00) 00000-0000" required />
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Bolo:</label>
            <select class="form-control" id="bolo" name="id_bolo" required>
                <?php foreach ($entity->list("bolo") as $bolo) { ?>
                    <option value="<?= $bolo['id_bolo'] ?>">
                        <?= htmlspecialchars($bolo['nomebolo']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="form-group">
            <label class="LR">Observação</label>
            <input type="text" class="form-control" name="obs" placeholder="Observações, preferencias ou alergias" required />
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Prazo</label>
            <input type="date" class="form-control" id="data" name="data" required />
        </div>


        <br>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <?php if (isset($bolo['id'])): ?>
                <input type="hidden" name="id" value="<?= $bolo['id'] ?>">
            <?php endif; ?>
            <button type="submit" class="btn btn-success btn-sm" id="BtnCadastrar">Cadastrar</button>
        </div>
    </form>

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
    }
    if (isset($_SESSION["msg_error"])) {
        echo $_SESSION["msg_error"];
    }
    ?>
</body>

</html>