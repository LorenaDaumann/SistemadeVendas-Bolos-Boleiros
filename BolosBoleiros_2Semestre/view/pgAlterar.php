<?php
require_once __DIR__ . '/../controller/verificarSessao.php';
require_once __DIR__ . '/../model/Entity.class.php';

$boloEntity = new Entity();
$usuario = $_SESSION['usuario'] ?? null;
$termoBusca = trim($_GET['busca'] ?? '');
$bolos = $termoBusca !== '' ? $boloEntity->searchByName("bolo", $termoBusca) : $boloEntity->list("bolo");

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../assets/img/bolosboleiros.png">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/stylepgAlterar.css" rel="stylesheet" />
    <title>Bolos Boleiros - Alterar</title>
</head>

<body id="B">
    <header id="Topo">
        <div class="top-bar">
            <div id="IT">
                <img src="../assets/img/bolosboleiros.png" id="Logo">
                <a id="Msg">Alterar Catálogo</a>
            </div>

            <div class="search-box" id="Pesquisar">
                <form action="../view/pgCatalogo.php" method="GET" id="Barra">
                    <input type="text" name="busca" value="<?= htmlspecialchars($termoBusca) ?>" placeholder="Pesquisar bolo..." id="Campo">
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
    </header>



    <?php if (isset($_SESSION['msg'])) : ?> <!--mensagem de sucesso ao cadastrar o bolo-->
        <div class="alert alert-success"><?php echo $_SESSION['msg']; ?></div>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['msg_error'])) : ?> <!--mensagem de erro ao cadastrar-->
        <div class="alert alert-danger"><?php echo $_SESSION['msg_error']; ?></div>
        <?php unset($_SESSION['msg_error']); ?>
    <?php endif; ?>

    <div class="row" id="C">


        <!--Estrutura para a barra de pesquisa-->
        <?php if (empty($bolos)) : ?>
            <div class="col-12">
                <div class="alert alert-warning">Nenhum bolo encontrado para: <strong><?= htmlspecialchars($termoBusca) ?></strong></div>
            </div>
        <?php else : ?>
            <?php foreach ($bolos as $bolo) : ?>

                <div class="col-4" style="padding-bottom: 20px;">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($bolo["nomebolo"]); ?>
                            </h5>
                            <h6 class="card-subtitle">
                                <?php echo htmlspecialchars($bolo["descricao"]); ?>
                            </h6>
                            <!--busca todas as características de um bolo cadastrado-->
                            <ul class="list-unstyled">
                                <br>
                                <li>Massa: <?php echo htmlspecialchars($bolo["massa"]); ?></li>
                                <li>Camadas: <?php echo htmlspecialchars($bolo["camadas"]); ?></li>
                                <li>Recheios: <?php echo htmlspecialchars($bolo["recheios"]); ?></li>
                                <li>Cobertura: <?php echo htmlspecialchars($bolo["cobertura"]); ?></li>
                                <li>Tamanho: <?php echo htmlspecialchars($bolo["tamanho"]); ?></li>
                                <li>Preço: <?php echo htmlspecialchars($bolo["preco"]); ?></li>
                                <li>Quantidade: <?php echo htmlspecialchars($bolo["quantidade"]); ?></li>
                            </ul>


                            <div class="d-flex justify-content-between align-items-center mb-3">
<a
    class="btn btn-danger btn-sm"
    href="../view/pgUpdate.php?id_bolo=<?= (int)$bolo['id_bolo'] ?>">
    Alterar
</a>
                            </div>


                            <div class="d-flex justify-content-between align-items-center mb-3">
                                
<form action="../controller/deleteBolo.php" method="POST"
      onsubmit="return confirm('Deseja realmente excluir este bolo?');">

    <input type="hidden"
           name="id_bolo"
           value="<?= (int)$bolo['id_bolo'] ?>">

    <button type="submit"
            class="btn btn-danger btn-sm"
            id="Deletar">
        Deletar
    </button>
</form>
                            </div>


                        </div>
                    </div>
                </div>
                <?php
                if (isset($_SESSION["msg"])) {
                    echo $_SESSION["msg"];
                }
                if (isset($_SESSION["msg_error"])) {
                    echo $_SESSION["msg_error"];
                }
                ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</body>

</html>