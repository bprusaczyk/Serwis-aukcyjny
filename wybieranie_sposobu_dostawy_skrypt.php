<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/licytacja.php");
	session_start();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/licytacje\.php\?strona=[0-9]&wygrane=true/");
	if ($_POST["sposob-dostawy"] == "")
	{
		$_SESSION["wybieranieSposobuDostawyBlad"] = "Wybierz sposób dostawy";
		header("Location: ".$_SERVER["HTTP_REFERER"]);
		exit();
	}
	try
	{
		Licytacja::wybierzSposobDostawy($_POST["id-oferty"], $_SESSION["login"], $_POST["sposob-dostawy"]);
		header("Location: ".$_SERVER["HTTP_REFERER"]);
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>