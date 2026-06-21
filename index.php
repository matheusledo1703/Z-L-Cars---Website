<?php
$depth = 0;
$pageTitle = 'Z&L Cars — Home';
$currentPage = 'home';
require_once 'includes/header.php';
require_once 'includes/db.php';
require_once 'functions/functions.php';
?>

<!-- Hero -->
<section class="hero-section">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title mb-3">
                    Bem-vindo à<br><span>Z&amp;L Cars</span>
                </h1>
                <p class="hero-subtitle mb-4">
                    Há mais de 10 anos conectando você ao veículo ideal.<br>
                    Carros, motos e camionetas com procedência e garantia.
                </p>
                <a href="pages/produtos.php" class="btn btn-hero me-2">
                    <i class="bi bi-car-front me-2"></i>Ver Produtos
                </a>
                <a href="pages/contatos.php" class="btn btn-outline-light">
                    <i class="bi bi-whatsapp me-2"></i>Fale Conosco
                </a>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <i class="bi bi-car-front-fill" style="font-size:9rem;color:var(--green-accent);opacity:.85;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Sobre / Info -->
<div class="page-wrapper">
    <div class="container">

        <!-- Por que nos escolher -->
        <h2 class="section-title">Por que a Z&amp;L Cars?</h2>
        <div class="row g-4 mb-5">
            <?php
            $diferenciais = [
                ['bi-shield-check',  'Procedência Garantida', 'Todos os veículos passam por inspeção completa antes de serem anunciados.'],
                ['bi-cash-coin',     'Melhores Preços',       'Negociamos direto com proprietários para oferecer o melhor custo-benefício.'],
                ['bi-headset',       'Suporte Completo',      'Atendimento personalizado do primeiro contato até a entrega das chaves.'],
                ['bi-file-earmark-check', 'Documentação OK', 'Toda a documentação verificada e transferência facilitada para você.'],
            ];
            foreach ($diferenciais as $d):
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="info-card h-100">
                    <div class="info-icon mb-2"><i class="bi <?= $d[0] ?>"></i></div>
                    <h5 class="mb-1"><?= $d[1] ?></h5>
                    <p class="text-muted small mb-0"><?= $d[2] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Localização & Horários -->
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <h2 class="section-title">Nossa Localização</h2>
                <div class="info-card">
                    <p class="mb-2"><i class="bi bi-geo-alt-fill text-success me-2"></i>
                        <strong>Endereço:</strong> Rua Araribóia, 114, Centro<br>
                        <small class="text-muted ms-4">Corumbataí do Sul - PR, 86.970-225</small>
                    </p>
                    <p class="mb-2"><i class="bi bi-clock-fill text-success me-2"></i>
                        <strong>Horário:</strong> Seg a Sex: 08h–18h | Sáb: 08h–13h
                    </p>
                    <p class="mb-0"><i class="bi bi-telephone-fill text-success me-2"></i>
                        <strong>Telefone:</strong> (44) 99755-5905
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="section-title">Categorias</h2>
                <div class="row g-3">
                    <?php
                    $cats = [
                        ['bi-car-front',    'Carros',     'pages/carros.php'],
                        ['bi-bicycle',      'Motos',      'pages/motos.php'],
                        ['bi-truck-front',  'Camionetas', 'pages/camionetas.php'],
                    ];
                    foreach ($cats as $c):
                    ?>
                    <div class="col-4">
                        <a href="<?= $c[2] ?>" class="cat-card py-3">
                            <span class="cat-icon" style="font-size:2rem;"><i class="bi <?= $c[0] ?>"></i></span>
                            <div class="cat-title" style="font-size:1rem;"><?= $c[1] ?></div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Destaques do banco -->
        <?php
        $conn = conectarBD();
        $veiculos = buscarVeiculosPorCategoria($conn, 'CARRO');
        $conn->close();
        if (!empty($veiculos)):
        ?>
        <h2 class="section-title">Destaques — Carros</h2>
        <div class="row g-4">
            <?php foreach (array_slice($veiculos, 0, 3) as $v):
                $icone = 'bi-car-front';
                require 'includes/card-veiculo.php';
            endforeach; ?>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
