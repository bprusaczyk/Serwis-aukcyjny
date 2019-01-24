<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStron("http://localhost/edycja_konta.php");
	try
	{
		Uzytkownik::aktualizujDaneOsobowe($_SESSION["login"], $_POST["imie"], $_POST["nazwisko"], $_POST["kraj"], $_POST["miejscowosc"], $_POST["ulica"], $_POST["numer-budynku"], $_POST["numer-mieszkania"], $_POST["numer-telefonu"], $_POST["adres-email"], $_POST["numer-konta-bankowego"], $_POST["kod-pocztowy"]);
		if (!isset($_SESSION["edycjaDanychOsobowychStronaPowrotu"]))
		{
			$_SESSION["zmianaDanychOsobowychSukces"] = true;
		}
		$adres = isset($_SESSION["edycjaDanychOsobowychStronaPowrotu"]) ? $_SESSION["edycjaDanychOsobowychStronaPowrotu"] : "edycja_konta.php";
		unset($_SESSION["edycjaDanychOsobowychStronaPowrotu"]);
		header("Location: ".$adres);
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>