<?php
require_once "db.php";
header("Content-Type: application/json");

$cpf = $_GET['cpf'] ?? null;

try {
  if ($cpf) {
    // Remove máscara do CPF digitado
    $cpfLimpo = preg_replace('/\D/', '', $cpf);

    // Usa SQL que remove pontuação do CPF no banco também
    $stmt = $pdo->prepare("
      SELECT * FROM medicamentos_solicitados
      WHERE REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', '') = ?
    ");
    $stmt->execute([$cpfLimpo]);
  } else {
    $stmt = $pdo->query("SELECT * FROM medicamentos_solicitados");
  }

  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
  echo json_encode(["erro" => $e->getMessage()]);
}
?>