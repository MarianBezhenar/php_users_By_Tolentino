<?php

// Abilita CORS per qualsiasi origine (solo per sviluppo, in produzione limita al tuo dominio)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Rispondi subito alle richieste OPTIONS (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = getenv("PGHOST");
$port = getenv("PGPORT");
$db   = getenv("PGDATABASE");
$user = getenv("PGUSER");
$pass = getenv("PGPASSWORD");

$conn_string = "
    host=$host
    port=$port
    dbname=$db
    user=$user
    password=$pass
    sslmode=require
";

$conn = pg_connect($conn_string);

if (!$conn) {
    die("Errore connessione PostgreSQL.");
}
?>