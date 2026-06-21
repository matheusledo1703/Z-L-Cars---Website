<?php
// =====================================================.=======
// CONFIGURAÇÃO DO BANCO DE DADOS
// Altere os valores abaixo para conectar à sua máquina virtual
// ============================================================

define('DB_HOST', '192.168.56.102');   // XAMPP local
define('DB_USER', 'matheus');        // Usuário padrão do XAMPP
define('DB_PASS', 'matheus');            // Senha padrão do XAMPP (vazia)
define('DB_NAME', 'z&l cars');

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
