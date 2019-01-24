<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/licytacja.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/moje_licytacje\.php\?strona=[0-9]&zakonczone=true/");
	try
	{
		Licytacja::oznaczZamowienieJakoPrzeczytane($_POST["id-oferty"]);
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>