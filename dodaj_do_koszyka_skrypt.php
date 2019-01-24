<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/kup_teraz.php");
	include_once("klasy/licytacja.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/oferta\.php\?oferta=[0-9]/");
	$oferta = new Licytacja($_POST["id-oferty"]);
	$dzis = date("Y-m-d H:i:s");
	if ($oferta->typ != "kup teraz")
	{
		header("Location: index.php");
		exit();
	}
	if ($_SESSION["login"] == $oferta->login)
	{
		$_SESSION["dodajDoKoszykaBlad"] = "Nie możesz kupować swoich towarów";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	if ($oferta->czas != "" && $dzis > $oferta->czas)
	{
		$_SESSION["dodajDoKoszykaBlad"] = "Oferta jest już nieważna";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	if ($_POST["liczba-sztuk"] == "")
	{
		$_SESSION["dodajDoKoszykaBlad"] = "Podaj liczbę sztuk w zamówieniu";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	elseif ($_POST["liczba-sztuk"] <= 0)
	{
		$_SESSION["dodajDoKoszykaBlad"] = "Niepoprawna wartość";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	elseif ($_POST["liczba-sztuk"] > $oferta->liczbaSztuk)
	{
		$_SESSION["dodajDoKoszykaBlad"] = "Towar w podanej liczbie jest niedostępny";
		header("Location: oferta.php?oferta=".$_POST["id-oferty"]);
		exit();
	}
	try
	{
		KupTeraz::dodajDoKoszyka($_POST["id-oferty"], $_POST["liczba-sztuk"]);
		$_SESSION["dodanoDoKoszyka"] = true;
		header("Location: koszyk.php?strona=1");
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>