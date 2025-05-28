<?php
header("Content-Type: application/json");
require_once "db.php";

$input = json_decode(file_get_contents("php://input"), true);
$cpf = preg_replace('/\D/', '', $input['cpf'] ?? '');

try {
  $stmt = $pdo->prepare("SELECT * FROM medicamentos_solicitados WHERE REPLACE(REPLACE(REPLACE(cpf, '.', ''), '-', ''), ' ', '') = ?");
  $stmt->execute([$cpf]);

  $dados = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($dados) {
    echo json_encode($dados);
  } else {
    echo json_encode([]); // Retorna JSON vazio se não encontrar
  }
} catch (PDOException $e) {
  echo json_encode(["erro" => $e->getMessage()]);
}
?>