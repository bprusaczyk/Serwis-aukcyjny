<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	Filtr::wyczyscSortowanieOcen();
	header("Location: ".$_POST["powrot"]);
?>