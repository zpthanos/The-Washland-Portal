<?php
declare(strict_types=1);

// ──────────────────────────────────────────────────────────────
// 1) CONFIG 
// ──────────────────────────────────────────────────────────────

$host    = getenv('DB_HOST')    ?: '127.0.0.1';
$port    = (int)(getenv('DB_PORT') ?: '3306');
$db      = getenv('DB_NAME')    ?: 'washland';
$user    = getenv('DB_USER')    ?: 'root';
$pass    = getenv('DB_PASS')    ?: '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';


$tzEnv   = getenv('DB_TIMEZONE') ?: 'Europe/Athens';

if (preg_match('/^[\+\-]\d{2}:\d{2}$/', $tzEnv)) {
    $offset = $tzEnv;
} else {
    $dtz    = new \DateTimeZone($tzEnv);
    $dt     = new \DateTime('now', $dtz);
    $offset = $dt->format('P');
}

// ──────────────────────────────────────────────────────────────
// 2) DSN & PDO
// ──────────────────────────────────────────────────────────────

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $host,
    $port,
    $db,
    $charset
);

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => filter_var(getenv('DB_PERSISTENT') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    PDO::ATTR_STRINGIFY_FETCHES  => false,
    PDO::ATTR_AUTOCOMMIT         => true,
    PDO::MYSQL_ATTR_INIT_COMMAND => sprintf(
        "SET NAMES %s COLLATE %s_unicode_ci, time_zone = '%s'",
        $charset,
        $charset,
        $offset
    ),
];

// ──────────────────────────────────────────────────────────────
// 3) ΑΠΟΠΕΙΡΑ ΣΥΝΔΕΣΗΣ
// ──────────────────────────────────────────────────────────────

try {
    /** @var PDO $pdo */
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
   
    error_log(sprintf(
        '[%s] Η σύνδεση απέτυχε (%s:%d): %s',
        date('Y-m-d H:i:s'),
        __FILE__,
        __LINE__,
        $e->getMessage()
    ));

    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(
        ['success' => false, 'msg' => 'Αποτυχία σύνδεσης στη βάση δεδομένων'],
        JSON_UNESCAPED_UNICODE
    );
    exit;
}


