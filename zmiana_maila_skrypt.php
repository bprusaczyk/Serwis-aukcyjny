<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStron("http://localhost/zmiana_maila.php");
	$blad = false;
	try
	{
		if ($_POST["haslo"] == "")
		{
			$_SESSION["zmianaEMailaBladHaslo"] = "Podaj hasło";
			$blad = true;
		}
		elseif (!Uzytkownik::zaloguj($_SESSION["login"], $_POST["haslo"]))
		{
			$_SESSION["zmianaEMailaBladHaslo"] = "Nieprawidłowe hasło";
			$blad = true;
		}
		if ($_POST["nowy-e-mail"] == "")
		{
			$_SESSION["zmianaEMailaBladNowyEMail"] = "Podaj nowy adres e-mail";
			$blad = true;
		}
		elseif (preg_match("/^\S+@\S+$/", $_POST["nowy-e-mail"]) != 1)
		{
			$_SESSION["zmianaEMailaBladNowyEMail"] = "Wprowadzony adres e-mail ma niepoprawny format";
			$blad = true;
		}
		elseif (!Uzytkownik::sprawdzDostepnoscMaila($_POST["nowy-e-mail"]))
		{
			$_SESSION["zmianaEMailaBladNowyEMail"] = "Konto zarejestrowane na podany adres e-mail już istnieje";
			$blad = true;
		}
		if ($blad)
		{
			$_SESSION["staryNowyEMail"] = $_POST["nowy-e-mail"];
			header("Location: zmiana_maila.php");
		}
		else
		{
			Uzytkownik::zmienEMail($_POST["nowy-e-mail"], $_SESSION["login"]);
			$_SESSION["zmianaEMailaSukces"] = true;
			header("Location: zmiana_maila.php");
		}
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>