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
                <a href="mailto:henriquezufa@gmail.com" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-envelope-fill"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">E-mail</h6>
                    <small class="text-muted">henriquezufa@gmail.com</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="https://wa.me/5544997555905" target="_blank" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-whatsapp"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">WhatsApp</h6>
                    <small class="text-muted">(44) 99755-5905</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="https://instagram.com/z.l.cars" target="_blank" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-instagram"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">Instagram</h6>
                    <small class="text-muted">@z.l.cars</small>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="https://www.facebook.com/profile.php?viewas=&id=61590477308188&show_switched_toast=false&show_switched_tooltip=false&is_tour_dismissed=false&is_tour_completed=false&show_podcast_settings=false&show_community_review_changes=false&should_open_composer=false&badge_type=NEW_MEMBER&show_community_rollback_toast=false&show_community_rollback=false&show_follower_visibility_disclosure=false&bypass_exit_warning=true" target="_blank" class="contact-card text-decoration-none d-block">
                    <div class="contact-icon"><i class="bi bi-facebook"></i></div>
                    <h6 style="font-family:'Oswald',sans-serif;font-size:1rem;">Facebook</h6>
                    <small class="text-muted">Z&L Cars</small>
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
                    <p class="mb-1">Rua Araribóia, 114, Centro</p>
                    <p class="mb-1 text-muted">Corumbataí do Sul - PR</p>
                    <p class="mb-0 text-muted">CEP 86.970-225</p>
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
