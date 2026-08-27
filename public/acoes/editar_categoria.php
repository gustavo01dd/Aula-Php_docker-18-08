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

    // Pegar o ID da categoria
    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Categoria não encontrada.");
    }

    // Salvar alteração
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = $_POST['nome'];
        $cor = $_POST['cor'];
        $tipo = $_POST['tipo'];

        $sql = "UPDATE categorias
                SET nome = :nome,
                    cor = :cor,
                    tipo = :tipo
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $nome,
            ':cor' => $cor,
            ':tipo' => $tipo,
            ':id' => $id
        ]);

        header("Location: ../index.php");
        exit;
    }

    // Buscar categoria atual
    $stmt = $pdo->prepare(
        "SELECT * FROM categorias WHERE id = :id"
    );

    $stmt->execute([':id' => $id]);

    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categoria) {
        die("Categoria não encontrada.");
    }

} catch (PDOException $e) {

    die("Erro no banco: " . $e->getMessage());

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <h1>Editar Categoria</h1>

    <form method="POST">

        <label>Nome:</label>
        <input
            type="text"
            name="nome"
            value="<?= htmlspecialchars($categoria['nome']) ?>"
            required
        >

        <br><br>

        <label>Cor:</label>
        <input
            type="text"
            name="cor"
            value="<?= htmlspecialchars($categoria['cor']) ?>"
            required
        >

        <br><br>

        <label>Tipo:</label>

        <select name="tipo" required>

            <option
                value="receita"
                <?= $categoria['tipo'] === 'receita' ? 'selected' : '' ?>
            >
                Receita
            </option>

            <option
                value="despesa"
                <?= $categoria['tipo'] === 'despesa' ? 'selected' : '' ?>
            >
                Despesa
            </option>

        </select>

        <br><br>

        <button type="submit">
            Salvar Alterações
        </button>

    </form>

    <br>

    <a href="../index.php">Voltar</a>

</body>

</html>