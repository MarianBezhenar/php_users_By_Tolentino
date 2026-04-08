<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'config.php';

// --- GESTIONE RICHIESTE GET (nuovo blocco) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = $_GET['userId'] ?? '';
    $fridgeId = $_GET['fridgeId'] ?? '';

    if (empty($userId) || empty($fridgeId)) {
        echo json_encode(['error' => 'Missing userId or fridgeId', 'authorized' => false]);
        exit();
    }

    // Cerca l'utente per nickname o email (case-insensitive)
    $userQuery = "SELECT id FROM users WHERE nickname = $1 OR email ILIKE $1";
    $userResult = pg_query_params($conn, $userQuery, array($userId));

    if (!$userResult || pg_num_rows($userResult) === 0) {
        echo json_encode(['error' => 'User not found', 'authorized' => false]);
        exit();
    }
    $userRow = pg_fetch_assoc($userResult);
    $userNumericId = $userRow['id'];

    $checkQuery = "SELECT 1 FROM assegnazioni WHERE user_id = $1 AND id_prodotto = $2";
    $checkResult = pg_query_params($conn, $checkQuery, array($userNumericId, $fridgeId));

    $authorized = ($checkResult && pg_num_rows($checkResult) > 0);
    echo json_encode(['authorized' => $authorized]);
    pg_close($conn);
    exit();
}

// --- GESTIONE RICHIESTE POST (codice già esistente, invariato) ---
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['error' => 'Invalid JSON', 'authorized' => false]);
    exit();
}

$userId = trim($input['userId'] ?? '');
$fridgeId = trim($input['fridgeId'] ?? '');

if (empty($userId) || empty($fridgeId)) {
    echo json_encode(['error' => 'Missing userId or fridgeId', 'authorized' => false]);
    exit();
}

// Cerca l'utente per nickname o email (case-insensitive)
$userQuery = "SELECT id FROM users WHERE nickname = $1 OR email ILIKE $1";
$userResult = pg_query_params($conn, $userQuery, array($userId));

if (!$userResult || pg_num_rows($userResult) === 0) {
    echo json_encode(['error' => 'User not found', 'authorized' => false]);
    exit();
}
$userRow = pg_fetch_assoc($userResult);
$userNumericId = $userRow['id'];

$checkQuery = "SELECT 1 FROM assegnazioni WHERE user_id = $1 AND id_prodotto = $2";
$checkResult = pg_query_params($conn, $checkQuery, array($userNumericId, $fridgeId));

$authorized = ($checkResult && pg_num_rows($checkResult) > 0);
echo json_encode(['authorized' => $authorized]);

pg_close($conn);
?>