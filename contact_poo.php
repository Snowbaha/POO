<?php

class Contact{
	private $_sujet ;
	private $_message ;
	
	const EMAIL_DEST = "email@email.com";
	
	
	## Constructeur -> appelé auto quand on instancie la class
	public function __construct( $sujet=null, $message=null){
		$this->setSujet($sujet);
		$this->setMessage($message);
	}
	
	
	## GETTER -> On récupère les valeur des attribut de l' objet
	public function getSujet(){
		return $this->_sujet ;
	}
	
	public function getMessagel(){
		return $this->_message ;
	}
	
	
	## SETTER -> on défini les objets
	public function setSujet($sujet){
		$this->_sujet = $sujet ;
	}
	
	public function setMessage($message){
		$this->_message = $message ;
	}
	
	## Fonction d'nevoi d'email
	public function envoiEmail(){
		mail(self::EMAIL_DEST, $this->_sujet, $this->_message);
	}
	

	
}

## On créé un objet (instancier) Contact
$contact = new Contact();  //on fait le constructeur
$contact->envoiEmail(); // on appelle la méthode EnvoiEmail

echo "email:".$contact->getEmail();
var_dump($contact);


?>
