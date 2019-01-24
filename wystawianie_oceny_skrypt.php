<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/ocena.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/licytacje\.php\?strona=[0-9]&wygrane=true/");
	if ($_POST["ocena"] < 0 || $_POST["ocena"] > 10)
	{
		header("Location: index.php");
		exit();
	}
	$ocena = new Ocena($_POST["ocena"], $_POST["komentarz"]);
	try
	{
		$ocena->wystaw($_POST["id-oferty"]);
		$_SESSION["ocenaDodana"] = true;
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
		exit();
	}
?>