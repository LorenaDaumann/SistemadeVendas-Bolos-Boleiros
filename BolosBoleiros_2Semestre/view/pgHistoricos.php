<?php
require_once __DIR__ . '/../controller/verificarSessao.php';
require_once __DIR__ . '/../model/Entity.class.php';

$pedidoEntity = new Entity();
$pedidoEntity->ensurePedidoTable();
$usuario = $_SESSION['usuario'] ?? null;
$termoBusca = trim($_GET['busca'] ?? '');
$pedidos = $termoBusca !== '' ? $pedidoEntity->searchByName2("pedido", $termoBusca) : $pedidoEntity->list("pedido");

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../assets/img/bolosboleiros.png">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/css/stylepgHistoricos.css">

    <title>Bolos Boleiros - Históricos</title>
</head>

<body id="B">
    <header id="Topo">
        <div class="top-bar">
            <div id="IT">
                <img src="../assets/img/bolosboleiros.png" id="Logo">
                <a id="Msg">Históricos</a>
            </div>

            <div class="search-box" id="Pesquisar">
                <form action="../view/pgHistoricos.php" method="GET" id="Barra">
                    <input type="text" name="busca" value="<?= htmlspecialchars($termoBusca) ?>" placeholder="Pesquisar pedido..." id="Campo">
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





    <div class="container py-4">
        <div id="historicoTab" class="tab-content-section">
            <h2>Histórico de Pedidos</h2>
            <div class="table-responsive">
                <table border="1px" class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Status</th>
                            <th>Observação</th>
                            <th>Data do Pedido</th>
                            <th>Apagar Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos)) : ?>
                            <tr>
                                <!-- O colspan="7" faz a célula ocupar as 7 colunas da tabela, expandindo o CSS corretamente -->
                                <td colspan="7" style="text-align: center; padding: 20px;">Nenhum pedido registrado.</td>
                            </tr>

                        <?php else : ?>
                            <?php foreach ($pedidos as $pedido) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($pedido['id']); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['nome'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['cpf'] ?? ''); ?></td>
                                    <td>
                                        <!-- Formulário para atualizar o status do pedido correspondente -->
                                        <form method="POST" action="../controller/updateStatus.php" class="d-flex align-items-center">
                                            <!-- Envia o ID oculto do pedido para saber qual registro atualizar -->
                                            <input type="hidden" name="id" value="<?php echo $pedido['id']; ?>">

                                            <div class="form-group mb-0 flex-grow-1 mr-2">
                                                <!-- Corrigi o ID duplicado tornando-o único por linha -->
                                                <select class="form-control form-control-sm" id="status_<?php echo $pedido['id']; ?>" name="status" required>
                                                    <?php foreach (($status) as $status) {
                                                        // Verifica se o status percorrido é o mesmo atual do pedido para marcá-lo como selecionado
                                                        $selected = ($status['status'] == ($pedido['status'] ?? '')) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?= htmlspecialchars($status['status']) ?>" <?= $selected ?>>
                                                            <?= htmlspecialchars($status['status']) ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <!-- Botão pequeno para salvar a alteração do status -->
                                            <button type="submit" class="btn btn-success btn-sm" title="Salvar Alteração">
                                                ✓
                                            </button>
                                        </form>
                                    </td>
                                    <td><?php echo htmlspecialchars($pedido['observacao'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($pedido['data_pedido'] ?? ''); ?></td>
                                    <td>
                                        <form method="POST" action="../controller/deletePedido.php" onsubmit="return confirm('Deseja realmente excluir?');">
                                            <input type="hidden" name="id" value="<?= $pedido['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <style>
        /* CSS do botão de download*/
        .download-link {
            display: inline-flex;
            padding: 10px 20px;
            background-color: #e05f70;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .download-link:hover {
            background-color: #c43548;
        }
    </style>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Botão para baixar o histórico de registros de pedidos em PDF -->
        <a href="../controller/gerar_pdf.php" class="download-link">Baixar Histórico em PDF</a>
    </div>

    <?php if (!empty($termoBusca) && empty($pedidos)) : ?>
        <div class="col-12">
            <div class="alert alert-warning">Nenhum registro encontrado para: <strong><?= htmlspecialchars($termoBusca) ?></strong></div>
        </div>
    <?php endif; ?>
</body>

</html>