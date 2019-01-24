<?php
	include_once("klasy/baza_danych.php");
	
	class Kategoria
	{
		public static function zwrocNazweKategorii($id)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$wynik = $baza->query("SELECT nazwa FROM kategorie WHERE idKategorii = ".$id);
			foreach ($wynik as $wiersz)
			{
				$nazwa = $wiersz["nazwa"];
			}
			return $nazwa;
		}
	}
?>