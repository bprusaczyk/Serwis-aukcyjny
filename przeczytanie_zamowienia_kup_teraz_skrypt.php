<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/kup_teraz.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/zamowienia_do_zrealizowania\.php\?strona=[0-9]/");
	try
	{
		KupTeraz::oznaczZamowienieJakoPrzeczytane($_POST["id-zamowienia"]);
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>