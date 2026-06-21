<?php
/** @var array $v Veículo do banco */
/** @var string $icone Classe Bootstrap Icon (ex: bi-car-front) */
$icone      = $icone ?? 'bi-car-front';
$nome       = nomeVeiculo($v);
$desc       = descricaoVeiculo($v);
$idFoto     = (int) ($v['ID_PRODUTO'] ?? 0);
$fotos      = fotosVeiculo($idFoto, $root ?? '');
$carouselId = 'carousel-produto-' . $idFoto;
?>
<div class="col-md-4 col-sm-6">
    <div class="card-custom h-100">
        <?php if (!empty($fotos)): ?>
        <div id="<?= htmlspecialchars($carouselId) ?>" class="carousel slide card-carousel" data-bs-ride="false">
            <div class="carousel-inner">
                <?php foreach ($fotos as $i => $fotoUrl): ?>
                <div class="carousel-item<?= $i === 0 ? ' active' : '' ?>">
                    <img src="<?= htmlspecialchars($fotoUrl) ?>"
                         alt="<?= htmlspecialchars($nome) ?> — foto <?= $i + 1 ?>"
                         class="d-block w-100 card-img-top">
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($fotos) > 1): ?>
            <div class="carousel-indicators">
                <?php foreach ($fotos as $i => $fotoUrl): ?>
                <button type="button"
                        data-bs-target="#<?= htmlspecialchars($carouselId) ?>"
                        data-bs-slide-to="<?= $i ?>"
                        class="<?= $i === 0 ? 'active' : '' ?>"
                        aria-label="Foto <?= $i + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button"
                    data-bs-target="#<?= htmlspecialchars($carouselId) ?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button"
                    data-bs-target="#<?= htmlspecialchars($carouselId) ?>" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próxima</span>
            </button>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="card-placeholder"><i class="bi <?= htmlspecialchars($icone) ?>"></i></div>
        <?php endif; ?>
        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= htmlspecialchars($nome) ?></h5>
            <div class="d-flex gap-2 mb-2 flex-wrap">
                <?php if (!empty($v['ANO'])): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($v['ANO']) ?></span>
                <?php endif; ?>
                <?php if (!empty($v['COR'])): ?>
                <span class="badge bg-light text-dark"><?= htmlspecialchars($v['COR']) ?></span>
                <?php endif; ?>
            </div>
            <?php if ($desc !== ''): ?>
            <p class="text-muted small flex-grow-1"><?= htmlspecialchars($desc) ?></p>
            <?php endif; ?>
            <div class="price-tag mb-3 mt-auto">
                <?= !empty($v['VALOR'])
                    ? formatarMoeda((float) $v['VALOR'])
                    : 'Consultar' ?>
            </div>
            <button type="button" class="btn btn-green w-100 btn-add-carrinho"
                data-veiculo="<?= htmlspecialchars(dadosVeiculoCarrinho($v), ENT_QUOTES) ?>">
                <i class="bi bi-cart-plus me-1"></i> Adicionar ao Carrinho
            </button>
        </div>
    </div>
</div>
