<?php

class Personnage{
    
    ## Attributs qui correspond aux champs de la BDD dont on a besoin
    private $_id,
            $_degats,
            $_nom ;
    
    const CEST_MOI = 1;
    const PERSONNAGE_TUE = 2;
    const PERSONNAGE_FRAPPE = 3;
  
  ## constructeur :
  public function __construct(array $donnees) {
      $this->hydrate($donnees);
  }
  ## Méthodes
  public function hydrate(array $donnees){
      foreach($donnees as $key => $value) :
          $method = 'set'.ucfirst($key);
          if(method_exists($this, $method)) :
              $this->$method($value);
          endif;
      endforeach;
      
  }
  
  public function frapper(Personnage $perso)
  {
    // Avant tout : vérifier qu'on ne se frappe pas soi-même.
    // Si c'est le cas, on stoppe tout en renvoyant une valeur signifiant que le personnage ciblé est le personnage qui attaque.
      if($perso->getId() == $this->_id) :
          return self::CEST_MOI;
      endif;
   
      // On indique au personnage frappé qu'il doit recevoir des dégâts.
      return $perso->recevoirDegats();
      
  }
 
  public function recevoirDegats()
  {
    // On augmente de 5 les dégâts.
      $this->_degat += 5;
    // Si on a 100 de dégâts ou plus, la méthode renverra une valeur signifiant que le personnage a été tué.
     if($this->_degats >= 100) return self::PERSONNAGE_TUE ;
    // Sinon, elle renverra une valeur signifiant que le personnage a bien été frappé.
      return self::PERSONNAGE_FRAPPE ;
  }
  
  public function nomValide()
  {
    return !empty($this->_nom);
  }


  ## getter et setter
  public function getDegats()
  {
    return $this->_degats;
  }
   
  public function getId()
  {
    return $this->_id;
  }
   
  public function getNom()
  {
    return $this->_nom;
  }
   
  public function setDegats($degats)
  {
    $degats = (int) $degats;
     
    if ($degats >= 0 && $degats <= 100)
    {
      $this->_degats = $degats;
    }
  }
   
  public function setId($id)
  {
    $id = (int) $id;
     
    if ($id > 0)
    {
      $this->_id = $id;
    }
  }
   
  public function setNom($nom)
  {
    if (is_string($nom))
    {
      $this->_nom = $nom;
    }
  }
  
}


?>