<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	Filtr::wyczyscFiltryLicytacjeWygrane();
	header("Location: ".$_POST["powrot"]);
?>