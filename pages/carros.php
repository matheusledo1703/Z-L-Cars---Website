<?php
$depth = 1;
$pageTitle = 'Z&L Cars — Carros';
$currentPage = 'produtos';
require_once '../includes/header.php';
require_once '../includes/db.php';
require_once '../functions/functions.php';

$conn     = conectarBD();
$veiculos = buscarVeiculosPorCategoria($conn, 'CARRO');
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
                <li class="breadcrumb-item active">Carros</li>
            </ol>
        </nav>

        <h1 class="section-title"><i class="bi bi-car-front me-2"></i>Carros</h1>

        <div class="form-section mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-9">
                    <label class="form-label">Buscar por marca ou modelo</label>
                    <input type="text" name="busca" class="form-control"
                           placeholder="Ex: Onix, Chevrolet..." value="<?= htmlspecialchars($termoBusca) ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-green w-100">
                        <i class="bi bi-search me-1"></i> Buscar
                    </button>
                </div>
            </form>
        </div>

        <?php if (empty($veiculos)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Nenhum veículo encontrado com os filtros aplicados.
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($veiculos as $v):
                $icone = 'bi-car-front';
                require '../includes/card-veiculo.php';
            endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
