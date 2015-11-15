<?php
/******************************************************************************/
/*                                                                            */
/*                       __        ____                                       */
/*                 ___  / /  ___  / __/__  __ _____________ ___               */
/*                / _ \/ _ \/ _ \_\ \/ _ \/ // / __/ __/ -_|_-<               */
/*               / .__/_//_/ .__/___/\___/\_,_/_/  \__/\__/___/               */
/*              /_/       /_/                                                 */
/*                                                                            */
/*                                                                            */
/******************************************************************************/
/*                                                                            */
/* Titre          : G�n�rer une chaine de caract�re unique et al�atoire       */
/*                                                                            */
/* URL            : http://www.phpsources.org/scripts87-PHP.htm               */
/* Auteur         : PHP Sources                                               */
/* Date �dition   : 04-11-2004                                                */
/*                                                                            */
/******************************************************************************/


//G�n�rer une chaine de caract�re unique et al�atoire

function random($car) {
$string = "";
$chaine = "abcdefghijklmnpqrstuvwxy";
srand((double)microtime()*1000000);
for($i=0; $i<$car; $i++) {
$string .= $chaine[rand()%strlen($chaine)];
}
return $string;
}

// APPEL
// G�n�re une chaine de longueur 20
$chaine = random(20);

?>
