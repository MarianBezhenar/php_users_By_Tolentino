<?php
include 'config.php';

// Abilita CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");  // Importante: risposta JSON

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Metodo non consentito"]);
    exit();
}

$identifier = trim($_POST["email"] ?? "");   // può essere email o nickname
$password = $_POST["password"] ?? "";

if (empty($identifier) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Campi mancanti"]);
    exit();
}

// Cerca per email OPPURE per nickname
$query = "SELECT nickname, email, password FROM users WHERE email = $1 OR nickname = $1";
$result = pg_query_params($conn, $query, array($identifier));

if (!$result || pg_num_rows($result) !== 1) {
    echo json_encode(["success" => false, "message" => "Utente non trovato"]);
    pg_close($conn);
    exit();
}

$row = pg_fetch_assoc($result);
$hash = $row["password"];

if (password_verify($password, $hash)) {
    // Login riuscito – restituisci i dati (isAdmin = false per gli utenti normali)
    echo json_encode([
        "success" => true,
        "nickname" => $row["nickname"],
        "email" => $row["email"],
        "isAdmin" => false
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Password errata"]);
}

pg_close($conn);
?>