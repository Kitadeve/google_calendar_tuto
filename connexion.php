<?php 

//1. Connext to local Mysql Server

// $conn = new mysqli("192.168.1.35", "root", "1357", "calendar");
// $conn->set_charset("utf8mb4");

$server = "192.168.1.35";
$utilisateur = "root";
$motdepasse = "1357";
$baseDeDonnees = "calendar";
$port = 3306;

$conn = new mysqli($server, $utilisateur, $motdepasse, $baseDeDonnees, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>