
// ============================================================
// FUNÇÕES DE NEGÓCIO - Z&L Cars
// ============================================================

/**
 * Busca todos os veículos de uma categoria no banco de dados.
 */
<?php

/**
 * Busca produtos por categoria.
 * Categorias válidas:
 * CARRO
 * MOTO
 * CAMINHONETE
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
            p.PLACA,
            p.KM,
            p.DEFEITOS,
            tp.DS_TIPO
        FROM PRODUTO p
        INNER JOIN TIPO_PRODUTO tp
            ON p.ID_TIPO_PRODUTO = tp.ID_TIPO_PRODUTO
        WHERE tp.DS_TIPO = ?
        ORDER BY p.MODELO ASC
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
    return 0;
}

/**
 * Formata moeda.
 */
function formatarMoeda(float $valor): string
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
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
 * Valida dados da conta.
 */
function validarDadosConta(array $dados): array
{
    $erros = [];

    if (empty(trim($dados['nome'] ?? ''))) {
        $erros[] = "Nome é obrigatório.";
    }

    if (
        empty($dados['email']) ||
        !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)
    ) {
        $erros[] = "E-mail inválido.";
    }

    if (empty(trim($dados['telefone'] ?? ''))) {
        $erros[] = "Telefone é obrigatório.";
    }

    return $erros;
}

/**
 * Monta mensagem do WhatsApp.
 */
function montarMensagemWhatsApp(array $conta, array $carrinho): string
{
    $msg = "Olá! Tenho interesse nos seguintes veículos:%0A%0A";

    foreach ($carrinho as $item) {

        $descricao =
            ($item['MARCA'] ?? '') . ' ' .
            ($item['MODELO'] ?? '') . ' - ' .
            ($item['ANO'] ?? '');

        $msg .= "▸ " . urlencode($descricao) . "%0A";
    }

    $msg .= "%0A%0ADados do cliente:%0A";
    $msg .= "Nome: " . urlencode($conta['nome'] ?? '') . "%0A";
    $msg .= "E-mail: " . urlencode($conta['email'] ?? '') . "%0A";
    $msg .= "Telefone: " . urlencode($conta['telefone'] ?? '');

    return $msg;
}
?>