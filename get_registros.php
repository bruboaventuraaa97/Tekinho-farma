<?php
require_once "db.php"; // Inclui a conexão

try {
    $stmt = $pdo->query("SELECT * FROM medicamentos_solicitados ORDER BY id DESC");
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($dados);
} catch (PDOException $e) {
    echo json_encode(["erro" => $e->getMessage()]);
}
?>
