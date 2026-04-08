<?php
include 'config.php';

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