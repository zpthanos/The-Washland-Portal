<?php
declare(strict_types=1);

require_once 'db.php';
header('Content-Type: application/json; charset=utf-8');

$data = filter_input_array(INPUT_POST, [
    'id'            => FILTER_VALIDATE_INT,
    'card_code'     => FILTER_SANITIZE_STRING,
    'fullname'      => FILTER_SANITIZE_STRING,
    'description'   => FILTER_UNSAFE_RAW,
    'purchase_date' => FILTER_SANITIZE_STRING,
    'type'          => FILTER_SANITIZE_STRING,
    'price'         => [
        'filter'  => FILTER_VALIDATE_FLOAT,
        'options' => ['decimal' => '.']
    ],
]);

if (!$data['id']) {
    http_response_code(400);
    echo json_encode(['success' => false, 'msg' => 'Ο κωδικός κάρτας δεν υπάρχει']);
    exit;
}

$required = ['card_code', 'fullname', 'purchase_date', 'type', 'price'];
foreach ($required as $field) {
    if (!isset($data[$field]) || $data[$field] === false || trim((string)$data[$field]) === '') {
        http_response_code(422);
        echo json_encode(['success' => false, 'msg' => 'Συμπληρώστε όλα τα υποχρεωτικά πεδία']);
        exit;
    }
}

$validTypes = ['Συνεργάτης', 'Πελάτης'];
if (!in_array($data['type'], $validTypes, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'msg' => 'Μη έγκυρο είδος κάρτας']);
    exit;
}

$date = DateTime::createFromFormat('Y-m-d', $data['purchase_date']);
if (!$date || $date->format('Y-m-d') !== $data['purchase_date']) {
    http_response_code(422);
    echo json_encode(['success' => false, 'msg' => 'Μη έγκυρη ημερομηνία']);
    exit;
}

$price = (float) $data['price'];
if ($price < 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'msg' => 'Η τιμή πρέπει να είναι θετικός αριθμός']);
    exit;
}

try {
    $sql = 'UPDATE cards
            SET card_code     = :code,
                fullname      = :name,
                description   = :desc,
                purchase_date = :date,
                type          = :type,
                price         = :price
            WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':code'  => $data['card_code'],
        ':name'  => $data['fullname'],
        ':desc'  => $data['description'] ?? '',
        ':date'  => $data['purchase_date'],
        ':type'  => $data['type'],
        ':price' => number_format($price, 2, '.', ''),
        ':id'    => $data['id'],
    ]);

    echo json_encode(['success' => true, 'msg' => 'Η κάρτα ενημερώθηκε επιτυχώς']);
} catch (PDOException $e) {
    $msg = $e->getCode() === '23000'
        ? 'Ο κωδικός κάρτας υπάρχει ήδη'
        : 'Σφάλμα βάσης';
    http_response_code(500);
    echo json_encode(['success' => false, 'msg' => $msg]);
    exit;
}
