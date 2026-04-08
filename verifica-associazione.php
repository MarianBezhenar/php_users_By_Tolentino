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

// Ricevi nickname o email (via POST JSON, POST form, o GET)
$input = json_decode(file_get_contents('php://input'), true);
if ($input) {
    $nickname = trim($input['nickname'] ?? '');
    $email = trim($input['email'] ?? '');
} else {
    $nickname = trim($_POST['nickname'] ?? $_GET['nickname'] ?? '');
    $email = trim($_POST['email'] ?? $_GET['email'] ?? '');
}

if (empty($nickname) && empty($email)) {
    echo json_encode(['error' => 'Missing nickname or email', 'authorized' => false]);
    exit();
}

// Se abbiamo l'email, cerchiamo il nickname corrispondente
if (!empty($email)) {
    $userQuery = "SELECT nickname FROM users WHERE email ILIKE $1";
    $userRes = pg_query_params($conn, $userQuery, array($email));
    if ($userRes && pg_num_rows($userRes) > 0) {
        $row = pg_fetch_assoc($userRes);
        $nickname = $row['nickname'];
    } else {
        echo json_encode(['error' => 'User not found', 'authorized' => false]);
        exit();
    }
}

// Verifica se esiste almeno un prodotto associato a questo nickname (id_prodotto non null)
$query = "SELECT 1 FROM assegnazioni WHERE nickname = $1 AND id_prodotto IS NOT NULL LIMIT 1";
$result = pg_query_params($conn, $query, array($nickname));

if ($result && pg_num_rows($result) > 0) {
    echo json_encode(['authorized' => true, 'nickname' => $nickname]);
} else {
    echo json_encode(['authorized' => false, 'nickname' => $nickname]);
}

pg_close($conn);
?>