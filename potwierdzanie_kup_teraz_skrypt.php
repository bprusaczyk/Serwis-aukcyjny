<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/kup_teraz.php");
	include_once("klasy/zamowienie.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStronRegex("/http:\/\/localhost\/koszyk\.php\?strona=[0-9]/");
	Uzytkownik::sprawdzDaneUzytkownikaKlient($_SESSION["login"]);
	if ($_POST["sposob-dostawy"] == "")
	{
		$_SESSION["wybieranieSposobuDostawyBlad"] = "Wybierz sposób dostawy";
		header("Location: ".$_SERVER["HTTP_REFERER"]);
		exit();
	}
	try
	{
		$zamowienie = new Zamowienie($_POST["id-zamowienia"]);
		$dzis = date("Y-m-d H:i:s");
		if ($zamowienie->oferta->czas !="" && $dzis > $zamowienie->oferta->czas)
		{
			$_SESSION["koszykBlad"] = "Ta oferta jest już nie ważna";
			KupTeraz::usunZamowienie($_POST["id-zamowienia"]);
			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit();
		}
		if ($zamowienie->oferta->liczbaSztuk < $zamowienie->liczbaSztuk)
		{
			$_SESSION["koszykBlad"] = "Towar w żądanej liczbie jest już niedostępny";
			KupTeraz::usunZamowienie($_POST["id-zamowienia"]);
			header("Location: ".$_SERVER["HTTP_REFERER"]);
			exit();
		}
		KupTeraz::potwierdzZamowienie($_POST["id-zamowienia"], $_POST["sposob-dostawy"]);
		$_SESSION["potwierdzenieZamowienia"] = "Zamówienie zostało potwierdzone";
		header("Location: historia_zakupow.php?strona=1");
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
		exit();
	}
?>