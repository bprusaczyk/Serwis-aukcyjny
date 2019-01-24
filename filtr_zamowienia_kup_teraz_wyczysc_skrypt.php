<?php
	include_once("filtry/klasy/filtr.php");
	session_start();
	Filtr::wyczyscFiltryZamowieniaDoZrealizowaniaKupTeraz();
	header("Location: ".$_POST["powrot"]);
?>