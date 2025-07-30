<?php
declare(strict_types=1);
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'msg' => 'Άγνωστο ID κάρτας']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('DELETE FROM cards WHERE id = ?');
    $stmt->execute([$id]);

    // Resequence IDs to be contiguous
    $pdo->exec('SET @n := 0');
    $pdo->exec('UPDATE cards SET id = (@n := @n + 1) ORDER BY id ASC');
    $pdo->exec('ALTER TABLE cards AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM (SELECT * FROM cards) AS t)');

    $pdo->commit();
    echo json_encode(['success' => true, 'msg' => 'Η κάρτα διαγράφηκε επιτυχώς']);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => 'Σφάλμα διαγραφής']);
    exit;
}
