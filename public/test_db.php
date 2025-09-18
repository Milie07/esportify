<?php
try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=esportify;charset=utf8mb4",
        "Milie07",
        "Admin342455Emy"
    );
    echo "Connexion OK";
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
