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

    // Buscar categorias
    $categorias = $pdo->query(
        "SELECT * FROM categorias ORDER BY id"
    )->fetchAll(PDO::FETCH_ASSOC);

    // Buscar transações
    $transacoes = $pdo->query(
        "SELECT 
            t.id,
            t.descricao,
            t.valor,
            t.tipo,
            c.nome AS categoria,
            t.data_transacao
         FROM transacoes t
         INNER JOIN categorias c ON t.categoria_id = c.id
         ORDER BY t.id"
    )->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro na conexão com o banco: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Controle Financeiro</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h1 {
            margin-bottom: 30px;
        }

        h2 {
            margin-top: 35px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <h1>Controle Financeiro</h1>

    <!-- TABELA DE CATEGORIAS -->

    <h2>Categorias</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cor</th>
            <th>Tipo</th>
        </tr>

        <?php foreach ($categorias as $categoria): ?>

            <tr>
                <td><?= $categoria['id'] ?></td>

                <td>
                    <?= htmlspecialchars($categoria['nome']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($categoria['cor']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($categoria['tipo']) ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>


    <!-- TABELA DE TRANSAÇÕES -->

    <h2>Transações</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Valor</th>
            <th>Tipo</th>
            <th>Categoria</th>
            <th>Data</th>
        </tr>

        <?php foreach ($transacoes as $transacao): ?>

            <tr>
                <td><?= $transacao['id'] ?></td>

                <td>
                    <?= htmlspecialchars($transacao['descricao']) ?>
                </td>

                <td>
                    R$ <?= number_format($transacao['valor'], 2, ',', '.') ?>
                </td>

                <td>
                    <?= htmlspecialchars($transacao['tipo']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($transacao['categoria']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($transacao['data_transacao']) ?>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

</body>

</html>