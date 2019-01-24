<?php
	include_once("klasy/baza_danych.php");
	include_once("klasy/licytacja.php");
	
	class Zamowienie
	{
		public $liczbaSztuk;
		public $oferta;
		
		function __construct($id)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			$st = $baza->prepare("SELECT * FROM zamowienia WHERE idZamowienia = :id");
			$st->bindParam(":id", $id);
			$st->execute();
			$zamowienie = $st->fetch();
			$this->oferta = new Licytacja($zamowienie["idOferty"]);
			$this->liczbaSztuk = $zamowienie["liczbaSztuk"];
		}
	}
?>