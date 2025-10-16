<?php
declare(strict_types=1);
require_once 'db.php';

header('Content-Type: application/json; charset=utf-8');

try {
    // φρόντισε στο db.php:
    // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'msg' => 'Άγνωστο ID κάρτας']);
        exit;
    }

    $pdo->beginTransaction();

    $stmt = $pdo->prepare('DELETE FROM cards WHERE id = ?');
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        // δεν βρέθηκε εγγραφή — κάνε rollback για καθαρότητα
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['success' => false, 'msg' => 'Δεν βρέθηκε κάρτα']);
        exit;
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'msg' => 'Η κάρτα διαγράφηκε επιτυχώς']);
} catch (Throwable $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Προσοχή: σε production μην εκθέτεις $e->getMessage()
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => 'Σφάλμα διαγραφής']);
    // error_log για server logs
    error_log('card_delete error: ' . $e->getMessage());
}
