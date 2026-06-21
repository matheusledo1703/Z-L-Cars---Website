<?php
// ============================================================
// CONFIGURAÇÃO DO BANCO DE DADOS
// Altere os valores abaixo para conectar à sua máquina virtual
// ============================================================

define('DB_HOST', 'sql107.ezyro.com');  // <<< IP DA SUA MÁQUINA VIRTUAL
define('DB_USER', 'ezyro_42232347');          // <<< Usuário do MySQL
define('DB_PASS', 'matheus');              // <<< Senha do MySQL
define('DB_NAME', 'ezyro_42232347_zlcars');     // <<< Nome do banco

function conectarBD() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if ($conn->connect_error) {
        die("<div class='alert alert-danger text-center m-4'>
                <strong>Erro de conexão:</strong> " . $conn->connect_error . "
             </div>");
    }

    $conn->set_charset("utf8");
    return $conn;
}
?>
