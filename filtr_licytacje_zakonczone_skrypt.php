<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	$_SESSION["filtrLicytacjeZakonczone"] = Filtr::licytacjeZakonczone($_POST["filtr"]);
	$_SESSION["filtryLicytacjeWygrane"]["filtr"] = $_POST["filtr"];
	header("Location: ".$_POST["powrot"]);
?>