<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
header('Content-Type: application/json; charset=utf-8');

class ValidationException extends \Exception {}


function respond(bool $success, string $msg): void {
    echo json_encode(['success' => $success, 'msg' => $msg], JSON_UNESCAPED_UNICODE);
    exit;
}


function validateCardData(array $data): void {
    $required = ['card_code', 'fullname', 'purchase_date', 'type', 'price'];
    // 1. Required fields
    foreach ($required as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === '') {
            throw new ValidationException('Συμπληρώστε όλα τα υποχρεωτικά πεδία');
        }
    }

  
    $allowedTypes = ['Συνεργάτης', 'Πελάτης'];
    if (!in_array($data['type'], $allowedTypes, true)) {
        throw new ValidationException('Μη έγκυρο είδος κάρτας');
    }

   
    if (!is_numeric($data['price'])) {
        throw new ValidationException('Η τιμή πρέπει να είναι αριθμός');
    }

    
}

try {
 
    validateCardData($_POST);

   
    $stmt = $pdo->prepare(
        'INSERT INTO cards
            (card_code, fullname, description, purchase_date, type, price)
         VALUES
            (?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $_POST['card_code'],
        $_POST['fullname'],
        $_POST['description'] ?? '',
        $_POST['purchase_date'],
        $_POST['type'],
        $_POST['price']
    ]);

    
    respond(true, 'Η κάρτα προστέθηκε επιτυχώς');

} catch (ValidationException $ve) {
    
    respond(false, $ve->getMessage());

} catch (PDOException $pe) {
    
    $msg = ($pe->getCode() === '23000')
         ? 'Ο κωδικός κάρτας υπάρχει ήδη'
         : 'Σφάλμα βάσης';
    respond(false, $msg);
}
