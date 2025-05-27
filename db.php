<?php
$host = "postgres.railway.internal";  // Host público mostrado no Railway
$port = "5432";                   // Porta externa informada
$db   = "railway";                 // Nome do banco (padrão se não tiver mudado)
$user = "postgres";               // Usuário padrão (confirme se estiver diferente)
$pass = "QFwpLgxmzRybvZjjSgiEfmuuXJVJWOyl";         // ✅ COPIE da aba "Variables" no Railway (ou "Connect")

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "erro", "mensagem" => "Erro de conexão: " . $e->getMessage()]);
    exit;
}
