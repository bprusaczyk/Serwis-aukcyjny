<?php
	include_once("klasy/baza_danych.php");
	include_once("klasy/licytacja.php");
	
	class Ocena
	{
		public $wartosc;
		public $komentarz;
		
		function __construct($wartosc, $komentarz)
		{
			$this->wartosc = $wartosc;
			$this->komentarz = $komentarz;
		}
		
		public function wypiszNumeryStronOceny($login)
		{
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty of, oceny oc, uzytkownicy u WHERE of.idOferty = oc.idOferty AND of.idUzytkownika = u.idUzytkownika AND login = '".$login."'", "login=".$login."&oceny=true", "strona_uzytkownika.php");
		}
		
		public static function wypiszOceny($login)
		{
			$sortowanie = isset($_SESSION["sortowanieOceny"]) ? $_SESSION["sortowanieOceny"] : " ORDER BY oc.idOceny DESC ";
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$oceny = $baza->query("SELECT oc.idOferty, oc.ocena, oc.komentarz, of.nazwa FROM oferty of, oceny oc, uzytkownicy u WHERE of.idOferty = oc.idOferty AND of.idUzytkownika = u.idUzytkownika AND login = '".$login."' ".$sortowanie." LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
			foreach ($oceny as $wiersz)
			{
				$klasa = null;
				if ($wiersz["ocena"] < 5)
				{
					$klasa = "komentarz-negatywny";
				}
				elseif ($wiersz["ocena"] < 7)
				{
					$klasa = "komentarz-neutralny";
				}
				else
				{
					$klasa = "komentarz-pozytywny";
				}
				$komentarz = $wiersz["komentarz"] == "" ? "" : 'Komentarz:<br>
																					'.htmlentities($wiersz["komentarz"]);
				echo '<div class="komentarz '.$klasa.'">
							<a class="komentarz-link" href="oferta.php?oferta='.$wiersz["idOferty"].'"><strong>'.$wiersz["nazwa"].'</strong></a><br>
							Ocena: '.$wiersz["ocena"].'/10<br>'.$komentarz.'
						</div>';
			}
		}
		
		public function wystaw($idOferty)
		{
			if ($_SESSION["login"] != Licytacja::zwrocWygrywajacegoUzytkownika($idOferty))
			{
				header("Location: index.php");
				exit();
			}
			if (!Licytacja::sprawdzCzyLicytacjaJuzSieZakonczyla($idOferty))
			{
				header("Location: index.php");
				exit();
			}
			if (Licytacja::sprawdzCzyLicytacjaZostalaOceniona($idOferty))
			{
				header("Location: index.php");
				exit();
			}
			if (!Licytacja::sprawdzCzyZostalWybranySposobDostawy($idOferty))
			{
				header("Location: index.php");
				exit();
			}
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("INSERT INTO oceny VALUES (null, :idOferty, :ocena, :komentarz, null)");
			$st->bindParam(":idOferty", $idOferty);
			$st->bindParam(":ocena", $this->wartosc);
			$st->bindParam(":komentarz", $this->komentarz);
			$st->execute();
		}
		
		public function wystawKupTeraz($idOferty, $idZamowienia)
		{
			if ($_SESSION["login"] != KupTeraz::zwrocLoginZamawiajacego($idZamowienia))
			{
				header("Location: index.php");
				exit();
			}
			if (KupTeraz::sprawdzCzyZamowienieZostaloOcenione($idZamowienia))
			{
				header("Location: index.php");
				exit();
			}
			if (!KupTeraz::sprawdzCzyZostalWybranySposobDostawy($idZamowienia))
			{
				header("Location: index.php");
				exit();
			}
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("INSERT INTO oceny VALUES (null, :idOferty, :ocena, :komentarz, :idZamowienia)");
			$st->bindParam(":idOferty", $idOferty);
			$st->bindParam(":ocena", $this->wartosc);
			$st->bindParam(":komentarz", $this->komentarz);
			$st->bindParam(":idZamowienia", $idZamowienia);
			$st->execute();
		}
	}
?>