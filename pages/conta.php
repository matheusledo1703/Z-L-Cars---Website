<?php
$depth = 1;
$pageTitle = 'Z&L Cars — Minha Conta';
$currentPage = 'conta';
session_start();

require_once '../includes/db.php';
require_once '../functions/functions.php';

$erros   = [];
$sucesso = false;
$conn    = conectarBD();

$tiposDocumento = buscarTiposDocumento($conn);
$estados        = buscarEstados($conn);
$todasCidades   = buscarTodasCidades($conn);

$cidadesPorEstado = [];
foreach ($todasCidades as $cidade) {
    $idEstado = (int) $cidade['ID_ESTADO'];
    $cidadesPorEstado[$idEstado][] = [
        'id'   => (int) $cidade['ID_CIDADE'],
        'nome' => $cidade['DS_CIDADE'],
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'id_pessoa'       => (int) ($_SESSION['conta']['id_pessoa'] ?? 0),
        'nome'            => trim($_POST['nome']            ?? ''),
        'data_nascimento' => trim($_POST['data_nascimento'] ?? ''),
        'sexo'            => trim($_POST['sexo']            ?? ''),
        'email'           => trim($_POST['email']           ?? ''),
        'telefone'        => trim($_POST['telefone']        ?? ''),
        'id_tipodoc'      => (int) ($_POST['id_tipodoc']   ?? 0),
        'nr_documento'    => trim($_POST['nr_documento']    ?? ''),
        'endereco'        => trim($_POST['endereco']        ?? ''),
        'numero'          => trim($_POST['numero']          ?? ''),
        'bairro'          => trim($_POST['bairro']          ?? ''),
        'cep'             => trim($_POST['cep']             ?? ''),
        'id_estado'       => (int) ($_POST['id_estado']    ?? 0),
        'id_cidade'       => (int) ($_POST['id_cidade']     ?? 0),
    ];

    $erros = validarDadosConta($dados);

    if (empty($erros) && empty($tiposDocumento)) {
        $erros[] = 'Cadastre ao menos um tipo de documento no banco (tabela tipo_documento).';
    }

    if (empty($erros) && empty($todasCidades)) {
        $erros[] = 'Cadastre estados e cidades no banco antes de salvar o endereço.';
    }

    if (empty($erros)) {
        $resultado = salvarContaNoBanco($conn, $dados);

        if ($resultado['success']) {
            $nomeCidade = '';
            $nomeEstado = '';
            foreach ($todasCidades as $c) {
                if ((int) $c['ID_CIDADE'] === $dados['id_cidade']) {
                    $nomeCidade = $c['DS_CIDADE'];
                    $nomeEstado = $c['DS_ESTADO'];
                    break;
                }
            }

            $_SESSION['conta'] = array_merge($dados, [
                'id_pessoa'   => $resultado['id_pessoa'],
                'cidade_nome' => $nomeCidade,
                'estado_nome' => $nomeEstado,
            ]);
            $sucesso = true;
        } else {
            $erros = $resultado['erros'];
        }
    }
}

$conn->close();
$conta = $_SESSION['conta'] ?? [];
require_once '../includes/header.php';
?>

