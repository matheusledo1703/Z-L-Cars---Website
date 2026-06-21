<?php
$depth = 1;
$pageTitle = 'Z&L Cars — Produtos';
$currentPage = 'produtos';
require_once '../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">
        <h1 class="section-title">Nossos Produtos</h1>
        <p class="text-muted mb-5">Escolha uma categoria para explorar nosso estoque:</p>

        <!-- Bootstrap: 3 colunas com Cards de categoria -->
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <a href="carros.php" class="cat-card">
                    <span class="cat-icon"><i class="bi bi-car-front-fill"></i></span>
                    <div class="cat-title">Carros</div>
                    <p class="text-muted small mt-2 mb-0">Sedans, hatches, SUVs e mais.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="motos.php" class="cat-card">
                    <span class="cat-icon"><i class="bi bi-bicycle"></i></span>
                    <div class="cat-title">Motos</div>
                    <p class="text-muted small mt-2 mb-0">Trail, esportivas, street e scooters.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="camionetas.php" class="cat-card">
                    <span class="cat-icon"><i class="bi bi-truck-front-fill"></i></span>
                    <div class="cat-title">Camionetas</div>
                    <p class="text-muted small mt-2 mb-0">Picapes e utilitários robustos.</p>
                </a>
            </div>
        </div>

        <!-- Bootstrap Accordion informativo -->
        <div class="mt-5">
            <h2 class="section-title">Informações de Compra</h2>
            <div class="accordion" id="faqAcordeon">
                <div class="accordion-item" style="background:var(--surface);border:1px solid var(--border);">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1"
                            style="background:var(--surface);color:var(--text);">
                            <i class="bi bi-question-circle text-success me-2"></i> Como funciona a compra?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAcordeon">
                        <div class="accordion-body text-muted">
                            Selecione os veículos desejados, adicione ao carrinho, preencha seus dados na aba <strong>Conta</strong>
                            e clique em <strong>Adquirir via WhatsApp</strong>. Nosso consultor entrará em contato.
                        </div>
                    </div>
                </div>
                <div class="accordion-item" style="background:var(--surface);border:1px solid var(--border);">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2"
                            style="background:var(--surface);color:var(--text);">
                            <i class="bi bi-cash-coin text-success me-2"></i> Quais formas de pagamento?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAcordeon">
                        <div class="accordion-body text-muted">
                            Trabalhamos com financiamento, consórcio, PIX, cartão e permuta. Consulte nossos especialistas.
                        </div>
                    </div>
                </div>
                <div class="accordion-item" style="background:var(--surface);border:1px solid var(--border);">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3"
                            style="background:var(--surface);color:var(--text);">
                            <i class="bi bi-shield-check text-success me-2"></i> Os veículos têm garantia?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAcordeon">
                        <div class="accordion-body text-muted">
                            Sim! Todos os veículos passam por revisão e possuem garantia de 3 meses contra defeitos mecânicos.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
