<?php

/**
 * Busca produtos por categoria.
 * Categorias válidas: CARRO, MOTO, CAMINHONETE
 */
function buscarVeiculosPorCategoria(mysqli $conn, string $categoria): array
{
    $sql = "
        SELECT
            p.ID_PRODUTO,
            p.MARCA,
            p.MODELO,
            p.ANO,
            p.COR,
            p.DESCRICAO,
            p.VALOR,
            tp.DS_TIPO
        FROM produto p
        INNER JOIN tipo_produto tp
            ON p.ID_TIPO_PRODUTO = tp.ID_TIPO_PRODUTO
        WHERE UPPER(tp.DS_TIPO) = UPPER(?)
        ORDER BY p.MARCA ASC, p.MODELO ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $categoria);
    $stmt->execute();

    $result = $stmt->get_result();
    $veiculos = [];

    while ($row = $result->fetch_assoc()) {
        $veiculos[] = $row;
    }

    $stmt->close();

    return $veiculos;
}

/**
 * Nome de exibição: Marca + Modelo.
 */
function nomeVeiculo(array $v): string
{
    return trim(($v['MARCA'] ?? '') . ' ' . ($v['MODELO'] ?? ''));
}

/**
 * Descrição resumida do veículo.
 */
function descricaoVeiculo(array $v): string
{
    $partes = array_filter([
        !empty($v['COR']) ? 'Cor: ' . $v['COR'] : null,
        !empty($v['VALOR']) ? 'Valor: ' . formatarMoeda((float) $v['VALOR']) : null,
        !empty($v['DESCRICAO']) ? 'Obs: ' . $v['DESCRICAO'] : null,
    ]);

    return implode(' · ', $partes);
}

/**
 * Dados do veículo para enviar ao carrinho (JSON).
 */
