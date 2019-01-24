<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	Filtr::wyczyscFiltryZamowieniaDoZrealizowania();
	header("Location: ".$_POST["powrot"]);
?>