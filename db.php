<?php
$host = "containers-us-west-42.railway.app"; // substitua pelo host do Railway
$db   = "railway";                           // nome do banco gerado no Railway
$user = "usuario";                           // usuário do Railway
$pass = "senha";                             // senha do Railway
$port = "0000";                              // porta do banco (geralmente 5432 ou outra informada)

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
