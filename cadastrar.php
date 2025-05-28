<?php
header("Content-Type: application/json");
require_once "db.php";

$input = json_decode(file_get_contents("php://input"), true);

try {
  if (isset($input['id']) && !empty($input['id'])) {
    // Atualizar
    $stmt = $pdo->prepare("UPDATE medicamentos_solicitados SET
      cpf = :cpf,
      nome = :nome,
      endereco = :endereco,
      titulo_eleitoral = :titulo,
      zona_eleitoral = :zona,
      nome_medicamento = :medicamento,
      data_solicitacao = :data
      WHERE id = :id");

    $stmt->execute([
      ':cpf' => $input['cpf'],
      ':nome' => $input['nome'],
      ':endereco' => $input['endereco'],
      ':titulo' => $input['titulo_eleitoral'],
      ':zona' => $input['zona_eleitoral'],
      ':medicamento' => $input['nome_medicamento'],
      ':data' => $input['data_solicitacao'],
      ':id' => $input['id']
    ]);

    echo json_encode(["status" => "sucesso", "acao" => "atualizado"]);
  } else {
    // Inserir
    $stmt = $pdo->prepare("INSERT INTO medicamentos_solicitados (
      cpf, nome, endereco, titulo_eleitoral, zona_eleitoral, nome_medicamento, data_solicitacao
    ) VALUES (
      :cpf, :nome, :endereco, :titulo, :zona, :medicamento, :data
    )");

    $stmt->execute([
      ':cpf' => $input['cpf'],
      ':nome' => $input['nome'],
      ':endereco' => $input['endereco'],
      ':titulo' => $input['titulo_eleitoral'],
      ':zona' => $input['zona_eleitoral'],
      ':medicamento' => $input['nome_medicamento'],
      ':data' => $input['data_solicitacao']
    ]);

    echo json_encode(["status" => "sucesso", "acao" => "inserido"]);
  }
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["status" => "erro", "mensagem" => $e->getMessage()]);
}
?>