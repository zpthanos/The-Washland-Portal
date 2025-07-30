<?php
require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

// Return cards in ascending ID order so the front end can paginate/sort correctly
$stmt = $pdo->query('SELECT * FROM cards ORDER BY id ASC');
echo json_encode($stmt->fetchAll());
?>
