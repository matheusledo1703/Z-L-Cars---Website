<?php
$depth = 1;
$pageTitle = 'Z&L Cars — Minha Conta';
$currentPage = 'conta';
session_start();

$erros   = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../functions/functions.php';

    $dados = [
        'nome'      => trim($_POST['nome']      ?? ''),
        'email'     => trim($_POST['email']     ?? ''),
        'telefone'  => trim($_POST['telefone']  ?? ''),
        'cpf'       => trim($_POST['cpf']       ?? ''),
        'cidade'    => trim($_POST['cidade']    ?? ''),
        'estado'    => trim($_POST['estado']    ?? ''),
        'observacao'=> trim($_POST['observacao']?? ''),
    ];

    $erros = validarDadosConta($dados);

    if (empty($erros)) {
        $_SESSION['conta'] = $dados;
        $sucesso = true;
    }
}

$conta = $_SESSION['conta'] ?? [];
require_once '../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container" style="max-width:720px;">
        <h1 class="section-title"><i class="bi bi-person-circle me-2"></i>Minha Conta</h1>
        <p class="text-muted mb-4">Preencha seus dados para agilizar o contato na hora da compra.</p>

        <?php if ($sucesso): ?>
        <div class="alert alert-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            Dados salvos com sucesso! Você pode prosseguir para o <a href="carrinho.php" class="alert-link">carrinho</a>.
        </div>
        <?php endif; ?>

        <?php if (!empty($erros)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($erros as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Bootstrap Card com formulário -->
        <div class="card border-0 shadow-sm" style="background:var(--surface);border-radius:var(--radius);">
            <div class="card-body p-4">
                <form method="POST" action="conta.php">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nome completo *</label>
                            <input type="text" name="nome" class="form-control" required
                                   value="<?= htmlspecialchars($conta['nome'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail *</label>
                            <input type="email" name="email" class="form-control" required
                                   value="<?= htmlspecialchars($conta['email'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone / WhatsApp *</label>
                            <input type="tel" name="telefone" class="form-control"
                                   placeholder="(00) 00000-0000" required
                                   value="<?= htmlspecialchars($conta['telefone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control"
                                   placeholder="000.000.000-00"
                                   value="<?= htmlspecialchars($conta['cpf'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" class="form-control"
                                   value="<?= htmlspecialchars($conta['cidade'] ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">UF</option>
                                <?php
                                $estados = ['AC','AL','AM','AP','BA','CE','DF','ES','GO','MA','MG','MS','MT','PA','PB','PE','PI','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'];
                                foreach ($estados as $uf) {
                                    $sel = ($conta['estado'] ?? '') === $uf ? 'selected' : '';
                                    echo "<option value='$uf' $sel>$uf</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observações</label>
                            <textarea name="observacao" class="form-control" rows="3"
                                      placeholder="Alguma preferência ou observação para o consultor?"><?= htmlspecialchars($conta['observacao'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-green px-4">
                            <i class="bi bi-save me-1"></i> Salvar Dados
                        </button>
                        <a href="carrinho.php" class="btn btn-outline-green px-4">
                            <i class="bi bi-cart3 me-1"></i> Ver Carrinho
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
