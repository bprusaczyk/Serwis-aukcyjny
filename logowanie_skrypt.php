<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
	session_start();
	Bezpieczenstwo::zablokujDostepZalogowanemuUzytkownikowi();
	try
	{
		if (Uzytkownik::sprawdzCzyUzytkownikJestZablokowany($_POST["login-email"]))
		{
			$_SESSION["logowanieBlad"] = "Użytkownik został chwilowo zablokowany";
			header("Location: logowanie.php");
		}
		elseif (Uzytkownik::zaloguj($_POST["login-email"], $_POST["haslo"]))
		{
			$_SESSION["zalogowany"] = true;
			header("Location: index.php");
		}
		else
		{
			Uzytkownik::dodajNieudanaProbeLogowania($_POST["login-email"]);
			$_SESSION["logowanieBlad"] = "Błędny login / e-mail lub hasło";
			header("Location: logowanie.php");
		}
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>