<?php

include_once('tuto02-POO_hydrate.php'); //pour pouvoir appeler la classe Personnage()

class PersonnagesManager
{
  private $_db; // Instance de PDO.
 
  public function __construct($db)
  {
    $this->setDb($db);
  }
 
  public function add(Personnage $perso)
  {
    // Préparation de la requête d'insertion.
    // Assignation des valeurs pour le nom, la force, les dégâts, l'expérience et le niveau du personnage.
    // Exécution de la requête.
	
	$q = $this->_db->prepare('INSERT INTO personnages SET nom = :nom, forcePerso = :forcePerso, degats = :degats, niveau = :niveau, experience = :experience');
 
    $q->bindValue(':nom', $perso->nom()); // on associe le nom ":nom" à une valeur
    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
 
    $q->execute();
  }
 
  public function delete(Personnage $perso)
  {
    // Exécute une requête de type DELETE.
	$this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->id());
  }
  
  
  
  
  
  
  public function get($id)
  {
    // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
	$id = (int) $id;
 
    $q = $this->_db->query('SELECT * FROM personnages WHERE id = '.$id);
    $donnees = $q->fetch(PDO::FETCH_ASSOC);
 	
	//throw new Exception(var_dump($donnees));
	
	//var_dump($donnees);
	
    $perso = new Personnage();
	$perso->hydrate($donnees);
	return $perso ;
  }
  
  
  
  
  
 
  public function getList()
  {
    // Retourne la liste de tous les personnages.
	$persos = array();
 
    $q = $this->_db->query('SELECT id, nom, forcePerso, degats, niveau, experience FROM personnages ORDER BY nom');
 
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
	  $perso = new Personnage();
	  $perso->hydrate($donnees); // IL FAUT RAJOUTER l'hydratation sinon il n'y a pas de donnéees !
      $persos[] = $perso;
    }
 
    return $persos;
  }
  
 
  public function update(Personnage $perso)
  {
    // Prépare une requête de type UPDATE.
    // Assignation des valeurs à la requête.
    // Exécution de la requête.
	
	$q = $this->_db->prepare('UPDATE personnages SET forcePerso = :forcePerso, degats = :degats, niveau = :niveau, experience = :experience WHERE id = :id');
 
    $q->bindValue(':forcePerso', $perso->forcePerso(), PDO::PARAM_INT);
    $q->bindValue(':degats', $perso->degats(), PDO::PARAM_INT);
    $q->bindValue(':niveau', $perso->niveau(), PDO::PARAM_INT);
    $q->bindValue(':experience', $perso->experience(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->id(), PDO::PARAM_INT);
 
    $q->execute()  or die(print_r($req->errorInfo()));
  }
 
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
}


##-- Application des methodes ! --##
$perso = new Personnage();

$perso -> hydrate(array(
  'nom' => 'SParta',
  'forcePerso' => 5,
  'degats' => 0,
  'niveau' => 1,
  'experience' => 0
));

//echo "ex ADD : THIS IS ".$perso->nom();

$db = new PDO('mysql:host=localhost;dbname=tuto_sdz', 'root', '');
$manager = new PersonnagesManager($db);
    
## ajoute le personnage créé $perso  ## 
//$manager->add($perso);
echo "<br>ex ADD :  AJOUT d'un perso hydtraté ! ";

## Supprime le $perso
$select = $manager->get(10);
//$manager->delete($select);
echo "<br>ex DELETE:  Suppression de:".$select->nom()." and his ID is : ".$select->id();


### get pour récupérer les infos d'un perso sur la BDD

$select = $manager->get(5);
$select ->setNiveau(1); // permet de bien faire la différence pour l'exemple 3 ;
$manager->update($select);
//var_dump($select);
echo "<br>ex SELECT :  Select ID 5, it's :".$select->nom()." and his lvl is : ".$select->niveau();


### Update le perso
echo "<br> BUT WITH an Update <br>" ;
$select ->setNiveau(3);
$manager->update($select);
echo "<br>ex UPDATE :  Now, ".$select->nom()." lvl is : ".$select->niveau();

## afficher la liste des poerso
$manager->getList();
echo "<br>ex LISTE des persos : <br>";
$persos = $manager->getList();
//var_dump($persos);

if (empty($persos)) {echo 'Personne...';}
else
{
  foreach ($persos as $unPerso)
    echo 'Perso : <a href="#id', $unPerso->id(), '">', htmlspecialchars($unPerso->nom()), '</a><br />';
}
?>