<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

// Σε αύξουσα σειρά αναγνώρισης, ώστε το front end να μπορεί να σελιδοποιήσει/ταξινομήσει σωστά
$stmt = $pdo->query('SELECT * FROM cards ORDER BY id ASC');
echo json_encode($stmt->fetchAll());
?>
