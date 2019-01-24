<?php
    include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
    session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStron("http://localhost/usun_konto.php");
	try
	{
		$blad = false;
		if ($_POST["haslo"] == "")
		{
			$_SESSION["usunKontoBladHaslo"] = "Podaj hasło";
			$blad = true;
		}
		elseif (!Uzytkownik::zaloguj($_SESSION["login"], $_POST["haslo"]))
		{
			$_SESSION["usunKontoBladHaslo"] = "Nieprawidłowe hasło";
			$blad = true;
		}
		if ($blad)
		{
			header("Location: usun_konto.php");
		}
		else
		{
			Uzytkownik::usunKonto($_SESSION["login"]);
			session_unset();
			header("Location: index.php");
		}
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
?>