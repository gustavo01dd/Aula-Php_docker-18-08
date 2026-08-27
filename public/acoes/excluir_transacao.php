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

    $stmt = $pdo->prepare(
        "DELETE FROM transacoes WHERE id = :id"
    );

    $stmt->execute([
        ':id' => $id
    ]);

    header("Location: ../index.php");
    exit;

} catch (PDOException $e) {

    die("Erro ao excluir: " . $e->getMessage());

}

?>