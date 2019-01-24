<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	Filtr::wyczyscFiltryHistoriaZakupow();
	header("Location: ".$_POST["powrot"]);
?>