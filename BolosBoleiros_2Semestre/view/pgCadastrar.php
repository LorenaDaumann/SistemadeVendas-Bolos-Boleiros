<?php
require_once __DIR__ . '/../controller/verificarSessao.php';
$termoBusca = trim($_GET['busca'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../assets/img/bolosboleiros.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/stylepgcadastrar.css" rel="stylesheet" />
    <title>Bolos Boleiros - Cadastrar</title>
</head>

<body id="B">
    <header id="Topo">
        <div class="top-bar">
            <div id="IT">
                <img src="../assets/img/bolosboleiros.png" id="Logo">
                <a id="Msg">Cadastrar Bolo</a>
            </div>

            <div class="search-box" id="Pesquisar">
                <form action="../view/pgCatalogo.php" method="GET" id="Barra">
                    <input type="text" name="busca" value="<?= htmlspecialchars($termoBusca) ?>" placeholder="Pesquisar bolo..." id="Campo">
                    <button type="submit" id="Botao">Buscar</button>
                </form>
            </div>

            <div class="logout-box">
                <form action="../controller/logout.php" method="POST">
    <button type="submit" class="btn btn-danger" id="Sair">
        Sair
    </button>
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

    <form method="POST" action="../controller/insertBolo.php">

<h3>Informações do bolo</h3>

        <div class="form-group">
            <label id="L1">Nome:</label>
            <input type="text" name="nomebolo" placeholder="Nome do bolo" class="form-control" />
        </div>

        <div class="form-group">
            <label class="LR">Descrição:</label>
            <input type="text" name="descricao" placeholder="Descrição do bolo" class="form-control"/>
        </div>

        <div class=" form-group">
            <label class="LR">Massa:</label>
            <input type="text" name="massa" placeholder="Sabor do bolo" class="form-control"/>
        </div>

        <div class=" form-group">
            <label class="LR">Camadas:</label>
            <input type="number" name="camadas" placeholder="Número de camadas" class="form-control"/>
        </div>

        <div class=" form-group">
            <label class="LR">Recheios:</label>
            <input type="text" name="recheios" placeholder="Sabor dos recheios" class="form-control"/>
        </div>

        <div class=" form-group">
            <label class="LR">Cobertura:</label>
            <input type="text" name="cobertura" placeholder="Sabor da cobertura" class="form-control"/>
        </div>

        <div class=" form-group">
            <label class="LR">Preço:</label>
            <input type="number" name="preco" step="0.01" placeholder="Preço" class="form-control"/>
        </div>

        <div class=" form-group">
            <label class="LR">Gramas:</label>
            <input type="number" name="tamanho" placeholder="Gramas" class="form-control"/>
        </div>

        <div class=" form-group">
            <label class="LR">Quantidade:</label>
            <input type="number" name="quantidade" placeholder="Quantidade" class="form-control"/>
        </div>

        <div class="form-group">
            <label class="LR">Link da Imagem:</label>
            <input type="text" name="foto" placeholder="URL" class="form-control"/>
        </div>

        <br>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <?php if (isset($bolo['id'])): ?>
            <input type="hidden" name="id" value="<?= $bolo['id'] ?>">
            <?php endif; ?> 
            <button type="submit" class="btn btn-success btn-sm" id="Cadastrar">Cadastrar</button>          
        </div>        
    </form>

    <?php
    if (isset($_SESSION["msg"])) {
        echo $_SESSION["msg"];
    }
    if (isset($_SESSION["msg_error"])) {
        echo $_SESSION["msg_error"];
    }
    ?>


</body>

</html>