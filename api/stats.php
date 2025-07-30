<?php
declare(strict_types=1);

require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

try {
    $stmt = $pdo->prepare(
        'SELECT
            COUNT(*)               AS total_cards,
            COALESCE(SUM(price),0) AS total_sales,
            SUM(CASE WHEN type = :partner THEN 1 ELSE 0 END) AS partner_cards,
            SUM(CASE WHEN type = :client  THEN 1 ELSE 0 END) AS client_cards
         FROM cards'
    );
    $stmt->execute([
        ':partner' => 'Συνεργάτης',
        ':client'  => 'Πελάτης'
    ]);
    $data = $stmt->fetch();

    echo json_encode([
        'total_cards'   => (int) $data['total_cards'],
        'total_sales'   => number_format((float) $data['total_sales'], 2, '.', ''),
        'partner_cards' => (int) $data['partner_cards'],
        'client_cards'  => (int) $data['client_cards']
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => 'Αποτυχία φόρτωσης στατιστικών']);
    exit;
}
