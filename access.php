<?php
include 'config.php';

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
        echo "Login riuscito";
    } else {
        echo "Password errata";
    }

} else {
    echo "Utente non trovato";
}

pg_close($conn);
?>