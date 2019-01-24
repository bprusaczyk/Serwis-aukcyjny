<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/kup_teraz.php");
	include_once("klasy/ocena.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/historia_zakupow\.php\?strona=[0-9]/");
	if ($_POST["ocena"] < 0 || $_POST["ocena"] > 10)
	{
		header("Location: index.php");
		exit();
	}
	$ocena = new Ocena($_POST["ocena"], $_POST["komentarz"]);
	try
	{
		$ocena->wystawKupTeraz($_POST["id-oferty"], $_POST["id-zamowienia"]);
		$_SESSION["ocenaDodana"] = true;
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
		exit();
	}
?>