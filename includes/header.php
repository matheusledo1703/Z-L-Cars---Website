<?php
// Define a raiz do projeto para paths relativos
$root = str_repeat('../', $depth ?? 0);
?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Z&L Cars' ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- CSS próprio -->
    <link rel="stylesheet" href="<?= $root ?>assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="<?= $root ?>index.php">
            <span class="brand-zl">Z&amp;L</span>
            <span class="brand-cars">CARS</span>
        </a>

        <!-- Toggler mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Links -->
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage ?? '') === 'home' ? 'active' : '' ?>" href="<?= $root ?>index.php">
                        <i class="bi bi-house-door me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage ?? '') === 'produtos' ? 'active' : '' ?>" href="<?= $root ?>pages/produtos.php">
                        <i class="bi bi-car-front me-1"></i>Produtos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage ?? '') === 'conta' ? 'active' : '' ?>" href="<?= $root ?>pages/conta.php">
                        <i class="bi bi-person-circle me-1"></i>Conta
                    </a>
                </li>
                <li class="nav-item position-relative">
                    <a class="nav-link <?= ($currentPage ?? '') === 'carrinho' ? 'active' : '' ?>" href="<?= $root ?>pages/carrinho.php">
                        <i class="bi bi-cart3 me-1"></i>Carrinho
                        <?php
                        session_start_safe();
                        $qtd = count($_SESSION['carrinho'] ?? []);
                        if ($qtd > 0):
                        ?>
                        <span class="badge bg-success cart-badge"><?= $qtd ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($currentPage ?? '') === 'contatos' ? 'active' : '' ?>" href="<?= $root ?>pages/contatos.php">
                        <i class="bi bi-envelope me-1"></i>Contatos
                    </a>
                </li>
                <!-- Dark mode toggle -->
                <li class="nav-item ms-2">
                    <button id="darkModeToggle" class="btn btn-dark-toggle" title="Alternar modo escuro">
                        <i class="bi bi-moon-stars-fill" id="darkIcon"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php
// Evita erro de session já iniciada
function session_start_safe() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}
session_start_safe();
?>
