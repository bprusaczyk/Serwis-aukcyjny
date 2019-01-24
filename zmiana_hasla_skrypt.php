<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStron("http://localhost/zmiana_hasla.php");
	try
	{
		$blad = false;
		if ($_POST["stare-haslo"] == "")
		{
			$_SESSION["zmianaHaslaBladStareHaslo"] = "Podaj stare hasło";
			$blad = true;
		}
		elseif (!Uzytkownik::zaloguj($_SESSION["login"], $_POST["stare-haslo"]))
		{
			$_SESSION["zmianaHaslaBladStareHaslo"] = "Nieprawidłowe hasło";
			$blad = true;
		}
		if (strlen($_POST["nowe-haslo-1"]) < 8)
		{
			$_SESSION["zmianaHaslaBladNoweHaslo1"] = "Hasło powinno zawierać co najmniej 8 znaków";
			$blad = true;
		}
		elseif (preg_match("/[a-zęóąśłżźćń]/", $_POST["nowe-haslo-1"]) != 1 || preg_match("/[A-ZĘÓĄŚŁŻŹĆŃ]/", $_POST["nowe-haslo-1"]) != 1 || preg_match("/[0-9]/", $_POST["nowe-haslo-1"]) != 1)
		{
			$_SESSION["zmianaHaslaBladNoweHaslo1"] = "Hasło jest za słabe (silne hasło powinno zawierać co najmniej jedną małą literę, jedną dużą literę i jedną cyfrę)";
			$blad = true;
		}
		if ($_POST["nowe-haslo-1"] != $_POST["nowe-haslo-2"])
		{
			$_SESSION["zmianaHaslaBladNoweHaslo2"] = "Hasła nie zgadzają się";
			$blad = true;
		}
		if ($blad)
		{
			header("Location: zmiana_hasla.php");
		}
		else
		{
			Uzytkownik::zmienHaslo($_POST["nowe-haslo-1"], $_SESSION["login"]);
			$_SESSION["zmianaHaslaSukces"] = true;
			header("Location: zmiana_hasla.php");
		}
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
		exit();
	}
?>