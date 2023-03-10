<?php
// fonctions mail
//traduction CPascalWeb
// Ne pas modifiez le nom de la classe
class XoopsMailerLocal extends icms_messaging_EmailHandler {

	function __construct(){
		parent::__construct();
		// Il est pas n�cessaire de modifier le jeu de caract�res
		$this->charSet = strtolower( _CHARSET );
		//  indiquer le code de langue ainsi que le fichier existe: XOOPS_ROOT_PAT/class/mail/phpmailer/language/lang-["your-language-code"].php
	    $this->multimailer->SetLanguage("fr");
	}
	
	// Multi-langues l'encodage du nom
	function encodeFromName($text)
	{
		// Activez la ligne suivante si n�cessaire
		// $text = "=?{$this->charSet}?B?".base64_encode($text)."?=";
		return $text;
	}

	// Multi-langues l'encodage Sujet
	function encodeSubject($text)
	{
		// Activez la ligne suivante si n�cessaire
		// $text = "=?{$this->charSet}?B?".base64_encode($text)."?=";
		return $text;
	}
}
