<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/kup_teraz.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/koszyk\.php\?strona=[0-9]/");
	try
	{
		KupTeraz::usunZamowienie($_POST["id-zamowienia"]);
		$_SESSION["anulowanieZamowienia"] = true;
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>