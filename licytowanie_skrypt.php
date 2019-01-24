<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/licytacja.php");
	include_once("klasy/uzytkownik.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/oferta\.php\?oferta=[0-9]/");
	Uzytkownik::sprawdzDaneUzytkownikaKlient($_SESSION["login"]);
	$oferta = new Licytacja($_POST["id-oferty"]);
	$dzis = date("Y-m-d H:i:s");
	if ($oferta->typ != "licytacja")
	{
		header("Location: index.php");
		exit();
	}
	if ($dzis > $oferta->czas)
	{
		$_SESSION["licytowanieBlad"] = "Licytacja została zakończona";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	if ($_POST["oferta"] == "")
	{
		$_SESSION["licytowanieBlad"] = "Podaj swoją ofertę";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	elseif ($_POST["oferta"] <= Licytacja::zwrocWygrywajacaKwote($_POST["id-oferty"]))
	{
		$_SESSION["licytowanieBlad"] = "Twoja oferta musi być większa niż dotychczasowa";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	elseif ($_POST["oferta"] >= 99999999.99)
	{
		$_SESSION["licytowanieBlad"] = "Niedozwolona wartość";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	if (strcmp($_SESSION["login"], $oferta->login) == 0)
	{
		$_SESSION["licytowanieBlad"] = "Nie możesz brać udziału we własnych licytacjach";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	try
	{
		if ($_POST["oferta"] == $oferta->zwrocNajwiekszaKwote())
		{
			$kwota = $_POST["oferta"] - 0.01;
		}
		else
		{
			$kwota = $_POST["oferta"];
		}
		Licytacja::licytuj($_POST["id-oferty"], $kwota, $_SESSION["login"]);
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>