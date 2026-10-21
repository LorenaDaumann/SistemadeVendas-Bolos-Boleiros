<?php
require_once __DIR__ . '/../controller/verificarSessao.php';
require_once __DIR__ . '/../model/Entity.class.php';

try {
    $pedidoEntity = new Entity();
    $pedidoEntity->ensurePedidoTable();
    // Exporta todos os pedidos, sem limitar pelos filtros da página.
    $pedidos = $pedidoEntity->list('pedido');
} catch (Throwable $e) {
    http_response_code(500);
    exit('Não foi possível consultar os pedidos. Verifique a conexão com o banco de dados.');
}

$esc = static function ($valor): string {
    return htmlspecialchars((string)($valor ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Histórico de Pedidos - Bolos Boleiros</title>
<style>
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color: #333; margin: 24px; }
    h1 { text-align: center; color: #235181; font-size: 22px; margin: 0 0 6px; }
    .subtitulo { text-align: center; color: #666; margin: 0 0 18px; font-size: 12px; }
    .acoes { display: flex; justify-content: center; gap: 10px; margin-bottom: 18px; }
    .acoes button { padding: 10px 16px; border: 0; border-radius: 5px; cursor: pointer; background: #235181; color: #fff; font-size: 14px; }
    .acoes .voltar { background: #666; }
    table { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 11px; }
    th, td { border: 1px solid #bbb; padding: 7px 6px; text-align: left; overflow-wrap: anywhere; }
    th { background: #235181; color: white; }
    tbody tr:nth-child(even) { background: #f2f5f8; }
    thead { display: table-header-group; }
    tr { break-inside: avoid; page-break-inside: avoid; }
    @page { size: A4 auto; margin: 12mm; }
    @media print {
        body { margin: 0; }
        .acoes { display: none !important; }
        h1 { font-size: 18px; }
        table { font-size: 9px; }
        th, td { padding: 5px 4px; }
        th { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        tbody tr:nth-child(even) { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
    }
</style>
</head>
<body>
<h1>Histórico de Pedidos — Bolos Boleiros</h1>
<p class="subtitulo">Total de registros: <?= count($pedidos) ?></p>
<div class="acoes">
    <button type="button" onclick="window.print()">Salvar / Imprimir em PDF</button>
    <button type="button" class="voltar" onclick="history.back()">Voltar</button>
</div>
<table>
<thead><tr>
    <th style="width:5%">ID</th>
    <th style="width:19%">Nome</th>
    <th style="width:14%">CPF</th>
    <th style="width:12%">Status</th>
    <th style="width:29%">Observação</th>
    <th style="width:15%">Data do Pedido</th>
    <th style="width:6%">Bolo ID</th>
</tr></thead>
<tbody>
<?php if (empty($pedidos)): ?>
    <tr><td colspan="7" style="text-align:center">Nenhum pedido registrado.</td></tr>
<?php else: foreach ($pedidos as $pedido): ?>
    <tr>
        <td><?= $esc($pedido['id'] ?? '') ?></td>
        <td><?= $esc($pedido['nome'] ?? '') ?></td>
        <td><?= $esc($pedido['cpf'] ?? '') ?></td>
        <td><?= $esc($pedido['status'] ?? '') ?></td>
        <td><?= $esc($pedido['observacao'] ?? '') ?></td>
        <td><?= $esc($pedido['data_pedido'] ?? '') ?></td>
        <td><?= $esc($pedido['bolo_id'] ?? '') ?></td>
    </tr>
<?php endforeach; endif; ?>
</tbody>
</table>
<script>
// Abre a janela de impressão do navegador; nela é possível escolher "Salvar como PDF".
window.addEventListener('load', function () {
    window.setTimeout(function () { window.print(); }, 300);
});
</script>
</body>
</html>
