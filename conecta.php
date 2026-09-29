<?php 
$host = "192.168.10.42";
$banco = "manutencao";
$senha = "senha4321";
$usuario = "postgres";

$pdo = new PDO(
    "pgsql:host=$host;port=5432;dbname=$banco",
    $usuario,
    $senha
);
?>