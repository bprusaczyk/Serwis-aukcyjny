<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	Filtr::wyczyscFiltryLicytacjeTrwajace();
	header("Location: ".$_POST["powrot"]);
?>