<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

final class ValidationException extends RuntimeException
{
}

function respond(int $status, bool $success, string $message): void
{
    http_response_code($status);
    echo json_encode(
        ['success' => $success, 'msg' => $message],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}

function validateCardData(array $data): array
{
    $required = ['card_code', 'fullname', 'purchase_date', 'type', 'price'];

    foreach ($required as $field) {
        if (!isset($data[$field]) || !is_string($data[$field]) || trim($data[$field]) === '') {
            throw new ValidationException('Συμπληρώστε όλα τα υποχρεωτικά πεδία');
        }
    }

    $cardCode = trim($data['card_code']);
    $fullname = trim($data['fullname']);
    $description = isset($data['description']) && is_string($data['description'])
        ? trim($data['description'])
        : '';
    $purchaseDate = trim($data['purchase_date']);
    $type = trim($data['type']);
    $priceInput = trim($data['price']);

    if (strlen($cardCode) > 255 || strlen($fullname) > 255) {
        throw new ValidationException('Ο κωδικός και το ονοματεπώνυμο πρέπει να είναι έως 255 χαρακτήρες');
    }

    $allowedTypes = ['Συνεργάτης', 'Πελάτης'];
    if (!in_array($type, $allowedTypes, true)) {
        throw new ValidationException('Μη έγκυρο είδος κάρτας');
    }

    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $purchaseDate);
    $dateErrors = DateTimeImmutable::getLastErrors();
    $dateIsValid = $date !== false
        && ($dateErrors === false || ($dateErrors['warning_count'] === 0 && $dateErrors['error_count'] === 0))
        && $date->format('Y-m-d') === $purchaseDate;

    if (!$dateIsValid) {
        throw new ValidationException('Η ημερομηνία πρέπει να έχει έγκυρη μορφή YYYY-MM-DD');
    }

    if (!preg_match('/^\d{1,8}(?:\.\d{1,2})?$/', $priceInput)) {
        throw new ValidationException('Η τιμή πρέπει να είναι από 0 έως 99999999.99 με έως δύο δεκαδικά');
    }

    $price = (float) $priceInput;
    if ($price < 0 || $price > 99999999.99) {
        throw new ValidationException('Η τιμή πρέπει να είναι από 0 έως 99999999.99');
    }

    return [
        'card_code' => $cardCode,
        'fullname' => $fullname,
        'description' => $description,
        'purchase_date' => $purchaseDate,
        'type' => $type,
        'price' => number_format($price, 2, '.', ''),
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, false, 'Η μέθοδος δεν επιτρέπεται');
}

try {
    $card = validateCardData($_POST);

    $stmt = $pdo->prepare(
        'INSERT INTO cards
            (card_code, fullname, description, purchase_date, type, price)
         VALUES
            (:card_code, :fullname, :description, :purchase_date, :type, :price)'
    );

    $stmt->execute($card);

    respond(201, true, 'Η κάρτα προστέθηκε επιτυχώς');
} catch (ValidationException $exception) {
    respond(422, false, $exception->getMessage());
} catch (PDOException $exception) {
    if ($exception->getCode() === '23000') {
        respond(409, false, 'Ο κωδικός κάρτας υπάρχει ήδη');
    }

    error_log('card_create database error: ' . $exception->getMessage());
    respond(500, false, 'Σφάλμα βάσης');
} catch (Throwable $exception) {
    error_log('card_create error: ' . $exception->getMessage());
    respond(500, false, 'Απρόβλεπτο σφάλμα');
}