function dadosVeiculoCarrinho(array $v): string
{
    $id = (int) ($v['ID_PRODUTO'] ?? 0);

    return json_encode([
        'id'     => $id,
        'nome'   => nomeVeiculo($v),
        'marca'  => $v['MARCA'] ?? '',
        'modelo' => $v['MODELO'] ?? '',
        'ano'    => (int) ($v['ANO'] ?? 0),
        'cor'    => $v['COR'] ?? '',
        'descricao' => $v['DESCRICAO'] ?? '',
        'valor'     => (float) ($v['VALOR'] ?? 0),
        'foto'   => fotoVeiculo($id),
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

/**
 * Retorna todas as fotos do produto (carrossel), ordenadas 1, 2, 3…
 *
 * Coloque em: assets/img/produtos/{ID_PRODUTO}/1.jpg, 2.jpg, 3.jpg …
 */
function fotosVeiculo(int $idProduto, string $root = ''): array
{
    if ($idProduto <= 0) {
        return [];
    }

    $pastaProduto = __DIR__ . '/../assets/img/produtos/' . $idProduto . '/';
    $extensoes    = ['jpg', 'jpeg', 'png', 'webp'];
    $fotos        = [];

    if (is_dir($pastaProduto)) {
        for ($i = 1; $i <= 20; $i++) {
            foreach ($extensoes as $ext) {
                if (file_exists($pastaProduto . $i . '.' . $ext)) {
                    $fotos[] = $root . 'assets/img/produtos/' . $idProduto . '/' . $i . '.' . $ext;
                    break;
                }
            }
        }
    }

    if (!empty($fotos)) {
        return $fotos;
    }

    // Compatibilidade: arquivo único assets/img/produtos/{ID}.jpg
    $pastaLegado = __DIR__ . '/../assets/img/produtos/';

    foreach ($extensoes as $ext) {
        if (file_exists($pastaLegado . $idProduto . '.' . $ext)) {
            return [$root . 'assets/img/produtos/' . $idProduto . '.' . $ext];
        }
    }

    return [];
}

/**
 * Retorna a primeira foto do produto ou null se não existir.
 */
function fotoVeiculo(int $idProduto, string $root = ''): ?string
{
    $fotos = fotosVeiculo($idProduto, $root);

    return $fotos[0] ?? null;
}

/**
 * Monta URL da foto com o prefixo correto da página.
 */
function urlFotoVeiculo(?string $foto, string $root = ''): ?string
{
    if (empty($foto)) {
        return null;
    }

    if (str_starts_with($foto, 'http') || str_starts_with($foto, '/')) {
        return $foto;
    }

    return $root . ltrim($foto, './');
}

/**
 * Nome de exibição de um item já salvo no carrinho (sessão).
 */
function nomeItemCarrinho(array $item): string
{
    if (!empty($item['nome'])) {
        return $item['nome'];
    }

    return trim(($item['marca'] ?? '') . ' ' . ($item['modelo'] ?? ''));
}

/**
 * Garante que itens antigos do carrinho tenham todos os campos.
 */
function normalizarItemCarrinho(array $item): array
{
    $item['id']     = (int) ($item['id'] ?? 0);
    $item['nome']   = nomeItemCarrinho($item);
    $item['marca']  = $item['marca']  ?? '';
    $item['modelo'] = $item['modelo'] ?? '';
    $item['ano']    = (int) ($item['ano'] ?? 0);
    $item['cor']       = $item['cor']       ?? '';
    $item['descricao'] = $item['descricao'] ?? '';
    $item['valor']     = (float) ($item['valor'] ?? 0);
    $item['foto']      = $item['foto']      ?? fotoVeiculo($item['id']);

    return $item;
}

/**
 * Mantida apenas para compatibilidade.
 */
function filtrarVeiculosPorPreco(array $veiculos, float $precoMinimo): array
{
    return $veiculos;
}

/**
 * Mantida apenas para compatibilidade.
 */
function calcularTotalCarrinho(array $itens): float
{
    $total = 0.0;

    foreach ($itens as $item) {
        $item = normalizarItemCarrinho($item);
        $total += $item['valor'];
    }

    return $total;
}

/**
 * Formata moeda.
 */
function formatarMoeda(float $valor): string
{
    return 'R$' . number_format($valor, 2, ',', '.');
}

/**
 * Pesquisa por marca ou modelo.
 */
function pesquisarVeiculo(array $veiculos, string $termo): array
{
    if (empty($veiculos) || empty(trim($termo))) {
        return $veiculos;
    }

    $termo = mb_strtolower(trim($termo));

    $resultado = array_filter($veiculos, function ($v) use ($termo) {

        $marca = mb_strtolower($v['MARCA'] ?? '');
        $modelo = mb_strtolower($v['MODELO'] ?? '');

        return str_contains($marca, $termo)
            || str_contains($modelo, $termo);
    });

    return array_values($resultado);
}

/**
 * Valida dados da conta conforme o DER (Pessoa, Contato, Documento, Endereço).
 */
function validarDadosConta(array $dados): array
{
    $erros = [];

    if (empty(trim($dados['nome'] ?? ''))) {
        $erros[] = 'Nome completo é obrigatório.';
    }

    if (empty($dados['data_nascimento'] ?? '')) {
        $erros[] = 'Data de nascimento é obrigatória.';
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dados['data_nascimento'])) {
        $erros[] = 'Data de nascimento inválida.';
    }

    if (empty($dados['sexo'] ?? '')) {
        $erros[] = 'Sexo é obrigatório.';
    }

    if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'E-mail inválido.';
    }

    if (empty(trim($dados['telefone'] ?? ''))) {
        $erros[] = 'Telefone é obrigatório.';
    }

    if (empty($dados['id_tipodoc'] ?? '')) {
        $erros[] = 'Tipo de documento é obrigatório.';
    }

    if (empty(trim($dados['nr_documento'] ?? ''))) {
        $erros[] = 'Número do documento é obrigatório.';
    }

    if (empty(trim($dados['endereco'] ?? ''))) {
        $erros[] = 'Endereço (logradouro) é obrigatório.';
    }

    if (empty(trim($dados['numero'] ?? ''))) {
        $erros[] = 'Número do endereço é obrigatório.';
    }

    if (empty(trim($dados['bairro'] ?? ''))) {
        $erros[] = 'Bairro é obrigatório.';
    }

    if (empty(trim($dados['cep'] ?? ''))) {
        $erros[] = 'CEP é obrigatório.';
    }

    if (empty($dados['id_cidade'] ?? '')) {
        $erros[] = 'Cidade é obrigatória.';
    }

    return $erros;
}

function buscarTiposDocumento(mysqli $conn): array
{
    $result = $conn->query('SELECT ID_TIPODOC, DS_TIPODOC FROM tipo_documento ORDER BY DS_TIPODOC');
    if (!$result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

function buscarEstados(mysqli $conn): array
{
    $result = $conn->query('SELECT ID_ESTADO, DS_ESTADO FROM estado ORDER BY DS_ESTADO');
    if (!$result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

function buscarCidadesPorEstado(mysqli $conn, int $idEstado): array
{
    $stmt = $conn->prepare(
        'SELECT ID_CIDADE, DS_CIDADE FROM cidade WHERE ID_ESTADO = ? ORDER BY DS_CIDADE'
    );
    $stmt->bind_param('i', $idEstado);
    $stmt->execute();

    $cidades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $cidades;
}

function buscarTodasCidades(mysqli $conn): array
{
    $sql = '
        SELECT c.ID_CIDADE, c.DS_CIDADE, c.ID_ESTADO, e.DS_ESTADO
        FROM cidade c
        INNER JOIN estado e ON c.ID_ESTADO = e.ID_ESTADO
        ORDER BY e.DS_ESTADO, c.DS_CIDADE
    ';
    $result = $conn->query($sql);

    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

/**
 * Salva ou atualiza os dados da conta nas tabelas do DER.
 */
function salvarContaNoBanco(mysqli $conn, array $dados): array
{
    $conn->begin_transaction();

    try {
        $idPessoa = (int) ($dados['id_pessoa'] ?? 0);

        if ($idPessoa > 0) {
            $stmtEnd = $conn->prepare(
                'SELECT ID_ENDERECO FROM pessoa WHERE ID_PESSOA = ? LIMIT 1'
            );
            $stmtEnd->bind_param('i', $idPessoa);
            $stmtEnd->execute();
            $row = $stmtEnd->get_result()->fetch_assoc();
            $stmtEnd->close();

            if (!$row) {
                throw new Exception('Pessoa não encontrada para atualização.');
            }

            $idEndereco = (int) $row['ID_ENDERECO'];

            $stmt = $conn->prepare(
                'UPDATE endereco SET DS_ENDERECO=?, NR_ENDERECO=?, BAIRRO=?, NR_CEP=?, ID_CIDADE=? WHERE ID_ENDERECO=?'
            );
            $stmt->bind_param(
                'ssssii',
                $dados['endereco'],
                $dados['numero'],
                $dados['bairro'],
                $dados['cep'],
                $dados['id_cidade'],
                $idEndereco
            );
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare(
                'UPDATE pessoa SET NOME_PESSOA=?, DATA_NASCIMENTO=?, DS_SEXO=? WHERE ID_PESSOA=?'
            );
            $stmt->bind_param(
                'sssi',
                $dados['nome'],
                $dados['data_nascimento'],
                $dados['sexo'],
                $idPessoa
            );
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare(
                'UPDATE contato SET NR_TELEFONE=?, EMAIL=? WHERE ID_PESSOA=?'
            );
            $stmt->bind_param('ssi', $dados['telefone'], $dados['email'], $idPessoa);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare(
                'UPDATE documento SET NR_DOCUMENTO=?, ID_TIPODOC=? WHERE ID_PESSOA=?'
            );
            $stmt->bind_param('sii', $dados['nr_documento'], $dados['id_tipodoc'], $idPessoa);
            $stmt->execute();
            $stmt->close();
        } else {
            $res = $conn->query('SELECT COALESCE(MAX(ID_PESSOA), 0) + 1 AS prox FROM pessoa');
            $proxId = (int) $res->fetch_assoc()['prox'];

            $stmt = $conn->prepare(
                'INSERT INTO endereco (DS_ENDERECO, NR_ENDERECO, BAIRRO, NR_CEP, ID_CIDADE) VALUES (?,?,?,?,?)'
            );
            $stmt->bind_param(
                'ssssi',
                $dados['endereco'],
                $dados['numero'],
                $dados['bairro'],
                $dados['cep'],
                $dados['id_cidade']
            );
            $stmt->execute();
            $idEndereco = (int) $conn->insert_id;
            $stmt->close();

            $stmt = $conn->prepare('INSERT INTO carrinho (ID_PESSOA) VALUES (?)');
            $stmt->bind_param('i', $proxId);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare(
                'INSERT INTO pessoa (ID_PESSOA, NOME_PESSOA, DATA_NASCIMENTO, DS_SEXO, ID_ENDERECO) VALUES (?,?,?,?,?)'
            );
            $stmt->bind_param(
                'isssi',
                $proxId,
                $dados['nome'],
                $dados['data_nascimento'],
                $dados['sexo'],
                $idEndereco
            );
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare(
                'INSERT INTO contato (NR_TELEFONE, EMAIL, ID_PESSOA) VALUES (?,?,?)'
            );
            $stmt->bind_param('ssi', $dados['telefone'], $dados['email'], $proxId);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare(
                'INSERT INTO documento (NR_DOCUMENTO, ID_TIPODOC, ID_PESSOA) VALUES (?,?,?)'
            );
            $stmt->bind_param('sii', $dados['nr_documento'], $dados['id_tipodoc'], $proxId);
            $stmt->execute();
            $stmt->close();

            $conn->query('ALTER TABLE pessoa AUTO_INCREMENT = ' . ($proxId + 1));
            $idPessoa = $proxId;
        }

        $conn->commit();

        return ['success' => true, 'id_pessoa' => $idPessoa];
    } catch (Exception $e) {
        $conn->rollback();

        return ['success' => false, 'erros' => ['Erro ao salvar no banco: ' . $e->getMessage()]];
    }
}

/**
 * Monta mensagem do WhatsApp.
 */
function montarMensagemWhatsApp(array $conta, array $carrinho): string
{
    $msg = "Olá! Tenho interesse nos seguintes veículos:%0A%0A";

    foreach ($carrinho as $item) {
        $item = normalizarItemCarrinho($item);
        $descricao = $item['nome'];

        if (!empty($item['ano'])) {
            $descricao .= ' - ' . $item['ano'];
        }
        if (!empty($item['cor'])) {
            $descricao .= ' (' . $item['cor'] . ')';
        }

        $msg .= '▸ ' . rawurlencode($descricao) . '%0A';
    }

    $msg .= "%0A%0ADados do cliente:%0A";
    $msg .= 'Nome: ' . rawurlencode($conta['nome'] ?? '') . '%0A';
    $msg .= 'E-mail: ' . rawurlencode($conta['email'] ?? '') . '%0A';
    $msg .= 'Telefone: ' . rawurlencode($conta['telefone'] ?? '') . '%0A';

    if (!empty($conta['nr_documento'])) {
        $msg .= 'Documento: ' . rawurlencode($conta['nr_documento']) . '%0A';
    }

    if (!empty($conta['endereco'])) {
        $end = ($conta['endereco'] ?? '') . ', ' . ($conta['numero'] ?? '') .
               ' - ' . ($conta['bairro'] ?? '') . ' - CEP ' . ($conta['cep'] ?? '');
        $msg .= 'Endereço: ' . rawurlencode($end) . '%0A';
    }

    return $msg;
}