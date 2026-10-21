<?php
require_once __DIR__ . '/../controller/verificarSessao.php';
require_once __DIR__ . '/../model/Entity.class.php';

$boloEntity = new Entity();
$usuario = $_SESSION['usuario'] ?? null;
$termoBusca = trim($_GET['busca'] ?? '');
// Carrega os bolos cadastrados sem ocultar registros por quantidade nula/zero.
$bolos = $termoBusca !== ''
    ? $boloEntity->searchByName("bolo", $termoBusca)
    : $boloEntity->list("bolo");

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../assets/img/bolosboleiros.png">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/stylepgCatalogo.css">

    <title>Bolos Boleiros - Catálogo</title>
</head>

<body id="B">
    <header id="Topo">
        <div class="top-bar">
            <div id="IT">
                <img src="../assets/img/bolosboleiros.png" id="Logo">
                <a id="Msg">Catálogo</a>
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


    
    <div class="row" id="C">


        <!--Estrutura para a barra de pesquisa-->
        <?php if (empty($bolos)) : ?>
            <div class="col-12">
                <div class="alert alert-warning">Nenhum bolo encontrado para: <strong><?= htmlspecialchars($termoBusca) ?></strong></div>
            </div>
        <?php else : ?>
            <?php foreach ($bolos as $bolo) : ?>

                <div class="col-12 col-md-6 col-lg-4" style="padding-bottom: 16px;">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <?php if (!empty($bolo['foto'])): ?>
                                <img src="<?= htmlspecialchars($bolo['foto'], ENT_QUOTES, 'UTF-8') ?>" class="card-img-top" alt="<?= htmlspecialchars($bolo['nomebolo'] ?? 'Bolo', ENT_QUOTES, 'UTF-8') ?>">
                            <?php else: ?>
                                <img src="../assets/img/vitrinebolosbonitos.jpg" class="card-img-top" alt="Imagem não cadastrada">
                            <?php endif; ?>
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
                            <div class="d-flex gap-2">

                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>

    </div>

</body>

</html>