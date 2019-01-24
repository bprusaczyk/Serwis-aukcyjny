<?php
	include_once("klasy/baza_danych.php");
	include_once("klasy/sposob_dostawy.php");
	include_once("klasy/uzytkownik.php");
	
	class Licytacja
	{
		public $cena;
		public $czas;
		public $czasNaZwrot;
		private $id;
		public $kosztyZwrotu;
		public $kurierPrzyOdbiorze;
		public $kurierZGory;
		public $liczbaSztuk;
		public $login;
		public $nazwa;
		public $odbiorOsobisty;
		public $okresGwarancji;
		public $opisDostawy;
		public $opisGwarancji;
		public $opisOferty;
		public $opisZwrotow;
		private $sredniaOcena;
		public $stan;
		public $typ;
		
		function __construct($id)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$zapytanie = $baza->query("SELECT nazwa, idUzytkownika, stan, dataZakonczenia, opisOferty, cenaKurierZGory, czasKurierZGory, cenaKurierPrzyOdbiorze, czasKurierPrzyOdbiorze, czasOdbiorOsobisty, opisDostawy, czasNaZwrot, adresDoZwrotu, opisZwrotow, opisGwarancji, okresGwarancji, kosztyZwrotu, typOferty, cena, liczbaSztuk FROM oferty WHERE idOferty = ".$id);
			foreach ($zapytanie as $wiersz)
			{
				$this->nazwa = htmlentities($wiersz["nazwa"]);
				$this->login = Uzytkownik::zwrocDaneOsobowePoId($wiersz["idUzytkownika"], "login");
				$this->stan = $wiersz["stan"];
				$this->cena = $wiersz["typOferty"] == "licytacja" ? Licytacja::zwrocWygrywajacaKwote($id) : $wiersz["cena"];
				$this->czas = $wiersz["dataZakonczenia"];
				$this->opisOferty = nl2br(htmlentities($wiersz["opisOferty"]));
				$this->kurierZGory = new SposobDostawy("Kurier (płatność z góry)", $wiersz["cenaKurierZGory"], $wiersz["czasKurierZGory"]);
				$this->kurierPrzyOdbiorze = new SposobDostawy("Kurier (płatność przy odbiorze)", $wiersz["cenaKurierPrzyOdbiorze"], $wiersz["czasKurierPrzyOdbiorze"]);
				$this->odbiorOsobisty = new SposobDostawy("Odbiór osobisty", null, $wiersz["czasOdbiorOsobisty"]);
				$this->opisDostawy = nl2br(htmlentities($wiersz["opisDostawy"]));
				$this->czasNaZwrot = $wiersz["czasNaZwrot"]." dni";
				$this->adresDoZwrotu = nl2br(htmlentities($wiersz["adresDoZwrotu"]));
				$this->opisZwrotow = nl2br(htmlentities($wiersz["opisZwrotow"]));
				$this->okresGwarancji = $wiersz["okresGwarancji"];
				$this->opisGwarancji = nl2br(htmlentities($wiersz["opisGwarancji"]));
				$this->kosztyZwrotu = $wiersz["kosztyZwrotu"];
				$this->sredniaOcena = Uzytkownik::zwrocSredniaOcenUzytkownika($wiersz["idUzytkownika"]);
				$this->id = $id;
				$this->typ = $wiersz["typOferty"];
				$this->liczbaSztuk = $wiersz["liczbaSztuk"];
			}
		}
		
		public static function sprawdzCzyLicytacjaJuzSieZakonczyla($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM oferty WHERE idOferty = :idOferty AND dataZakonczenia > now()");
			$st->bindParam(":idOferty", $idOferty);
			$st->execute();
			$wiersz = $st->fetch();
			if ($wiersz["count(*)"] == 1)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function sprawdzCzyLicytacjaZostalaOceniona($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM oceny WHERE idOferty = :idOferty");
			$st->bindParam(":idOferty", $idOferty);
			$st->execute();
			$wiersz = $st->fetch();
			if ($wiersz["count(*)"] == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public static function sprawdzCzyZostalWybranySposobDostawy($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT wybranySposobDostawy FROM oferty WHERE idOferty = :idOferty");
			$st->bindParam(":idOferty", $idOferty);
			$st->execute();
			$wiersz = $st->fetch();
			if ($wiersz["wybranySposobDostawy"] == "")
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function licytuj($idOferty, $kwota, $login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			if ($_SESSION["login"] == Licytacja::zwrocWygrywajacegoUzytkownika($idOferty))
			{
				Licytacja::zwiekszKwote($idOferty, $kwota);
				return;
			}
			$st = $baza->prepare("INSERT INTO kwoty VALUES (null, :idOferty, :kwota, (SELECT idUzytkownika from uzytkownicy WHERE login = :login))");
			$st->bindParam(":idOferty", $idOferty);
			$st->bindParam(":kwota", $kwota);
			$st->bindParam(":login", $login);
			$st->execute();
		}
		
		public static function oznaczZamowienieJakoPrzeczytane($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$stTest = $baza->prepare("SELECT count(*) FROM oferty WHERE idOferty = :idOferty AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login) AND wybranySposobDostawy IS NOT NULL");
			$stTest->bindParam(":idOferty", $idOferty);
			$stTest->bindParam(":login", $_SESSION["login"]);
			$stTest->execute();
			$test = $stTest->fetch();
			if ($test["count(*)"] == 0)
			{
				header("Location: index.php");
				exit();
			}
			$st = $baza->prepare("UPDATE oferty SET przeczytana = 1 WHERE idOferty = :idOferty");
			$st->bindParam(":idOferty", $idOferty);
			$st->execute();
		}
		
		public static function sprawdzCzyOfertaIstnieje($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM oferty WHERE idOferty = :idOferty");
			$st->bindParam(":idOferty", $idOferty);
			$st->execute();
			$wynik = $st->fetch();
			if ($wynik["count(*)"] == 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function usunOferte($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("DELETE FROM kwoty WHERE idOferty = :idOferty AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$_SESSION["login"]."')");
			$st->bindParam(":idOferty", $idOferty);
			$st->execute();
		}
		
		public static function utworz($login, $nazwa, $cena, $data, $kategoria, $opis, $stan, $czyKurierZGory, $cenaKurierZGory, $czasKurierZGory, $czyKurierPrzyOdbiorze, $cenaKurierPrzyOdbiorze, $czasKurierPrzyOdbiorze, $czyOdbiorOsobisty, $czasOdbiorOsobisty, $opisDostawy, $czasNaZwrot, $adresDoZwrotu, $kosztyZwrotu, $opisZwrotow, $okresGwarancji, $opisGwarancji)
		{
			$cenaKurierZGoryZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierZGory, "cenaKurierZGory");
			$czasKurierZGoryZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierZGory, "czasKurierZGory");
			$cenaKurierPrzyOdbiorzeZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierPrzyOdbiorze, "cenaKurierPrzyOdbiorze");
			$czasKurierPrzyOdbiorzeZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierPrzyOdbiorze, "czasKurierPrzyOdbiorze");
			$czasOdbiorOsobistyZmienna = Licytacja::zwrocCeneICzasDostawy($czyOdbiorOsobisty, "czasOdbiorOsobisty");
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("INSERT INTO oferty (idOferty, idUzytkownika, nazwa, dataZakonczenia, idKategorii, opisOferty, stan, cenaKurierZGory, czasKurierZGory, cenaKurierPrzyOdbiorze, czasKurierPrzyOdbiorze, czasOdbiorOsobisty, opisDostawy, czasNaZwrot, adresDoZwrotu, kosztyZwrotu, opisZwrotow, okresGwarancji, opisGwarancji, typOferty) VALUES (null, (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login), :nazwa, :data, (SELECT idKategorii FROM kategorie WHERE nazwa = :kategoria), :opis, :stan, ".$cenaKurierZGoryZmienna.", ".$czasKurierZGoryZmienna.", ".$cenaKurierPrzyOdbiorzeZmienna.", ".$czasKurierPrzyOdbiorzeZmienna.", ".$czasOdbiorOsobistyZmienna.", :opisDostawy, :czasNaZwrot, :adresDoZwrotu, :kosztyZwrotu, :opisZwrotow, :okresGwarancji, :opisGwarancji, 'licytacja')");
			$st->bindParam(":login", $login);
			$st->bindParam(":nazwa", $nazwa);
			$st->bindParam(":data", $data);
			$st->bindParam(":kategoria", $kategoria);
			$st->bindParam(":opis", $opis);
			$st->bindParam(":stan", $stan);
			if ($czyKurierZGory == "on")
			{
				$st->bindParam(":cenaKurierZGory", $cenaKurierZGory);
				$st->bindParam(":czasKurierZGory", $czasKurierZGory);
			}
			if ($czyKurierPrzyOdbiorze == "on")
			{
				$st->bindParam(":cenaKurierPrzyOdbiorze", $cenaKurierPrzyOdbiorze);
				$st->bindParam(":czasKurierPrzyOdbiorze", $czasKurierPrzyOdbiorze);
			}
			if ($czyOdbiorOsobisty == "on")
			{
				$st->bindParam(":czasOdbiorOsobisty", $czasOdbiorOsobisty);
			}
			$st->bindParam(":opisDostawy", $opisDostawy);
			$st->bindParam(":czasNaZwrot", $czasNaZwrot);
			$st->bindParam(":adresDoZwrotu", $adresDoZwrotu);
			$st->bindParam(":kosztyZwrotu", $kosztyZwrotu);
			$st->bindParam(":opisZwrotow", $opisZwrotow);
			$st->bindParam(":okresGwarancji", $okresGwarancji);
			$st->bindParam(":opisGwarancji", $opisGwarancji);
			$st->execute();
			$idLicytacji = $baza->lastInsertId();
			$st2 = $baza->prepare("INSERT INTO kwoty VALUES (null, ".$idLicytacji.", :cena, (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login))");
			$st2->bindParam(":cena", $cena);
			$st2->bindParam(":login", $login);
			$st2->execute();
			return $idLicytacji;
		}
		
		public static function wybierzSposobDostawy($idOferty, $login, $sposobDostawy)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$stTest = $baza->prepare("SELECT login FROM uzytkownicy WHERE idUzytkownika = (SELECT idUzytkownika FROM kwoty WHERE idOferty = :idOferty AND kwota = (SELECT max(kwota) FROM kwoty WHERE idOferty = :idOferty))");
			$stTest->bindParam(":idOferty", $idOferty);
			$stTest->execute();
			$wiersz = $stTest->fetch();
			if ($wiersz["login"] != $login)
			{
				header("Location: index.php");
				exit();
			}
			$st = $baza->prepare("UPDATE oferty SET wybranySposobDostawy = :sposobDostawy WHERE idOferty = :idOferty AND wybranySposobDostawy IS NULL");
			$st->bindParam(":idOferty", $idOferty);
			$st->bindParam(":sposobDostawy", $sposobDostawy);
			$st->execute();
		}
		
		public static function wypiszLicytacjeWKtorychUzytkownikBierzeUdzial($login)
		{
			$filtr = isset($_SESSION["filtrLicytacje"]) ? $_SESSION["filtrLicytacje"] : "";
			$filtr = str_replace("wAND", " AND", $filtr);
			$filtr = str_replace("pAND", " AND", $filtr);
			Licytacja::wypiszOferty("SELECT DISTINCT o.idOferty AS idOferty, o.nazwa AS nazwa, o.stan AS stan, o.dataZakonczenia AS dataZakonczenia, o.cenaKurierZGory AS cenaKurierZGory, o.cenaKurierPrzyOdbiorze AS cenaKurierPrzyOdbiorze, o.typOferty AS typOferty FROM oferty o, kwoty k WHERE o.idOferty = k.idOferty AND o.dataZakonczenia > now() AND o.idUzytkownika != (SELECT u.idUzytkownika FROM uzytkownicy u WHERE u.login = '".$login."') AND k.idUzytkownika = (SELECT u.idUzytkownika FROM uzytkownicy u WHERE u.login = '".$login."') ".$filtr." ORDER BY o.dataZakonczenia LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
		}
		
		public static function wypiszNumeryStron($zapytanie, $parametr, $strona)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$wynik = $baza->query($zapytanie);
			foreach ($wynik as $wiersz)
			{
				$liczbaMaks = ceil($wiersz["count(*)"] / 10);
			}
			for ($i = 1; $i <= $liczbaMaks; $i++)
			{
				$active = $_GET["strona"] == $i ? ' active' : '';
				echo '<li class="page-item'.$active.'">
							<a class="page-link" href="'.$strona.'?strona='.$i.'&'.$parametr.'">
								'.$i.'
							</a>
						</li>';
			}
		}
		
		public static function wypiszNumeryStronFraza($fraza)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty WHERE (dataZakonczenia > now() OR dataZakonczenia IS NULL) AND ((liczbaSztuk > 0 AND typOferty = 'kup teraz') OR typOferty = 'licytacja') AND nazwa LIKE '%".$fraza."%'".$filtr, "fraza=".$fraza, "szukaj.php");
		}
		
		public static function wypiszNumeryStronKategoria($idKategorii)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty WHERE (dataZakonczenia > now() OR dataZakonczenia IS NULL) AND ((liczbaSztuk > 0 AND typOferty = 'kup teraz') OR typOferty = 'licytacja') AND idKategorii = ".$idKategorii." ".$filtr, "kategoria=".$idKategorii, "szukaj.php");
		}
		
		public static function wypiszNumeryStronLicytacjiTrwajacych($login)
		{
			$filtr = isset($_SESSION["filtrLicytacje"]) ? $_SESSION["filtrLicytacje"] : "";
			Licytacja::wypiszNumeryStron( str_replace("pAND", " AND", str_replace("wAND", " AND", "SELECT count(DISTINCT o.idOferty) AS 'count(*)' FROM oferty o, kwoty k WHERE o.idOferty = k.idOferty AND o.dataZakonczenia > now() AND o.idUzytkownika != (SELECT u.idUzytkownika FROM uzytkownicy u WHERE u.login = '".$login."') AND k.idUzytkownika = (SELECT u.idUzytkownika FROM uzytkownicy u WHERE u.login = '".$login."') ".$filtr)), "trwajace=true", "licytacje.php");
		}
		
		public static function wypiszNumeryStronLicytacjiWygranych($login)
		{
			$filtr = isset($_SESSION["filtrLicytacjeZakonczone"]) ? $_SESSION["filtrLicytacjeZakonczone"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM kwoty k, oferty o WHERE k.idOferty = o.idOferty AND o.idUzytkownika != (SELECT u.idUzytkownika FROM uzytkownicy u WHERE u.login = '".$login."') AND k.kwota = (SELECT max(k1.kwota) FROM kwoty k1 WHERE k1.idOferty = o.idOferty AND k1.idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') AND o.dataZakonczenia < now()) ".$filtr, "wygrane=true", "licytacje.php");
		}
		
		public static function wypiszNumeryStronUzytkownikTrwajace($login)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty WHERE (dataZakonczenia > now() OR dataZakonczenia IS NULL) AND ((liczbaSztuk > 0 AND typOferty = 'kup teraz') OR typOferty = 'licytacja') AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') ".$filtr, "login=".$login."&oferty=true&typ=licytacje", "strona_uzytkownika.php");
		}
		
		public static function wypiszNumeryStronUzytkownikTrwajaceMojeLicytacje($login)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty WHERE dataZakonczenia > now() AND typOferty = 'licytacja' AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') ".$filtr, "trwajace=true", "moje_licytacje.php");
		}
		
		public static function wypiszNumeryStronUzytkownikZakonczone($login)
		{
			$filtr = isset($_SESSION["filtrZamowieniaDoZrealizowania"]) ? $_SESSION["filtrZamowieniaDoZrealizowania"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty WHERE dataZakonczenia < now() AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') AND typOferty = 'licytacja' ".$filtr, "zakonczone=true", "moje_licytacje.php");
		}
		
		public static function wypiszOferty($zapytanie)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$oferty = $baza->query($zapytanie);
			foreach ($oferty as $wiersz)
			{
				$brakDostawy = false;
				if ($wiersz["cenaKurierZGory"] == "" && $wiersz["cenaKurierPrzyOdbiorze"] == "")
				{
					$brakDostawy = true;
				}
				elseif ($wiersz["cenaKurierZGory"] == "")
				{
					$cenaDostawa = $wiersz["cenaKurierPrzyOdbiorze"];
				}
				elseif ($wiersz["cenaKurierPrzyOdbiorze"] == "")
				{
					$cenaDostawa = $wiersz["cenaKurierZGory"];
				}
				else
				{
					$cenaDostawa = $wiersz["cenaKurierZGory"] < $wiersz["cenaKurierPrzyOdbiorze"] ? $wiersz["cenaKurierZGory"] : $wiersz["cenaKurierPrzyOdbiorze"];
				}
				$informacjeODostawie = $brakDostawy ? '' :	'<p>Dostawa od: <strong>'.$cenaDostawa.' zł</strong></p>';
				$informacjeOStanie = $wiersz["stan"] == "" ? "" : '<p>Stan: <strong>'.$wiersz["stan"].'</strong></p>';
				echo '<div class="oferta">
							<div class="oferta-zdjecie">
								<div class="oferta-zdjecie-ramka"></div>
								<img src="'.Licytacja::zwrocSciezkeDoZdjecia($wiersz["idOferty"]).'" class="img-thumbnail"/>
								<div class="oferta-zdjecie-ramka"></div>
							</div>
							<div class="oferta-informacje">
								<a href="oferta.php?oferta='.$wiersz["idOferty"].'" class="oferta-informacje-nazwa">
									<h2>
										'.htmlentities($wiersz["nazwa"]).'
									</h2>
								</a>
								<p>
									Typ oferty: <strong>'.$wiersz["typOferty"].'</strong>
								</p>
								'.$informacjeOStanie.$informacjeODostawie.'
								<p>
									'.($wiersz["dataZakonczenia"] != "" ? 'Do końca aukcji pozostało: <strong>'.Licytacja::zwrocPozostalyCzas($wiersz["dataZakonczenia"]).'</strong>' : '').'
								</p>
								<h1>
									'.($wiersz["typOferty"] == "licytacja" ? Licytacja::zwrocWygrywajacaKwote($wiersz["idOferty"]) : $wiersz["cena"]).'
								</h1>
							</div>
							<div style="clear: both;"></div>
						</div>';
			}
		}
		
		public static function wypiszOfertyUzytkownikaTrwajace($login)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			$sortowanie = isset($_SESSION["sortowanieStandard"]) ? $_SESSION["sortowanieStandard"] : "";
			Licytacja::wypiszOferty("SELECT idOferty, nazwa, stan, dataZakonczenia, cenaKurierZGory, cenaKurierPrzyOdbiorze, cena, typOferty FROM oferty WHERE (dataZakonczenia > now() OR dataZakonczenia IS NULL) AND ((liczbaSztuk > 0 AND typOferty = 'kup teraz') OR typOferty = 'licytacja') AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') ".$filtr." ".$sortowanie." LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
		}
		
		public static function wypiszOfertyUzytkownikaTrwajaceMojeLicytacje($login)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			$sortowanie = isset($_SESSION["sortowanieStandard"]) ? $_SESSION["sortowanieStandard"] : "";
			Licytacja::wypiszOferty("SELECT idOferty, nazwa, stan, dataZakonczenia, cenaKurierZGory, cenaKurierPrzyOdbiorze, cena, typOferty FROM oferty WHERE dataZakonczenia > now() AND typOferty = 'licytacja' AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') ".$filtr." ".$sortowanie." LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
		}
		
		public static function wypiszOfertyUzytkownikaZakonczone($login)
		{
			$filtr = isset($_SESSION["filtrZamowieniaDoZrealizowania"]) ? $_SESSION["filtrZamowieniaDoZrealizowania"] : "";
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$oferty = $baza->query("SELECT idOferty, nazwa, wybranySposobDostawy, przeczytana FROM oferty WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') AND dataZakonczenia < now() AND typOferty = 'licytacja' ".$filtr." ORDER BY dataZakonczenia DESC LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
			foreach($oferty as $wiersz)
			{
				$tekst = null;
				if (Licytacja::zwrocWygrywajacegoUzytkownika($wiersz["idOferty"]) == $login)
				{
					$tekst = "Licytacja zakończyła się bez zwycięzcy";
				}
				elseif ($wiersz["wybranySposobDostawy"] == "")
				{
					$tekst = "Zwycięzca licytacji nie wybrał jeszcze sposobu dostawy";
				}
				elseif ($wiersz["wybranySposobDostawy"] == "Kurier (płatność z góry)")
				{
					$tekst = 'Wybrany sposób dostawy: <strong>przesyłka kurierska z płatnością z góry</strong><br>
								 Przesyłkę należy wysłać na adres:<br>
								 <strong>'.Uzytkownik::wypiszAdres(Uzytkownik::zwrocDaneOsobowe(Licytacja::zwrocWygrywajacegouzytkownika($wiersz["idOferty"]), "idUzytkownika")).'</strong>';
				}
				elseif ($wiersz["wybranySposobDostawy"] == "Kurier (płatność przy odbiorze)")
				{
					$tekst = 'Wybrany sposób dostawy: <strong>przesyłka kurierska z płatnością przy odbiorze</strong><br>
								 Przesyłkę należy wysłać na adres:<br>
								 <strong>'.Uzytkownik::wypiszAdres(Uzytkownik::zwrocDaneOsobowe(Licytacja::zwrocWygrywajacegouzytkownika($wiersz["idOferty"]), "idUzytkownika")).'</strong>';
				}
				elseif ($wiersz["wybranySposobDostawy"] == "Odbiór osobisty")
				{
					$tekst = 'Wybrany sposób dostawy: <strong>odbiór osobisty</strong><br>';
				}
				if ($wiersz["przeczytana"] == 0 && $wiersz["wybranySposobDostawy"] != "")
				{
					$form = '<form method="post" action="przeczytanie_zamowienia_licytacja_skrypt.php">
									<input type="hidden" name="id-oferty" value="'.$wiersz["idOferty"].'"/>
									<button type="submit" class="btn btn-info">
										Oznacz jako przeczytane
									</button>
								</form>';
				}
				else
				{
					$form = '';
				}
				$klasa = $wiersz["przeczytana"] == 1 ? " przeczytane" : "";
				echo '<div class="oferta'.$klasa.'">
							<a href="oferta.php?oferta='.$wiersz["idOferty"].'" class="oferta-informacje-nazwa">
								<h2>
									'.$wiersz["nazwa"].'
								</h2>
							</a>
							<p>
								'.$tekst.'
							</p>
							'.$form.'
						</div>';
			}
		}
		
		public static function wypiszOfertyWgFrazy($fraza)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			$sortowanie = isset($_SESSION["sortowanieStandard"]) ? $_SESSION["sortowanieStandard"] : "";
			Licytacja::wypiszOferty("SELECT idOferty, nazwa, cena, stan, dataZakonczenia, cenaKurierZGory, cenaKurierPrzyOdbiorze, typOferty FROM oferty WHERE (dataZakonczenia > now() OR dataZakonczenia IS NULL) AND ((liczbaSztuk > 0 AND typOferty = 'kup teraz') OR typOferty = 'licytacja') AND nazwa LIKE '%".$fraza."%' ".$filtr." ".$sortowanie." LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
		}
		
		public static function wypiszOfertyWgKategorii($idKategorii)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			$sortowanie = isset($_SESSION["sortowanieStandard"]) ? $_SESSION["sortowanieStandard"] : "";
			Licytacja::wypiszOferty("SELECT idOferty, nazwa, cena, stan, dataZakonczenia, cenaKurierZGory, cenaKurierPrzyOdbiorze, typOferty FROM oferty WHERE (dataZakonczenia > now() OR dataZakonczenia IS NULL) AND ((liczbaSztuk > 0 AND typOferty = 'kup teraz') OR typOferty = 'licytacja') AND idKategorii=".$idKategorii." ".$filtr." ".$sortowanie." LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
		}
		
		public function wypiszSredniaOcen()
		{
			$kolor = null;
			if ($this->sredniaOcena < 5)
			{
				$kolor = "red";
			}
			elseif ($this->sredniaOcena < 7)
			{
				$kolor = "#ffcc00";
			}
			else
			{
				$kolor = "green";
			}
			echo '<font color="'.$kolor.'">'.$this->sredniaOcena.'</font>';
		}
		
		public static function wypiszWygraneLicytacje($login)
		{
			$filtr = isset($_SESSION["filtrLicytacjeZakonczone"]) ? $_SESSION["filtrLicytacjeZakonczone"] : "";
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$licytacje = $baza->query("SELECT DISTINCT o.idOferty, o.nazwa, o.czasKurierZGory, o.cenaKurierZGory, o.czasKurierPrzyOdbiorze, o.cenaKurierPrzyOdbiorze, o.czasOdbiorOsobisty, o.wybranySposobDostawy, o.idUzytkownika FROM kwoty k, oferty o WHERE k.idOferty = o.idOferty AND o.idUzytkownika != (SELECT u.idUzytkownika FROM uzytkownicy u WHERE u.login = '".$login."') AND k.kwota = (SELECT max(k1.kwota) FROM kwoty k1 WHERE k1.idOferty = o.idOferty) AND k.idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') AND o.dataZakonczenia < now() ".$filtr." ORDER BY o.dataZakonczenia DESC LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
			$i = 0;
			foreach ($licytacje as $wiersz)
			{
				$kwota = Licytacja::zwrocWygrywajacaKwote($wiersz["idOferty"]);
				$kurierZGory = $wiersz["czasKurierZGory"] == "" ? "" : '<option>
																							Kurier (płatność z góry)
																						</option>';
				$kurierPrzyOdbiorze = $wiersz["czasKurierPrzyOdbiorze"] == "" ? "" : '<option>
																												Kurier (płatność przy odbiorze)
																											</option>';
				$odbiorOsobisty = $wiersz["czasOdbiorOsobisty"] == "" ? "" : '<option>
																									Odbiór osobisty
																								</option>';
				switch ($wiersz["wybranySposobDostawy"])
				{
					case "":
						$drugaCzesc = '<form method="post" action="wybieranie_sposobu_dostawy_skrypt.php">
												<input type="hidden" name="id-oferty" value="'.$wiersz["idOferty"].'"/>
												<div class="form-group">
													<label for="sposob-dostawy-'.$i.'">
														Wybiersz spoób dostawy
													</label>
													<select id="sposob-dostawy-'.$i.'" name="sposob-dostawy" class="form-control">
														<option disabled selected value>
															-- wybierz sposób dostawy --
														</option>
														'.$kurierZGory.$kurierPrzyOdbiorze.$odbiorOsobisty.'
													</select>
													<small id="sposob-dostawy-blad-'.$i.'" class="form-text text-muted walidacja-blad">
														'.(isset($_SESSION["wybieranieSposobuDostawyBlad"]) ? $_SESSION["wybieranieSposobuDostawyBlad"] : "").'
													</small>
												</div>
												<button type="submit" class="btn btn-success" onclick="return walidacjaWyborSposobuDostawy('.$i.');">
													Zatwierdź
												</button>
											</form>';
						if (isset($_SESSION["wybieranieSposobuDostawyBlad"]))
						{
							unset($_SESSION["wybieranieSposobuDostawyBlad"]);
						}
						break;
					case "Kurier (płatność z góry)":
						$nrKonta = Uzytkownik::zwrocDaneOsobowePoId($wiersz["idUzytkownika"], "numerKontaBankowego");
						$drugaCzesc = '<p>
												Wpłatę należy dokonać na konto
											</p>
											<h4>
												'.$nrKonta.'
											</h4>
											<p>
												Towar: <strong>'.$kwota.'</strong><br>
												Przesyłka: <strong>'.$wiersz["cenaKurierZGory"].'</strong><br>
												RAZEM: <strong>'.($kwota + $wiersz["cenaKurierZGory"]).'</strong>
											</p>';
						break;
					case "Kurier (płatność przy odbiorze)":
						$drugaCzesc = '<p>
												Towar: <strong>'.$kwota.'</strong><br>
												Przesyłka: <strong>'.$wiersz["cenaKurierPrzyOdbiorze"].'</strong><br>
												RAZEM: <strong>'.($kwota + $wiersz["cenaKurierPrzyOdbiorze"]).'</strong>
											</p>';
						break;
					case "Odbiór osobisty":
						$drugaCzesc = '<p>
												Towar można odebrać pod adresem:<br>
												<strong>
													'.Uzytkownik::wypiszAdres($wiersz["idUzytkownika"]).'
												</strong>
											</p>';
				}
				echo '<div class="oferta">
							<a href="oferta.php?oferta='.$wiersz["idOferty"].'" class="oferta-informacje-nazwa">
								<h2>
									'.htmlentities($wiersz["nazwa"]).'
								</h2>
							</a>
							<p>
								Kwota do zapłacenia:
								<h3>
									<strong>'.$kwota.'</strong>
								</h3>
							</p>
							'.$drugaCzesc.(Licytacja::sprawdzCzyLicytacjaZostalaOceniona($wiersz["idOferty"]) || $wiersz["wybranySposobDostawy"] == "" ? '' :'
							<form style="margin-top: 10px;" method="post" action="wystawianie_oceny_skrypt.php">
								<input name="id-oferty" type="hidden" value="'.$wiersz["idOferty"].'"/>
								<h6>
									Oceń użytkownika, z którym przeprowadziłeś transakcję
								</h6>
								<div class="form-group">
									<label for="ocena">
										Wystaw ocenę w skali 0 - 10
									</label>
									<h5 id="ocena-napis-'.$i.'">
										10
									</h5>
									<input id="ocena" name="ocena" type="range" class="form-control" min="0" max="10" value="10" oninput="wypiszOcene(this.value, \'ocena-napis-'.$i++.'\');">
									<small id="ocena-blad" class="form-text text-muted walidacja-blad"></small>
								</div>
								<div class="form-group">
									<label for="komentarz">
										Komentarz (opcjonalny)
									</label>
									<input id="komentarz" name="komentarz" class="form-control" type="text" placeholder="Komentarz (opcjonalny)"/>
								</div>
								<button type="submit" class="btn btn-success">
									Wystaw ocenę
								</button>
							</form>').'
						</div>';
			}
		}
		
		public static function zapamietajDane()
		{
			$_SESSION["staraNazwa"] = $_POST["nazwa"];
			$_SESSION["staraCena"] = $_POST["cena"];
			$_SESSION["staraDataKoniec"] = $_POST["data-koniec"];
			$_SESSION["staraKategoria"] = $_POST["kategoria"];
			$_SESSION["staryOpis"] = $_POST["opis"];
			$_SESSION["staryStan"] = $_POST["stan"];
			$_SESSION["staryKurierZGory"] = $_POST["kurier-z-gory"];
			$_SESSION["staryKurierPrzyOdbiorze"] = $_POST["kurier-przy-odbiorze"];
			$_SESSION["staryOdbiorOsobisty"] = $_POST["odbior-osobisty"];
			if (isset($_POST["kurier-z-gory"]))
			{
				$_SESSION["staraCenaKurierZGory"] = $_POST["cena-kurier-z-gory"];
				$_SESSION["staryCzasKurierZGory"] = $_POST["czas-kurier-z-gory"];
			}
			if (isset($_POST["kurier-przy-odbiorze"]))
			{
				$_SESSION["staraCenaKurierPrzyOdbiorze"] = $_POST["cena-kurier-przy-odbiorze"];
				$_SESSION["staryCzasKurierPrzyOdbiorze"] = $_POST["czas-kurier-przy-odbiorze"];
			}
			if (isset($_POST["odbior-osobisty"]))
			{
				$_SESSION["staryCzasOdbiorOsobisty"] = $_POST["czas-odbior-osobisty"];
			}
			$_SESSION["staryOpisDostawa"] = $_POST["opis-dostawa"];
			$_SESSION["staryAdresZwrotu"] = $_POST["adres-zwrotu"];
			$_SESSION["stareKosztyZwrotu"] = $_POST["koszty-zwrotu"];
			$_SESSION["staryOpisZwroty"] = $_POST["opis-zwroty"];
			$_SESSION["staraGwarancja"] = $_POST["gwarancja"];
			$_SESSION["staraGwarancjaOpis"] = $_POST["gwarancja-opis"];
		}
		
		public static function zapiszZdjecia($zdjecia, $login, $idLicytacji)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("INSERT INTO zdjecia VALUES (null, ".$idLicytacji.", :sciezka)");
			$nazwyTmp = $zdjecia["tmp_name"];
			$nazwyOrg = $zdjecia["name"];
			if ($zdjecia["name"][0] == "")
			{
				return;
			}
			for ($i = 0; $i < count($nazwyTmp); $i++)
			{
				$nazwaPliku = $login.$idLicytacji.date("YmdHis").$i.$nazwyOrg[$i];
				move_uploaded_file($nazwyTmp[$i], "C:/xampp/htdocs/zdjecia/".$nazwaPliku);
				$st->bindParam(":sciezka", $nazwaPliku);
				$st->execute();
			}
		}
		
		private static function zwiekszKwote($idOferty, $kwota)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE kwoty SET kwota = :kwota WHERE idOferty = :idOferty AND kwota = (SELECT max(kwota) FROM (SELECT * FROM kwoty) AS t WHERE idOferty = :idOferty)");
			$st->bindParam(":kwota", $kwota);
			$st->bindParam(":idOferty", $idOferty);
			$st->execute();
		}
		
		public static function zwrocCeneICzasDostawy($checkbox, $zmienna)
		{
			if ($checkbox == "on")
			{
				return ":".$zmienna;
			}
			else
			{
				return "null";
			}
		}
		
		public static function zwrocLiczbeNieprzeczytanychZamowien($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM oferty WHERE dataZakonczenia < now() AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login) AND wybranySposobDostawy IS NOT NULL AND przeczytana = 0");
			$st->bindParam(":login", $login);
			$st->execute();
			$wynik = $st->fetch();
			if ($wynik["count(*)"] == 0)
			{
				return "";
			}
			else
			{
				return " (".$wynik["count(*)"].")";
			}
		}
		
		public static function zwrocLiczbeWszystkichNieprzeczytanychZamowien($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$stLicytacje = $baza->prepare("SELECT count(*) FROM oferty WHERE dataZakonczenia < now() AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login) AND wybranySposobDostawy IS NOT NULL AND przeczytana = 0");
			$stLicytacje->bindParam(":login", $login);
			$stLicytacje->execute();
			$wynikLicytacje = $stLicytacje->fetch();
			$czescLicytacje = $wynikLicytacje["count(*)"];
			$stKupTeraz = $baza->prepare("SELECT count(*) FROM oferty o, zamowienia z WHERE o.idOferty = z.idOferty AND z.wybranySposobDostawy IS NOT NULL AND o.idUzytkownika = (SELECT u1.idUzytkownika FROM uzytkownicy u1 WHERE login = :login) AND z.przeczytane = 0");
			$stKupTeraz->bindParam(":login", $login);
			$stKupTeraz->execute();
			$wynikKupTeraz = $stKupTeraz->fetch();
			$czescKupTeraz = $wynikKupTeraz["count(*)"];
			if ($czescLicytacje + $czescKupTeraz == 0)
			{
				return "";
			}
			else
			{
				return " (".($czescLicytacje + $czescKupTeraz).")";
			}
		}
		
		public function zwrocNajwiekszaKwote()
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT max(kwota) FROM kwoty WHERE idOferty = :id");
			$st->bindParam(":id", $this->id);
			$st->execute();
			$wiersz = $st->fetch();
			return $wiersz["max(kwota)"];
		}
		
		public function zwrocOpisStanuLicytacji()
		{
			$loginWygrywajacego = Licytacja::zwrocWygrywajacegoUzytkownika($this->id);
			if (date("Y-m-d H:i:s") > $this->czas && $this->login != $loginWygrywajacego)
			{
				echo "Licytację wygrał użytkownik <strong>".$loginWygrywajacego."</strong>";
			}
			elseif (date("Y-m-d H:i:s") > $this->czas && $this->login == $loginWygrywajacego)
			{
				echo "Licytacja zakończyła się bez zwycięzcy";
			}
			elseif ($this->login == $loginWygrywajacego)
			{
				echo "Nikt jescze nie licytuje";
			}
			elseif (isset($_SESSION["login"]) && $_SESSION["login"] == $loginWygrywajacego)
			{
				$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
				$zapytanie = $baza->query("SELECT max(kwota) FROM kwoty WHERE idOferty = ".$this->id);
				foreach ($zapytanie as $wiersz)
				{
					$kwota = $wiersz["max(kwota)"];
				}
				echo "Twoja oferta nie została jeszcze przebita<br>Twoja maksymalna oferta wynosi <strong>".$kwota."</strong>";
			}
			else
			{
				echo "Najwyższą ofertę zaproponował użytkownik <strong>".$loginWygrywajacego."</strong>";
			}
		}
		
		private static function zwrocPozostalyCzas($data)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$roznica = strtotime($data) - time();
			if (round($roznica / (60 * 60)) == 0)
			{
				return round($roznica / 60)." minut";
			}
			elseif (round($roznica / (60 * 60 * 24)) == 0)
			{
				return round($roznica / (60 * 60))." godzin";
			}
			else
			{
				return round($roznica / (60 * 60 * 24))." dni";
			}
		}
		
		public static function zwrocSciezkeDoZdjecia($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$sciezka = $baza->query("SELECT sciezka FROM zdjecia WHERE idOferty = ".$idOferty." LIMIT 1");
			foreach ($sciezka as $wiersz)
			{
				$wynik = $wiersz["sciezka"];
			}
			if (!isset($wynik))
			{
				return "zdjecia/Brak_zdjęcia.svg";
			}
			else
			{
				return "zdjecia\\".$wynik;
			}
		}
		
		public static function zwrocWygrywajacaKwote($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$zapytanie = $baza->query("SELECT kwota FROM kwoty WHERE idOferty = ".$idOferty." ORDER BY kwota DESC LIMIT 2");
			$licznik = 0;
			foreach ($zapytanie as $wiersz)
			{
				if ($licznik == 0)
				{
					$kwotaMaks1 = $wiersz["kwota"];
					$licznik++;
				}
				else
				{
					$kwotaMaks2 = $wiersz["kwota"];
				}
			}
			if (!isset($kwotaMaks2) || $kwotaMaks2 + 0.01 > $kwotaMaks1)
			{
				return $kwotaMaks1;
			}
			else
			{
				return $kwotaMaks2 + 0.01;
			}
		}
		
		public static function zwrocWygrywajacegoUzytkownika($idOferty)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$zapytanie = $baza->query("SELECT u.login FROM kwoty k, uzytkownicy u WHERE k.idUzytkownika = u.idUzytkownika and k.kwota = (SELECT max(k1.kwota) FROM kwoty k1 WHERE idOferty = ".$idOferty.") AND idOferty = ".$idOferty);
			foreach ($zapytanie as $wiersz)
			{
				$wynik = $wiersz["login"];
			}
			return $wynik;
		}
		
		public static function zwrocZdjecia($idOferty)
		{
			$zdjecia = array();
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$wynik = $baza->query("SELECT sciezka FROM zdjecia WHERE idOferty = ".$idOferty);
			foreach ($wynik as $wiersz)
			{
				array_push($zdjecia, "zdjecia/".$wiersz["sciezka"]);
			}
			return $zdjecia;
		}
	}
?>