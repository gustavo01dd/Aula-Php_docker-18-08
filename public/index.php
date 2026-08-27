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

    // Calcular total de receitas
$totalReceitas = $pdo->query(
    "SELECT COALESCE(SUM(valor), 0) FROM transacoes WHERE tipo = 'receita'"
)->fetchColumn();

// Calcular total de despesas
$totalDespesas = $pdo->query(
    "SELECT COALESCE(SUM(valor), 0) FROM transacoes WHERE tipo = 'despesa'"
)->fetchColumn();

// Calcular saldo
$saldo = $totalReceitas - $totalDespesas;

    // Adicionar categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_categoria'])) {

    $nome = $_POST['nome'];
    $cor = $_POST['cor'];
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO categorias (nome, cor, tipo)
            VALUES (:nome, :cor, :tipo)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':nome' => $nome,
        ':cor' => $cor,
        ':tipo' => $tipo
    ]);

    header("Location: index.php?sucesso=Categoria adicionada com sucesso!");
    exit;
}

    // Adicionar transação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_transacao'])) {

    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $tipo = $_POST['tipo'];
    $categoria_id = $_POST['categoria_id'];
    $data_transacao = $_POST['data_transacao'];

    $sql = "INSERT INTO transacoes
            (descricao, valor, tipo, categoria_id, data_transacao)
            VALUES
            (:descricao, :valor, :tipo, :categoria_id, :data_transacao)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':descricao' => $descricao,
        ':valor' => $valor,
        ':tipo' => $tipo,
        ':categoria_id' => $categoria_id,
        ':data_transacao' => $data_transacao
    ]);

    header("Location: index.php?sucesso=Transação adicionada com sucesso!");
    exit;
}

    // Buscar categorias
    $categorias = $pdo->query(
        "SELECT * FROM categorias ORDER BY nome"
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
    <link rel="stylesheet" href="style.css">

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

<?php if (isset($_GET['sucesso'])): ?>

    <div class="mensagem sucesso">
        <?= htmlspecialchars($_GET['sucesso']) ?>
    </div>

<?php endif; ?>

<div class="cards">

    <div class="card receita">
        <h3>💰 Receitas</h3>

        <p>
            R$ <?= number_format($totalReceitas, 2, ',', '.') ?>
        </p>
    </div>

    <div class="card despesa">
        <h3>💸 Despesas</h3>

        <p>
            R$ <?= number_format($totalDespesas, 2, ',', '.') ?>
        </p>
    </div>

    <div class="card saldo">
        <h3>📊 Saldo</h3>

        <p>
            R$ <?= number_format($saldo, 2, ',', '.') ?>
        </p>
    </div>

</div>

    <h1>Controle Financeiro</h1>

    <h2>Adicionar Categoria</h2>

<form method="POST">

    <select name="nome" required>
        <option value="">Selecione uma categoria</option>
        <option value="Alimentação">Alimentação</option>
        <option value="Transporte">Transporte</option>
        <option value="Moradia">Moradia</option>
        <option value="Saúde">Saúde</option>
        <option value="Educação">Educação</option>
        <option value="Lazer">Lazer</option>
        <option value="Salário">Salário</option>
        <option value="Freelance">Freelance</option>
        <option value="Investimentos">Investimentos</option>
        <option value="Outros">Outros</option>
    </select>

    <input
        type="text"
        name="cor"
        placeholder="#28a745"
        required
    >

    <select name="tipo" required>
        <option value="receita">Receita</option>
        <option value="despesa">Despesa</option>
    </select>

    <button type="submit" name="adicionar_categoria">
        Adicionar
    </button>

</form>

    <!-- TABELA DE CATEGORIAS -->

    <h2>Categorias</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Cor</th>
            <th>Tipo</th>
            <th>Ações</th>
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

                <td>
                    <a href="acoes/editar_categoria.php?id=<?= $categoria['id'] ?>">
                    Editar
                    </a>

                |

                    <a
                        href="acoes/excluir_categoria.php?id=<?= $categoria['id'] ?>"
                        onclick="return confirm('Tem certeza que deseja excluir esta categoria?')"
                        >
                        Excluir
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

    <h2>Adicionar Transação</h2>

<form method="POST">

    <label>Descrição:</label>
    <input
        type="text"
        name="descricao"
        required
    >

    <br><br>

    <label>Valor:</label>
    <input
        type="number"
        name="valor"
        step="0.01"
        min="0"
        required
    >

    <br><br>

    <label>Tipo:</label>
    <select name="tipo" required>
        <option value="">Selecione o tipo</option>
        <option value="receita">Receita</option>
        <option value="despesa">Despesa</option>
    </select>

    <br><br>

    <label>Categoria:</label>
    <select name="categoria_id" required>
        <option value="">Selecione uma categoria</option>

        <?php foreach ($categorias as $categoria): ?>

            <option value="<?= $categoria['id'] ?>">
                <?= htmlspecialchars($categoria['nome']) ?>
            </option>

        <?php endforeach; ?>

    </select>

    <br><br>

    <label>Data:</label>
    <input
        type="date"
        name="data_transacao"
        required
    >

    <br><br>

    <button type="submit" name="adicionar_transacao">
        Adicionar Transação
    </button>

</form>

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
            <th>Ações</th>
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

                <td>
                    <a href="acoes/editar_transacao.php?id=<?= $transacao['id'] ?>">
                    Editar
                    </a>

                |

                    <a
                        href="acoes/excluir_transacao.php?id=<?= $transacao['id'] ?>"
                        onclick="return confirm('Tem certeza que deseja excluir esta transação?')"
                        >
                        Excluir
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>
</body>

</html>