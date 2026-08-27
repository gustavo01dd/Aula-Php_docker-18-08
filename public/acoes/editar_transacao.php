<?php

$host = 'aula_mariadb';
$db = 'controle_financeiro';
$user = 'aula_user';
$pass = 'aula_pass';

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Transação não encontrada.");
    }

    // Salvar alteração
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];
        $tipo = $_POST['tipo'];
        $categoria_id = $_POST['categoria_id'];
        $data_transacao = $_POST['data_transacao'];

        $sql = "UPDATE transacoes
                SET descricao = :descricao,
                    valor = :valor,
                    tipo = :tipo,
                    categoria_id = :categoria_id,
                    data_transacao = :data_transacao
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':descricao' => $descricao,
            ':valor' => $valor,
            ':tipo' => $tipo,
            ':categoria_id' => $categoria_id,
            ':data_transacao' => $data_transacao,
            ':id' => $id
        ]);

        header("Location: ../index.php");
        exit;
    }

    // Buscar transação
    $stmt = $pdo->prepare(
        "SELECT * FROM transacoes WHERE id = :id"
    );

    $stmt->execute([':id' => $id]);

    $transacao = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$transacao) {
        die("Transação não encontrada.");
    }

    // Buscar categorias
    $categorias = $pdo->query(
        "SELECT * FROM categorias ORDER BY nome"
    )->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Erro no banco: " . $e->getMessage());

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Transação</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <h1>Editar Transação</h1>

    <form method="POST">

        <label>Descrição:</label>

        <input
            type="text"
            name="descricao"
            value="<?= htmlspecialchars($transacao['descricao']) ?>"
            required
        >

        <br><br>

        <label>Valor:</label>

        <input
            type="number"
            name="valor"
            step="0.01"
            value="<?= htmlspecialchars($transacao['valor']) ?>"
            required
        >

        <br><br>

        <label>Tipo:</label>

        <select name="tipo" required>

            <option
                value="receita"
                <?= $transacao['tipo'] === 'receita' ? 'selected' : '' ?>
            >
                Receita
            </option>

            <option
                value="despesa"
                <?= $transacao['tipo'] === 'despesa' ? 'selected' : '' ?>
            >
                Despesa
            </option>

        </select>

        <br><br>

        <label>Categoria:</label>

        <select name="categoria_id" required>

            <?php foreach ($categorias as $categoria): ?>

                <option
                    value="<?= $categoria['id'] ?>"
                    <?= $transacao['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

        <label>Data:</label>

        <input
            type="date"
            name="data_transacao"
            value="<?= htmlspecialchars($transacao['data_transacao']) ?>"
            required
        >

        <br><br>

        <button type="submit">
            Salvar Alterações
        </button>

    </form>

    <br>

    <a href="../index.php">Voltar</a>

</body>

</html>