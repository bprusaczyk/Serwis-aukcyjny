<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	$_SESSION["filtrZamowieniaDoZrealizowania"] = Filtr::zamowieniaDoZrealizowania($_POST["filtr"]);
	$_SESSION["filtrZamowienia"]["filtr"] = $_POST["filtr"];
	header("Location: ".$_POST["powrot"]);
?>