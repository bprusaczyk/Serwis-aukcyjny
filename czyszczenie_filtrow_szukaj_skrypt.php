<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	Filtr::wyczyscFiltrySzukaj();
	header("Location: ".$_POST["powrot"]);
?>