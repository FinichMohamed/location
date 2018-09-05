<!DOCTYPE html>
<html>
<head>
<title></title>
</head>
<body>

<h1>Bienvenue sur notre site web</h1>

<?php
// On indique au navigateur qu'on utilise l'encodage UTF-8
header('Content-type: text/html; charset=utf-8');
 
// Paramètres de connexion à la base
define('DB_HOST' , 'localhost');
define('DB_NAME' , 'devops');
define('DB_USER' , 'root');
define('DB_PASS' , '');
 
// Connexion à la base avec PDO
try{
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
} catch(Exception $e) {
    echo "Impossible de se connecter à la base de données '".DB_NAME."' sur ".DB_HOST." avec le compte utilisateur '".DB_USER."'";
    echo "<br/>Erreur PDO : <i>".$e->getMessage()."</i>";
    die();
}
?>
<?php
// Fonction qui permet de mettre à jour le compteur de visites
function compter_visite(){
    // On va utiliser l'objet $pdo pour se connecter, il est créé en dehors de la fonction
    // donc on doit indiquer global $pdo; au début de la fonction
    global $pdo;
     
    // On prépare les données à insérer
    $ip   = $_SERVER['REMOTE_ADDR']; /*L'adresse IP du visiteur C'est l'adresse IP du visiteur.
                                        Chaque personne qui visite un site a une IP différente, 
                                        donc on va utiliser cette information pour différencier les visiteurs.*/

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
compter_visite();

// On récupère tout le contenu de la table stats_visites
$reponse = $pdo->query('SELECT * FROM stats_visites');
// On affiche chaque entrée une à une
while ($donnees = $reponse->fetch())
{
?>
    <p>
    <strong>L'adresse IP du visiteur</strong> : <?php echo $donnees['ip']; ?><br />
    La date de visite  : <?php echo $donnees['date_visite']; ?><br />
   Le nombre des pages vues  <?php echo $donnees['pages_vues']; ?>
   </p>
<?php
}

$reponse->closeCursor(); // Termine


?>

</body>
</html>
