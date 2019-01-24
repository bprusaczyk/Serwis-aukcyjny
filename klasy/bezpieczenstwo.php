<?php
	class Bezpieczenstwo
	{
		public static function zablokujDostepNiezalogowanemuUzytkownikowi()
		{
			if (!isset($_SESSION["zalogowany"]) || $_SESSION["zalogowany"] == false)
			{
				header("Location: logowanie.php");
				exit();
			}
		}
		
		public static function zablokujDostepZalogowanemuUzytkownikowi()
		{
			if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true)
			{
				header("Location: index.php");
				exit();
			}
		}
		
		public static function zablokujDostepZInnychStron($adres)
		{
			if (!isset($_SERVER["HTTP_REFERER"]) || $_SERVER["HTTP_REFERER"] != $adres)
			{
				header("Location: index.php");
				exit();
			}
		}
		
		public static function zablokujDostepZInnychStronRegex($regex)
		{
			if (!isset($_SERVER["HTTP_REFERER"]) || preg_match($regex, $_SERVER["HTTP_REFERER"]) == 0)
			{
				header("Location: index.php");
				exit();
			}
		}
	}
?>