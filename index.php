<?php 
header("Content-Type: application/json");

require 'conecta.php';

#SuperGlobais
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo == 'POST') {
    $json = file_get_contents("php://input");
    $dados = json_decode($json, true);

    $prioridade = strtolower($dados['prioridade']);
    $status = strtolower($dados['status']);

    if (!in_array($prioridade, ['baixa', 'media', 'alta'], true)) {
        http_response_code(400);
        echo json_encode(["Mensagem" => "ERRO: prioridade deve ser baixa, media ou alta"]);
        exit;
    } else if (!in_array($status, ['aberto', 'em andamento', 'concluido'], true)) {
        http_response_code(400);
        echo json_encode(["Mensagem" => "ERRO: status deve ser aberto, em andamento ou concluido"]);
        exit;
    } else {
        $sql = "INSERT INTO chamados (equipamento, setor, descricao, prioridade, status) VALUES (?,?,?,?,?)";

        $comando = $pdo->prepare($sql);

        $comando->execute([
            $dados['equipamento'],
            $dados['setor'],
            $dados['descricao'],
            $prioridade,
            $status
        ]);

        http_response_code(201);
        echo json_encode(["Mensagem" => "Chamado cadastrado com sucesso!"]);
        exit;
    }
}

if ($metodo == "GET"){
    $sql = "SELECT * FROM chamados";

    $comando = $pdo -> query($sql);

    $chamados = $comando -> fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($chamados);
};

# Se o método for PUT (Atualizar)
if ($metodo == 'PUT') {
    $json = file_get_contents("php://input");
    $dados = json_decode($json, true);

    $prioridade = strtolower($dados['prioridade']);
    $status = strtolower($dados['status']);

    if (!in_array($prioridade, ['baixa', 'media', 'alta'], true)) {
        http_response_code(400);
        echo json_encode(["Mensagem" => "ERRO: prioridade deve ser baixa, media ou alta"]);
        exit;
    } else if (!in_array($status, ['aberto', 'em andamento', 'concluido'], true)) {
        http_response_code(400);
        echo json_encode(["Mensagem" => "ERRO: status deve ser aberto, em andamento ou concluido"]);
        exit;
    } else {
        $sql = "UPDATE chamados SET equipamento=?,setor=?,descricao=?,prioridade=?,status=? WHERE id = ?";

        $comando = $pdo->prepare($sql);

        $comando->execute([
            $dados['equipamento'],
            $dados['setor'],
            $dados['descricao'],
            $prioridade,
            $status,
            $dados['id']
        ]);

        http_response_code(201);
        echo json_encode(["Mensagem"=>"Dados atualizados com sucesso!"]);
        exit;
    }
}

# Se o método for DELETE
if ($metodo == "DELETE"){
    $json = file_get_contents("php://input");

    $dados = json_decode($json,true);

    $sql = "DELETE FROM chamados WHERE id = ?";

    $comando = $pdo -> prepare($sql);

    $comando -> execute([
        $dados['id']
    ]);

    echo json_encode(["Mensagem"=>"Chamado excluído com sucesso!"]);
};
?>