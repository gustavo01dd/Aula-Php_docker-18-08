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

        header("Location: ../index.php?sucesso=Categoria atualizada com sucesso!");
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

        <select name="nome" required>

            <option value="">Selecione uma categoria</option>

            <option value="Alimentação"
                <?= $categoria['nome'] === 'Alimentação' ? 'selected' : '' ?>>
                Alimentação
            </option>

            <option value="Transporte"
                <?= $categoria['nome'] === 'Transporte' ? 'selected' : '' ?>>
                Transporte
            </option>

            <option value="Moradia"
                <?= $categoria['nome'] === 'Moradia' ? 'selected' : '' ?>>
                Moradia
            </option>

            <option value="Saúde"
                <?= $categoria['nome'] === 'Saúde' ? 'selected' : '' ?>>
                Saúde
            </option>

            <option value="Educação"
                <?= $categoria['nome'] === 'Educação' ? 'selected' : '' ?>>
                Educação
            </option>

            <option value="Lazer"
                <?= $categoria['nome'] === 'Lazer' ? 'selected' : '' ?>>
                Lazer
            </option>

            <option value="Salário"
                <?= $categoria['nome'] === 'Salário' ? 'selected' : '' ?>>
                Salário
            </option>

            <option value="Freelance"
                <?= $categoria['nome'] === 'Freelance' ? 'selected' : '' ?>>
                Freelance
            </option>

            <option value="Investimentos"
                <?= $categoria['nome'] === 'Investimentos' ? 'selected' : '' ?>>
                Investimentos
            </option>

            <option value="Outros"
                <?= $categoria['nome'] === 'Outros' ? 'selected' : '' ?>>
                Outros
            </option>

        </select>

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