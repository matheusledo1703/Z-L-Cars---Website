<?php
$depth = 1;
$pageTitle = 'Z&L Cars — Motos';
$currentPage = 'produtos';
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../functions/functions.php';

$conn     = conectarBD();
$veiculos = buscarVeiculosPorCategoria($conn, 'MOTO');
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
                    <label class="form-label">Buscar por marca ou modelo</label>
                    <input type="text" name="busca" class="form-control"
                           placeholder="Ex: Honda, Bros..." value="<?= htmlspecialchars($termoBusca) ?>">
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
            <?php foreach ($veiculos as $v):
                $icone = 'bi-bicycle';
                require '../includes/card-veiculo.php';
            endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
