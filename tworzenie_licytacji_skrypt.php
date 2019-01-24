<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/licytacja.php");
	session_start();
	Bezpieczenstwo::zablokujDostepZInnychStron("http://localhost/tworzenie_licytacji.php");
	$kurierZGory = isset($_POST["kurier-z-gory"]) ? $_POST["kurier-z-gory"] : null;
	$cenaKurierZGory = isset($_POST["cena-kurier-z-gory"]) ? $_POST["cena-kurier-z-gory"] : null;
	$czasKurierZGory = isset($_POST["czas-kurier-z-gory"]) ? $_POST["czas-kurier-z-gory"] : null;
	$kurierPrzyOdbiorze = isset($_POST["kurier-przy-odbiorze"]) ? $_POST["kurier-przy-odbiorze"] : null;
	$cenaKurierPrzyOdbiorze = isset($_POST["cena-kurier-przy-odbiorze"]) ? $_POST["cena-kurier-przy-odbiorze"] : null;
	$czasKurierPrzyOdbiorze = isset($_POST["czas-kurier-przy-odbiorze"]) ? $_POST["czas-kurier-przy-odbiorze"] : null;
	$odbiorOsobisty = isset($_POST["odbior-osobisty"]) ? $_POST["odbior-osobisty"] : null;
	$czasOdbiorOsobisty = isset($_POST["czas-odbior-osobisty"]) ? $_POST["czas-odbior-osobisty"] : null;
	$blad = false;
	if ($_POST["nazwa"] == "")
	{
		$_SESSION["tworzenieLicytacjiBladNazwa"] = "Podaj nazwę oferty";
		$blad = true;
	}
	if ($_POST["cena"] <= 0 || $_POST["cena"] > 99999999.99)
	{
		$_SESSION["tworzenieLicytacjiBladCena"] = "Niedozwolona wartość ceny";
		$blad = true;
	}
	if ($_POST["data-koniec"] == "")
	{
		$_SESSION["tworzenieLicytacjiBladDataKoniec"] = "Podaj datę i godzinę zakończenia licytacji";
		$blad = true;
	}
	if ($_POST["kategoria"] == "")
	{
		$_SESSION["tworzenieLicytacjiBladKategoria"] = "Wybierz kategorię oferty";
		$blad = true;
	}
	if ($_POST["kategoria"] != "Artykuły spożywcze" && !isset($_POST["stan"]))
	{
		$_SESSION["tworzenieLicytacjiBladStan"] = "Wybierz stan towaru";
		$blad = true;
	}
	if (!isset($_POST["kurier-z-gory"]) && !isset($_POST["kurier-przy-odbiorze"]) && !isset($_POST["odbior-osobisty"]))
	{
		$_SESSION["tworzenieLicytacjiBladDostawa"] = "Wybierz przynajmniej jeden sposób dostawy";
		$blad = true;
	}
	elseif (isset($_POST["kurier-z-gory"]) && ($_POST["cena-kurier-z-gory"] < 0 || $_POST["cena-kurier-z-gory"] > 99999999.99))
	{
		$_SESSION["tworzenieLicytacjiBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["kurier-z-gory"]) && ($_POST["czas-kurier-z-gory"] < 0 || $_POST["czas-kurier-z-gory"] > 2147483647))
	{
		$_SESSION["tworzenieLicytacjiBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["kurier-przy-odbiorze"]) && ($_POST["cena-kurier-przy-odbiorze"] < 0 || $_POST["cena-kurier-przy-odbiorze"] > 99999999.99))
	{
		$_SESSION["tworzenieLicytacjiBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["kurier-przy-odbiorze"]) && ($_POST["czas-kurier-przy-odbiorze"] < 0 || $_POST["czas-kurier-przy-odbiorze"] > 2147483647))
	{
		$_SESSION["tworzenieLicytacjiBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["odbior-osobisty"]) && ($_POST["czas-odbior-osobisty"] < 0 || $_POST["czas-odbior-osobisty"] > 2147483647))
	{
		$_SESSION["tworzenieLicytacjiBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	if ($_POST["czas-na-zwrot"] < 14 || $_POST["czas-na-zwrot"] > 2147483647)
	{
		$_SESSION["tworzenieLicytacjiBladCzasNaZwrot"] = "Niedozwolona wartość (minimalny dozwolony czas na zwrot to 14)";
		$blad = true;
	}
	if ($_POST["adres-zwrotu"] == "")
	{
		$_SESSION["tworzenieLicytacjiBladAdresZwrotu"] = "Podaj adres do zwrotu";
		$blad = true;
	}
	if (!isset($_POST["koszty-zwrotu"]))
	{
		$_SESSION["tworzenieLicytacjiBladKosztyZwrotu"] = "Podaj, kto ponosi koszty przesyłki zwrotnej";
		$blad = true;
	}
	if ($_POST["gwarancja"] < 0 || $_POST["gwarancja"] == "")
	{
		$_SESSION["tworzenieLicytacjiBladGwarancja"] = "Niedozwolona wartość (jeżeli brak gwarancji wpisz 0)";
	}
	if ($blad)
	{
		Licytacja::zapamietajDane();
		header("Location: tworzenie_licytacji.php");
	}
	else
	{
		try
		{
			$idLicytacji = Licytacja::utworz($_SESSION["login"], $_POST["nazwa"], $_POST["cena"], $_POST["data-koniec"], $_POST["kategoria"], $_POST["opis"], $_POST["stan"], $kurierZGory, $cenaKurierZGory, $czasKurierZGory, $kurierPrzyOdbiorze, $cenaKurierPrzyOdbiorze, $czasKurierPrzyOdbiorze, $odbiorOsobisty, $czasOdbiorOsobisty, $_POST["opis-dostawa"], $_POST["czas-na-zwrot"], $_POST["adres-zwrotu"], $_POST["koszty-zwrotu"], $_POST["opis-zwroty"], $_POST["gwarancja"], $_POST["gwarancja-opis"]);
			$_SESSION["licytacjaUtworzona"] = true;
			try
			{
				Licytacja::zapiszZdjecia($_FILES["zdjecia"], $_SESSION["login"], $idLicytacji);
			}
			catch (Exception $e) {}
			header("Location: moje_licytacje.php?strona=1&trwajace=true");
		}
		catch (Exception $e)
		{
			header("Location: blad.php");
			exit();
		}
	}
?>