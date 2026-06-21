<?php
session_start();
require_once '../functions/functions.php';

// ── Ações AJAX ──────────────────────────────────────────────
if (isset($_GET['action'])) {
    header('Content-Type: application/json');

    if ($_GET['action'] === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $marca  = trim($_POST['marca']  ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $nome   = trim($_POST['nome']   ?? '');

        if ($nome === '') {
            $nome = trim($marca . ' ' . $modelo);
        }

        $item = normalizarItemCarrinho([
            'id'     => intval($_POST['id'] ?? 0),
            'nome'   => $nome,
            'marca'  => $marca,
            'modelo' => $modelo,
            'ano'    => intval($_POST['ano'] ?? 0),
            'cor'       => trim($_POST['cor'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'valor'     => floatval($_POST['valor'] ?? 0),
            'foto'      => trim($_POST['foto'] ?? ''),
        ]);

        if ($item['id'] <= 0 || $item['nome'] === '') {
            echo json_encode(['success' => false, 'mensagem' => 'Veículo inválido.']);
            exit;
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $_SESSION['carrinho'][] = $item;
        echo json_encode(['success' => true, 'quantidade' => count($_SESSION['carrinho'])]);
        exit;
    }

    if ($_GET['action'] === 'remove' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $index = intval($_POST['index'] ?? -1);
        if (isset($_SESSION['carrinho'][$index])) {
            array_splice($_SESSION['carrinho'], $index, 1);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false]);
    exit;
}

// ── Limpar carrinho ─────────────────────────────────────────
if (isset($_GET['limpar'])) {
    $_SESSION['carrinho'] = [];
    header('Location: carrinho.php');
    exit;
}

// ── Renderização ────────────────────────────────────────────
$depth = 1;
$pageTitle = 'Z&L Cars — Carrinho';
$currentPage = 'carrinho';

$carrinho = array_map('normalizarItemCarrinho', $_SESSION['carrinho'] ?? []);
$_SESSION['carrinho'] = $carrinho;
$totalCarrinho = calcularTotalCarrinho($carrinho);
$conta         = $_SESSION['conta'] ?? [];

require_once '../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">
        <h1 class="section-title"><i class="bi bi-cart3 me-2"></i>Meu Carrinho</h1>

        <?php if (empty($carrinho)): ?>
        <!-- Carrinho vazio -->
        <div class="text-center py-5">
            <i class="bi bi-cart-x" style="font-size:5rem;color:var(--text-muted);"></i>
            <h4 class="mt-3 text-muted">Seu carrinho está vazio</h4>
            <p class="text-muted">Adicione veículos para continuar.</p>
            <a href="produtos.php" class="btn btn-green mt-2">
                <i class="bi bi-car-front me-1"></i> Ver Produtos
            </a>
        </div>

        <?php else: ?>
        <div class="row g-4">
            <!-- Tabela dos itens -->
            <div class="col-lg-8">
                <div class="table-responsive table-custom">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Veículo</th>
                                <th class="text-end">Valor</th>
                                <th class="text-center">Remover</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carrinho as $i => $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php
                                        $fotoItem = urlFotoVeiculo($item['foto'] ?? null, $root ?? '../');
                                        if ($fotoItem):
                                        ?>
                                        <img src="<?= htmlspecialchars($fotoItem) ?>" alt="" class="cart-thumb">
                                        <?php else: ?>
                                        <i class="bi bi-car-front text-success"></i>
                                        <?php endif; ?>
                                        <div>
                                            <?= htmlspecialchars($item['nome']) ?>
                                            <?php if (!empty($item['ano']) || !empty($item['cor'])): ?>
                                            <small class="text-muted d-block">
                                                <?php if (!empty($item['ano'])): ?>
                                                <?= (int) $item['ano'] ?>
                                                <?php endif; ?>
                                                <?php if (!empty($item['cor'])): ?>
                                                <?= !empty($item['ano']) ? ' · ' : '' ?><?= htmlspecialchars($item['cor']) ?>
                                                <?php endif; ?>
                                            </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end price-tag">
                                    <?= $item['valor'] > 0
                                        ? formatarMoeda($item['valor'])
                                        : 'Consultar' ?>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-danger"
                                        onclick="removerDoCarrinho(<?= $i ?>)">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="produtos.php" class="btn btn-outline-green">
                        <i class="bi bi-plus-circle me-1"></i> Adicionar mais
                    </a>
                    <a href="carrinho.php?limpar=1" class="btn btn-outline-danger"
                       onclick="return confirm('Limpar o carrinho?')">
                        <i class="bi bi-trash me-1"></i> Limpar
                    </a>
                </div>
            </div>

            <!-- Resumo + Checkout -->
            <div class="col-lg-4">
                <div class="form-section">
                    <h5 class="mb-3" style="font-family:'Oswald',sans-serif;">Resumo do Pedido</h5>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Itens:</span>
                        <span><?= count($carrinho) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-top pt-2">
                        <strong>Total estimado:</strong>
                        <strong class="price-tag">
                            <?= $totalCarrinho > 0
                                ? formatarMoeda($totalCarrinho)
                                : 'Consultar' ?>
                        </strong>
                    </div>

                    <?php if (empty($conta)): ?>
                    <div class="alert alert-warning py-2 small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Preencha seus dados na aba <a href="conta.php"><strong>Conta</strong></a> para prosseguir.
                    </div>
                    <?php else: ?>
                    <!-- Dados do cliente resumo -->
                    <div class="alert alert-success py-2 small mb-3">
                        <i class="bi bi-person-check me-1"></i>
                        <strong><?= htmlspecialchars($conta['nome']) ?></strong><br>
                        <?= htmlspecialchars($conta['telefone']) ?> · <?= htmlspecialchars($conta['email']) ?>
                    </div>

                    <?php
                    $msg = montarMensagemWhatsApp($conta, $carrinho);
                    // Número da loja — altere aqui
                    $numero = '5500000000000';
                    $urlWpp = "https://wa.me/{$numero}?text={$msg}";
                    ?>
                    <a href="<?= $urlWpp ?>" target="_blank" class="btn btn-success w-100 py-2">
                        <i class="bi bi-whatsapp me-2 fs-5"></i>
                        <strong>Adquirir via WhatsApp</strong>
                    </a>
                    <small class="text-muted d-block text-center mt-2">
                        Você será redirecionado ao WhatsApp da Z&amp;L Cars.
                    </small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
