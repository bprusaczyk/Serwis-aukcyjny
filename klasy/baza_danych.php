<?php
    define("DANE_BAZY", "mysql:host=localhost;dbname=serwis_aukcyjny");
    define("UZYTKOWNIK_BAZY", "root");
    define("HASLO_BAZY", "");
	
	class BazaDanych
	{
		public static function ustawKodowanie($pdo)
		{
			$pdo->query("SET NAMES utf8");
			$pdo->query("SET CHARACTER_SET utf8_unicode_ci");
		}
	}
?>