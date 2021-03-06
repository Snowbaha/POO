<?php

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
    // Assignation des valeurs pour le nom du personnage.
    // Exécution de la requête.
    $q = $this->_db->prepare('INSERT INTO personnages SET nom = :nom');
    $q->bindValue(':nom', $perso->getNom());
    $q->execute();
     
    
     
    // Hydratation du personnage passé en paramètre avec assignation de son identifiant et des dégâts initiaux (= 0).
    $perso->hydrate(array(
      'id' => $this->_db->lastInsertId(),
      'degats' => 0,
    ));
  }
   
  public function count()
  {
    // Exécute une requête COUNT() et retourne le nombre de résultats retourné.
    return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();

  }
   
  public function delete(Personnage $perso)
  {
    // Exécute une requête de type DELETE.
   $this->_db->exec('DELETE FROM personnages WHERE id = '.$perso->getId());

  }
   
  public function exists($info)
  {
    // Si le paramètre est un entier, c'est qu'on a fourni un identifiant.
    // On exécute alors une requête COUNT() avec une clause WHERE, et on retourne un boolean.
    if (is_int($info)) : // On veut voir si tel personnage ayant pour id $info existe.
      return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
    endif;

    // Sinon c'est qu'on a passé un nom.
    // Exécution d'une requête COUNT() avec une clause WHERE, et retourne un boolean.
    $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
    $q->execute(array(':nom' => $info));
     
    return (bool) $q->fetchColumn();
  }
   
  public function get($info)
  {
    // Si le paramètre est un entier, on veut récupérer le personnage avec son identifiant.
    // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
    if (is_int($info))
    {
      $q = $this->_db->query('SELECT id, nom, degats FROM personnages WHERE id = '.$info);
      $donnees = $q->fetch(PDO::FETCH_ASSOC);
       
      return new Personnage($donnees);
    }
     
    // Sinon, on veut récupérer le personnage avec son nom.
    // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
    else
    {
      $q = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom = :nom');
      $q->execute(array(':nom' => $info));
     
      return new Personnage($q->fetch(PDO::FETCH_ASSOC));
    }
  }
   
  public function getList($nom)
  {
    // Retourne la liste des personnages dont le nom n'est pas $nom.
    // Le résultat sera un tableau d'instances de Personnage.
      
    $persos = array();
     
    $q = $this->_db->prepare('SELECT id, nom, degats FROM personnages WHERE nom <> :nom ORDER BY nom');
    $q->execute(array(':nom' => $nom));
     
    while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
    {
      $persos[] = new Personnage($donnees);
    }
     
    return $persos;
  }
   
  public function update(Personnage $perso)
  {
    // Prépare une requête de type UPDATE.
    // Assignation des valeurs à la requête.
    // Exécution de la requête.
    $q = $this->_db->prepare('UPDATE personnages SET degats = :degats WHERE id = :id');
    //echo $perso->getDegats();
    $q->bindValue(':degats', $perso->getDegats(), PDO::PARAM_INT);
    $q->bindValue(':id', $perso->getId(), PDO::PARAM_INT);
     
    $q->execute();
  }
   
  public function setDb(PDO $db)
  {
    $this->_db = $db;
  }
  
}
?>