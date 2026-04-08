<?php
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