<?php
require 'db.php'; // Arquivo com a conexão PDO

// Lê os dados enviados via JSON
$input = json_decode(file_get_contents("php://input"), true);

// Verifica se os dados estão presentes
if ($input) {
    $stmt = $pdo->prepare("INSERT INTO medicamentos_solicitados 
        (cpf, nome, endereco, titulo_eleitoral, zona_eleitoral, nome_medicamento, data_solicitacao) 
        VALUES (:cpf, :nome, :endereco, :titulo, :zona, :medicamento, :data)");

    $stmt->execute([
        ":cpf"         => $input['cpf'],
        ":nome"        => $input['nome'],
        ":endereco"    => $input['endereco'],
        ":titulo"      => $input['titulo_eleitoral'],
        ":zona"        => $input['zona_eleitoral'],
        ":medicamento" => $input['nome_medicamento'],
        ":data"        => $input['data_solicitacao']
    ]);

    echo json_encode(["status" => "sucesso", "mensagem" => "Medicamento solicitado com sucesso."]);
} else {
    http_response_code(400);
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos"]);
}
