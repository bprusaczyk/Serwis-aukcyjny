<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/kup_teraz.php");
	include_once("klasy/licytacja.php");
	session_start();
	Bezpieczenstwo::zablokujDostepZInnychStron("http://localhost/tworzenie_kup_teraz.php");
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
		$_SESSION["tworzenieKupTerazBladNazwa"] = "Podaj nazwę oferty";
		$blad = true;
	}
	if ($_POST["cena"] <= 0 || $_POST["cena"] > 99999999.99)
	{
		$_SESSION["tworzenieKupTerazBladCena"] = "Niedozwolona wartość ceny";
		$blad = true;
	}
	if ($_POST["liczba-sztuk"] <= 0 || $_POST["liczba-sztuk"] > 2147483647)
	{
		$_SESSION["tworzenieKupTerazBladLiczbaSztuk"] = "Niedozwolona wartość";
		$blad = true;
	}
	if ($_POST["kategoria"] == "")
	{
		$_SESSION["tworzenieKupTerazBladKategoria"] = "Wybierz kategorię oferty";
		$blad = true;
	}
	if ($_POST["kategoria"] != "Artykuły spożywcze" && !isset($_POST["stan"]))
	{
		$_SESSION["tworzenieKupTerazBladStan"] = "Wybierz stan towaru";
		$blad = true;
	}
	if (!isset($_POST["kurier-z-gory"]) && !isset($_POST["kurier-przy-odbiorze"]) && !isset($_POST["odbior-osobisty"]))
	{
		$_SESSION["tworzenieKupTerazBladDostawa"] = "Wybierz przynajmniej jeden sposób dostawy";
		$blad = true;
	}
	elseif (isset($_POST["kurier-z-gory"]) && ($_POST["cena-kurier-z-gory"] < 0 || $_POST["cena-kurier-z-gory"] > 99999999.99))
	{
		$_SESSION["tworzenieKupTerazBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["kurier-z-gory"]) && ($_POST["czas-kurier-z-gory"] < 0 || $_POST["czas-kurier-z-gory"] > 2147483647))
	{
		$_SESSION["tworzenieKupTerazBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["kurier-przy-odbiorze"]) && ($_POST["cena-kurier-przy-odbiorze"] < 0 || $_POST["cena-kurier-przy-odbiorze"] > 99999999.99))
	{
		$_SESSION["tworzenieKupTerazBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["kurier-przy-odbiorze"]) && ($_POST["czas-kurier-przy-odbiorze"] < 0 || $_POST["czas-kurier-przy-odbiorze"] > 2147483647))
	{
		$_SESSION["tworzenieKupTerazBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	elseif (isset($_POST["odbior-osobisty"]) && ($_POST["czas-odbior-osobisty"] < 0 || $_POST["czas-odbior-osobisty"] > 2147483647))
	{
		$_SESSION["tworzenieKupTerazBladDostawa"] = "Niedozwolona wartość";
		$blad = true;
	}
	if ($_POST["czas-na-zwrot"] < 14 || $_POST["czas-na-zwrot"] > 2147483647)
	{
		$_SESSION["tworzenieKupTerazBladCzasNaZwrot"] = "Niedozwolona wartość (minimalny dozwolony czas na zwrot to 14)";
		$blad = true;
	}
	if ($_POST["adres-zwrotu"] == "")
	{
		$_SESSION["tworzenieKupTerazBladAdresZwrotu"] = "Podaj adres do zwrotu";
		$blad = true;
	}
	if (!isset($_POST["koszty-zwrotu"]))
	{
		$_SESSION["tworzenieKupTerazBladKosztyZwrotu"] = "Podaj, kto ponosi koszty przesyłki zwrotnej";
		$blad = true;
	}
	if ($_POST["gwarancja"] < 0 || $_POST["gwarancja"] == "")
	{
		$_SESSION["tworzenieKupTerazBladGwarancja"] = "Niedozwolona wartość (jeżeli brak gwarancji wpisz 0)";
	}
	if ($blad)
	{
		KupTeraz::zapamietajDane();
		header("Location: tworzenie_kup_teraz.php");
	}
	else
	{
		try
		{
			$idOferty = KupTeraz::utworz($_SESSION["login"], $_POST["nazwa"], $_POST["cena"], $_POST["liczba-sztuk"], $_POST["data-koniec"], $_POST["kategoria"], $_POST["opis"], $_POST["stan"], $kurierZGory, $cenaKurierZGory, $czasKurierZGory, $kurierPrzyOdbiorze, $cenaKurierPrzyOdbiorze, $czasKurierPrzyOdbiorze, $odbiorOsobisty, $czasOdbiorOsobisty, $_POST["opis-dostawa"], $_POST["czas-na-zwrot"], $_POST["adres-zwrotu"], $_POST["koszty-zwrotu"], $_POST["opis-zwroty"], $_POST["gwarancja"], $_POST["gwarancja-opis"]);
			$_SESSION["kupTerazUtworzone"] = true;
			Licytacja::zapiszZdjecia($_FILES["zdjecia"], $_SESSION["login"], $idOferty);
			header("Location: moje_kup_teraz.php?strona=1");
		}
		catch (Exception $e)
		{
			header("Location: blad.php");
			exit();
		}
	}
?>