<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/licytacja.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/oferta\.php\?oferta=[0-9]/");
	try
	{
		Licytacja::usunOferte($_POST["id-oferty"]);
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
		exit();
	}
?>