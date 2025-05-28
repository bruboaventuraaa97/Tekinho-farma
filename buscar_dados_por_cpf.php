<?php
header("Content-Type: application/json");
require_once "db.php";

$input = json_decode(file_get_contents("php://input"), true);
$cpf = $input["cpf"] ?? '';

try {
  $stmt = $pdo->prepare("SELECT nome, endereco, titulo_eleitoral, zona_eleitoral 
                         FROM medicamentos_solicitados 
                         WHERE cpf = :cpf 
                         ORDER BY id DESC LIMIT 1");
  $stmt->execute([":cpf" => $cpf]);
  $dados = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($dados) {
    echo json_encode(["status" => "sucesso", "dados" => $dados]);
  } else {
    echo json_encode(["status" => "nao_encontrado"]);
  }
} catch (PDOException $e) {
  echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}
