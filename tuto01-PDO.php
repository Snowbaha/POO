<?php
## connexion BDD en PDO
try
{
    // On se connecte à MySQL
    $bdd = new PDO('mysql:host=localhost;dbname=tuto_sdz', 'root', '');
}
catch(Exception $e)
{
    // En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}
 
## Si tout va bien, on peut continuer
 
##### Méthode 1 direct #########################################################################################################
// On récupère tout le contenu de la table jeux_video
$reponse = $bdd->query('SELECT * FROM jeux_video LIMIT 0, 2') or die(print_r($bdd->errorInfo())); //si requête pas bonne, affiche une erreur
 
// On affiche chaque entrée une à une (temps qu'il y a des éléments, la boucle continu)
while ($donnees = $reponse->fetch())
{
?>
    <p>
    <strong>Jeu</strong> : <?php echo $donnees['nom']; ?><br />
    Le possesseur de ce jeu est : <?php echo $donnees['possesseur']; ?>, et il le vend à <?php echo $donnees['prix']; ?> euros !<br />

   </p>
<?php
}
 
$reponse->closeCursor(); // Termine le traitement de la requête


##### Méthode 2 préparation #########################################################################################################
$req = $bdd->prepare('SELECT nom FROM jeux_video WHERE possesseur = ? AND prix <= ?'); //les variables sont en ?
$req->execute(array($_GET['possesseur'], $_GET['prix_max'])) or die(print_r($req->errorInfo())); // remplaces les ? par les variable dans l'ordre

//OU on peut nommer les variables
$req = $bdd->prepare('SELECT nom, prix FROM jeux_video WHERE possesseur = :possesseur AND prix <= :prixmax');
$req->execute(array('possesseur' => $_GET['pos'], 'prixmax' => $_GET['pri']));

echo '<ul>';
while ($donnees = $req->fetch())
{
    echo '<li>' . $donnees['nom'] . ' (' . $donnees['prix'] . ' EUR)</li>';
}
echo '</ul>';
 
$req->closeCursor();

?>