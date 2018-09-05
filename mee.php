<?php
// Fonction qui permet de mettre à jour le compteur de visites
function compter_visite(){
    // On va utiliser l'objet $pdo pour se connecter, il est créé en dehors de la fonction
    // donc on doit indiquer global $pdo; au début de la fonction
    global $pdo;
     
    // On prépare les données à insérer
    $ip   = $_SERVER['REMOTE_ADDR']; // L'adresse IP du visiteur
    $date = date('Y-m-d');           // La date d'aujourd'hui, sous la forme AAAA-MM-JJ
     
    // Mise à jour de la base de données
    // 1. On initialise la requête préparée
    $query = $pdo->prepare("
        INSERT INTO stats_visites (ip , date_visite , pages_vues) VALUES (:ip , :date , 1)
        ON DUPLICATE KEY UPDATE pages_vues = pages_vues + 1
    ");
    // 2. On execute la requête préparée avec nos paramètres
    $query->execute(array(
        ':ip'   => $ip,
        ':date' => $date
    ));
}
?>