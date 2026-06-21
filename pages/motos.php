<?php
$depth = 1;
$pageTitle = 'Z&L Cars — Motos';
$currentPage = 'produtos';
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../functions/functions.php';

$conn     = conectarBD();
$veiculos = buscarVeiculosPorCategoria($conn, 'moto');
$conn->close();

$termoBusca = trim($_GET['busca'] ?? '');
if ($termoBusca !== '') {
    $veiculos = pesquisarVeiculo($veiculos, $termoBusca);
}
?>

<div class="page-wrapper">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="produtos.php">Produtos</a></li>
                <li class="breadcrumb-item active">Motos</li>
            </ol>
        </nav>

        <h1 class="section-title"><i class="bi bi-bicycle me-2"></i>Motos</h1>

        <div class="form-section mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Buscar por modelo</label>
                    <input type="text" name="busca" class="form-control"
                           placeholder="Ex: Honda, Yamaha..." value="<?= htmlspecialchars($termoBusca) ?>">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-green w-100">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                </div>
            </form>
        </div>

        <?php if (empty($veiculos)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Nenhuma moto disponível no momento. Volte em breve!
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($veiculos as $v): ?>
            <div class="col-md-4 col-sm-6">
                <div class="card-custom h-100">
                    <div class="card-placeholder"><i class="bi bi-bicycle"></i></div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($v['nome']) ?></h5>
                        <div class="d-flex gap-2 mb-2 flex-wrap">
                            <?php if (!empty($v['ano'])): ?>
                            <span class="badge bg-secondary"><?= htmlspecialchars($v['ano']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($v['km'])): ?>
                            <span class="badge" style="background:var(--green-light);color:var(--green);">
                                <?= number_format($v['km'], 0, ',', '.') ?> km
                            </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted small flex-grow-1"><?= htmlspecialchars($v['descricao'] ?? '') ?></p>
                        <div class="price-tag mb-3 mt-auto">
                            <?= $v['preco'] ? formatarMoeda(floatval($v['preco'])) : 'Consultar' ?>
                        </div>
                        <button class="btn btn-green w-100"
                            onclick="adicionarAoCarrinho(<?= $v['id'] ?>, '<?= htmlspecialchars(addslashes($v['nome'])) ?>', '<?= $v['preco'] ?>')">
                            <i class="bi bi-cart-plus me-1"></i> Adicionar ao Carrinho
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
