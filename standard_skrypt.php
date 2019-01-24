<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	$kurierZGory = isset($_POST["kurier-z-gory"]) ? true : false;
	$kurierPrzyOdbiorze = isset($_POST["kurier-przy-odbiorze"]) ? true : false;
	$odbiorOsobisty = isset($_POST["odbior-osobisty"]) ? true : false;
	$darmowaDostawa = isset($_POST["darmowa-dostawa"]) ? true : false;
	$_SESSION["filtrStandard"] = Filtr::standard($_POST["rodzaj-oferty"], $_POST["cena-min"], $_POST["cena-max"], $_POST["stan"], $kurierZGory, $kurierPrzyOdbiorze, $odbiorOsobisty, $darmowaDostawa);
	$_SESSION["filtrSzukaj"]["typOfertySzukaj"] = $_POST["rodzaj-oferty"];
	$_SESSION["filtrSzukaj"]["cenaMinSzukaj"] = $_POST["cena-min"];
	$_SESSION["filtrSzukaj"]["cenaMaxSzukaj"] = $_POST["cena-max"];
	$_SESSION["filtrSzukaj"]["stanSzukaj"] = $_POST["stan"];
	$_SESSION["filtrSzukaj"]["kurierZGory"] = $kurierZGory;
	$_SESSION["filtrSzukaj"]["kurierPrzyOdbiorze"] = $kurierPrzyOdbiorze;
	$_SESSION["filtrSzukaj"]["odbiorOsobisty"] = $odbiorOsobisty;
	$_SESSION["filtrSzukaj"]["darmowaDostawa"] = $darmowaDostawa;
	$sortowanieKryterium = isset($_POST["sortowanie-kryterium"]) ? $_POST["sortowanie-kryterium"] : null;
	$sortowaniePorzadek = isset($_POST["sortowanie-porzadek"]) ? $_POST["sortowanie-porzadek"] : null;
	$_SESSION["sortowanieStandard"] = Filtr::sortowanie($sortowanieKryterium, $sortowaniePorzadek);
	$_SESSION["filtrSzukaj"]["sortowanieKryterium"] = $sortowanieKryterium;
	$_SESSION["filtrSzukaj"]["sortowaniePorzadek"] = $sortowaniePorzadek;
	header("Location: ".$_POST["powrot"]);
?>