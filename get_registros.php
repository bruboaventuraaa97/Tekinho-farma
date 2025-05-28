<?php


require_once "db.php";
header("Content-Type: application/json");

$cpf = $_GET['cpf'] ?? null;

try {
  if ($cpf) {
    // Remove máscara (por segurança)
    $cpf = preg_replace('/\D/', '', $cpf);

    $stmt = $pdo->prepare("SELECT * FROM medicamentos_solicitados WHERE REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', '') = ?");
    $stmt->execute([$cpf]);
  } else {
    $stmt = $pdo->query("SELECT * FROM medicamentos_solicitados");
  }

  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
  echo json_encode(["erro" => $e->getMessage()]);
}

?>
