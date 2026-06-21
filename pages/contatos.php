<?php
$depth = 1;
$pageTitle = 'Z&L Cars — Contatos';
$currentPage = 'contatos';
require_once '../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container">
        <h1 class="section-title"><i class="bi bi-envelope me-2"></i>Fale Conosco</h1>
        <p class="text-muted mb-5">Estamos prontos para ajudar você a encontrar o veículo ideal.</p>

        <!-- Bootstrap: 4 cards de contato -->
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-sm-6">
                <a href="mailto:contato@zlcars.com.br" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">E-mail</h6>
                    <small class="text-muted">contato@zlcars.com.br</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="https://wa.me/5500000000000" target="_blank" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-whatsapp"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">WhatsApp</h6>
                    <small class="text-muted">(00) 00000-0000</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="https://instagram.com/zlcars" target="_blank" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-instagram"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">Instagram</h6>
                    <small class="text-muted">@zlcars</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="https://facebook.com/zlcars" target="_blank" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-facebook"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">Facebook</h6>
                    <small class="text-muted">/zlcars</small>
                </a>
            </div>
        </div>

        <!-- Endereço e horário -->
        <div class="row g-4">
            <div class="col-md-6">
                <div class="info-card h-100">
                    <h5 class="mb-3" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-geo-alt text-success me-2"></i>Endereço
                    </h5>
                    <p class="mb-1">Av. das Indústrias, 1500 — Centro</p>
                    <p class="mb-1 text-muted">Sua Cidade — Estado</p>
                    <p class="mb-0 text-muted">CEP 00000-000</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card h-100">
                    <h5 class="mb-3" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-clock text-success me-2"></i>Horário de Atendimento
                    </h5>
                    <p class="mb-1"><strong>Segunda a Sexta:</strong> 08h00 – 18h00</p>
                    <p class="mb-1"><strong>Sábado:</strong> 08h00 – 13h00</p>
                    <p class="mb-0 text-muted"><strong>Domingo:</strong> Fechado</p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
