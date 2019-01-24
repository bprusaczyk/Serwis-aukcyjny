<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	$_SESSION["filtrLicytacje"] = Filtr::Licytacje($_POST["filtr"]);
	$_SESSION["filtrLicytacjeUdzial"]["filtr"] = $_POST["filtr"];
	header("Location: ".$_POST["powrot"]);
?>