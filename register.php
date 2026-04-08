<?php
include 'config.php';

// Abilita CORS per qualsiasi origine (solo per sviluppo, in produzione limita al tuo dominio)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Rispondi subito alle richieste OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Metodo non consentito");
}

$nickname = trim($_POST["nickname"] ?? "");
$email = trim($_POST["email"] ?? "");
$password_raw = $_POST["password"] ?? "";

if (empty($nickname) || empty($email) || empty($password_raw)) {
    die("Campi mancanti");
}

$password = password_hash($password_raw, PASSWORD_BCRYPT);

$query = "INSERT INTO users (nickname, email, password)
          VALUES ($1, $2, $3)";

$result = pg_query_params($conn, $query, array(
    $nickname,
    $email,
    $password
));

if ($result) {
    echo "Registrazione completata";
} else {
    echo "Errore registrazione (utente già esistente?)";
}

pg_close($conn);
?>