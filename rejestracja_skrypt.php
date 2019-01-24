<?php
    include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
    session_start();
	Bezpieczenstwo::zablokujDostepZalogowanemuUzytkownikowi();
	Bezpieczenstwo::zablokujDostepZInnychStron("http://localhost/rejestracja.php");
    try
    {
		$blad = false;
		if ($_POST["login"] == "")
		{
			$_SESSION["rejestracjaBladLogin"] = "Wpisz login";
			$blad = true;
		}
		elseif (!ctype_alnum($_POST["login"]))
		{
			$_SESSION["rejestracjaBladLogin"] = "Login może zawierać tylko litery alfabetu łacińskiego i cyfry";
			$blad = true;
		}
		elseif (!Uzytkownik::sprawdzDostepnoscLoginu($_POST["login"]))
		{
			$_SESSION["rejestracjaBladLogin"] = "Podany login jest już zajęty";
			$blad = true;
		}
		if (strlen($_POST["haslo1"]) < 8)
		{
			$_SESSION["rejestracjaBladHaslo1"] = "Hasło powinno zawierać co najmniej 8 znaków";
			$blad = true;
		}
		elseif (preg_match("/[a-zęóąśłżźćń]/", $_POST["haslo1"]) != 1 || preg_match("/[A-ZĘÓĄŚŁŻŹĆŃ]/", $_POST["haslo1"]) != 1 || preg_match("/[0-9]/", $_POST["haslo1"]) != 1)
		{
			$_SESSION["rejestracjaBladHaslo1"] = "Hasło jest za słabe (silne hasło powinno zawierać co najmniej jedną małą literę, jedną dużą literę i jedną cyfrę)";
			$blad = true;
		}
		if ($_POST["haslo1"] != $_POST["haslo2"])
		{
			$_SESSION["rejestracjaBladHaslo2"] = "Hasła nie zgadzają się";
			$blad = true;
		}
		if ($_POST["email"] == "")
		{
			$_SESSION["rejestracjaBladMail"] = "Wpisz swój adres e-mail";
			$blad = true;
		}
		elseif (preg_match("/^\S+@\S+$/", $_POST["email"]) != 1)
		{
			$_SESSION["rejestracjaBladMail"] = "Wprowadzony adres e-mail ma niepoprawny format";
			$blad = true;
		}
		elseif (!Uzytkownik::sprawdzDostepnoscMaila($_POST["email"]))
		{
			$_SESSION["rejestracjaBladMail"] = "Konto zarejestrowane na podany adres e-mail już istnieje";
			$blad = true;
		}
		if($_POST["regulamin"] != "on")
		{
			$_SESSION["rejestracjaBladRegulamin"] = true;
			$blad = true;
		}
		if(json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LewkV8UAAAAAGxYO6njqqrfX7mt3oB_JTeXAqHm&response=".$_POST["g-recaptcha-response"]))->success == false)
		{
			$_SESSION["rejestracjaBladReCaptcha"] = "Potwierdź, że nie jesteś robotem";
			$blad = true;
		}
		if ($blad)
		{
			Uzytkownik::zapamietajDane();
			header("Location: rejestracja.php");
		}
		else
		{
			Uzytkownik::zarejestruj($_POST["login"], $_POST["haslo1"], $_POST["email"]);
			header("Location: rejestracja_powodzenie.php");
		}
    }
    catch (Exception $e)
    {
        header("Location: blad.php");
    }
?>