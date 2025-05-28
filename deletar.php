<?php
header("Content-Type: application/json");
require 'db.php';

// Lê o corpo da requisição (JSON)
$input = json_decode(file_get_contents("php://input"), true);

// Verifica se o ID foi enviado
if (!isset($input['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "erro", "mensagem" => "ID não enviado"]);
    exit;
}

$id = intval($input['id']);

try {
    $stmt = $pdo->prepare("DELETE FROM medicamentos_solicitados WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(["status" => "sucesso", "mensagem" => "Registro deletado com sucesso"]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao deletar: " . $e->getMessage()]);
}
