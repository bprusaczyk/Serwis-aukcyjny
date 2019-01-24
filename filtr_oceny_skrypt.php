<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	$_SESSION["sortowanieOceny"] = Filtr::oceny($_POST["sortowanie-porzadek"]);
	$_SESSION["filtryOceny"]["sortowanie"] = $_POST["sortowanie-porzadek"];
	header("Location: ".$_POST["powrot"]);
?>