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

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if (empty($email) || empty($password)) {
    die("Campi mancanti");
}

$query = "SELECT nickname, password FROM users WHERE email = $1";
$result = pg_query_params($conn, $query, array($email));

if (!$result) {
    die("Errore query");
}

if (pg_num_rows($result) === 1) {

    $row = pg_fetch_assoc($result);
    $hash = $row["password"];

    if (password_verify($password, $hash)) {
        echo "Login riuscito  da Tolentino";
    } else {
        echo "Password errata da Tolentino";
    }

} else {
    echo "Utente non trovato da Tolentino";
}

pg_close($conn);
?>