<div class="page-wrapper">
    <div class="container" style="max-width:860px;">
        <h1 class="section-title"><i class="bi bi-person-circle me-2"></i>Minha Conta</h1>
        <p class="text-muted mb-4">
            Preencha seus dados conforme o cadastro do sistema (Pessoa, Contato, Documento e Endereço).
        </p>

        <?php if ($sucesso): ?>
        <div class="alert alert-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            Dados salvos com sucesso! Você pode prosseguir para o
            <a href="carrinho.php" class="alert-link ms-1">carrinho</a>.
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

        <?php if (empty($tiposDocumento) || empty($estados)): ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Para salvar no banco, cadastre dados nas tabelas
            <strong>tipo_documento</strong>, <strong>estado</strong> e <strong>cidade</strong> no phpMyAdmin.
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm" style="background:var(--surface);border-radius:var(--radius);">
            <div class="card-body p-4">
                <form method="POST" action="conta.php">

                    <!-- Pessoa -->
                    <h5 class="mb-3" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-person text-success me-2"></i>Dados Pessoais
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="form-label">Nome completo *</label>
                            <input type="text" name="nome" class="form-control" required
                                   value="<?= htmlspecialchars($conta['nome'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Data de nascimento *</label>
                            <input type="date" name="data_nascimento" class="form-control" required
                                   value="<?= htmlspecialchars($conta['data_nascimento'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sexo *</label>
                            <select name="sexo" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php
                                $sexos = ['Masculino', 'Feminino', 'Outro'];
                                foreach ($sexos as $s):
                                    $sel = ($conta['sexo'] ?? '') === $s ? 'selected' : '';
                                ?>
                                <option value="<?= $s ?>" <?= $sel ?>><?= $s ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Contato -->
                    <h5 class="mb-3" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-telephone text-success me-2"></i>Contato
                    </h5>
                    <div class="row g-3 mb-4">
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
                    </div>

                    <!-- Documento -->
                    <h5 class="mb-3" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-card-text text-success me-2"></i>Documento
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="form-label">Tipo de documento *</label>
                            <select name="id_tipodoc" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($tiposDocumento as $tipo): ?>
                                <option value="<?= (int) $tipo['ID_TIPODOC'] ?>"
                                    <?= ((int) ($conta['id_tipodoc'] ?? 0) === (int) $tipo['ID_TIPODOC']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($tipo['DS_TIPODOC']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Número do documento *</label>
                            <input type="text" name="nr_documento" class="form-control"
                                   placeholder="000.000.000-00" required
                                   value="<?= htmlspecialchars($conta['nr_documento'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Endereço -->
                    <h5 class="mb-3" style="font-family:'Oswald',sans-serif;">
                        <i class="bi bi-geo-alt text-success me-2"></i>Endereço
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Logradouro *</label>
                            <input type="text" name="endereco" class="form-control"
                                   placeholder="Rua, Avenida..." required
                                   value="<?= htmlspecialchars($conta['endereco'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Número *</label>
                            <input type="text" name="numero" class="form-control" required
                                   value="<?= htmlspecialchars($conta['numero'] ?? '') ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Bairro *</label>
                            <input type="text" name="bairro" class="form-control" required
                                   value="<?= htmlspecialchars($conta['bairro'] ?? '') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">CEP *</label>
                            <input type="text" name="cep" class="form-control"
                                   placeholder="00000-000" required
                                   value="<?= htmlspecialchars($conta['cep'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Estado *</label>
                            <select name="id_estado" id="id_estado" class="form-select" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($estados as $est): ?>
                                <option value="<?= (int) $est['ID_ESTADO'] ?>"
                                    <?= ((int) ($conta['id_estado'] ?? 0) === (int) $est['ID_ESTADO']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($est['DS_ESTADO']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Cidade *</label>
                            <select name="id_cidade" id="id_cidade" class="form-select" required
                                    data-selected="<?= (int) ($conta['id_cidade'] ?? 0) ?>">
                                <option value="">Selecione o estado primeiro...</option>
                            </select>
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

<script>window.CIDADES_POR_ESTADO = <?= json_encode($cidadesPorEstado, JSON_HEX_TAG | JSON_HEX_APOS) ?>;</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const estadoSelect = document.getElementById('id_estado');
    const cidadeSelect = document.getElementById('id_cidade');

    function carregarCidades(idEstado) {
        cidadeSelect.innerHTML = '<option value="">Selecione...</option>';

        if (!idEstado || !window.CIDADES_POR_ESTADO[idEstado]) return;

        window.CIDADES_POR_ESTADO[idEstado].forEach(cidade => {
            const option = document.createElement('option');
            option.value = cidade.id;
            option.textContent = cidade.nome;
            cidadeSelect.appendChild(option);
        });
    }

    estadoSelect.addEventListener('change', function () {
        carregarCidades(this.value);
    });

    // se já tiver estado salvo (edição da conta)
    if (estadoSelect.value) {
        carregarCidades(estadoSelect.value);

        const cidadeSelecionada = cidadeSelect.dataset.selected;
        if (cidadeSelecionada) {
            setTimeout(() => {
                cidadeSelect.value = cidadeSelecionada;
            }, 100);
        }
    }
});
</script>
<?php require_once '../includes/footer.php'; ?>
