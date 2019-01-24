<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	$_SESSION["filtrHistoriaZakupow"] = Filtr::historiaZakupow($_POST["filtr"]);
	$_SESSION["filtryHistoriaZakupow"]["filtr"] = $_POST["filtr"];
	header("Location: ".$_POST["powrot"]);
?>