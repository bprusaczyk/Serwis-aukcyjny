<?php
	class Filtr
	{
		public static function historiaZakupow($filtr)
		{
			$wynik = "";
			if ($filtr != "")
			{
				switch ($filtr)
				{
					case "wszystkie":
						break;
					case "nieocenione":
						$wynik = " AND (SELECT count(*) FROM oceny oc WHERE oc.idZamowienia = z.idZamowienia) = 0 ";
						break;
				}
			}
			return $wynik;
		}
		
		public static function licytacje($filtr)
		{
			$wynik = " AND typOferty='licytacja'";
			if ($filtr != "")
			{
				switch ($filtr)
				{
					case "wszystkie":
						break;
					case "wygrywasz":
						$wynik = " AND (SELECT u1.login FROM uzytkownicy u1 WHERE u1.idUzytkownika = (SELECT k1.idUzytkownika FROM kwoty k1 WHERE k1.idOferty = o.idOferty AND k1.kwota = (SELECT max(k2.kwota) FROM kwoty k2 WHERE k2.idOferty = o.idOferty))) = '".$_SESSION["login"]."' ";
						break;
					case "przegrywasz":
						$wynik = " AND (SELECT u1.login FROM uzytkownicy u1 WHERE u1.idUzytkownika = (SELECT k1.idUzytkownika FROM kwoty k1 WHERE k1.idOferty = o.idOferty AND k1.kwota = (SELECT max(k2.kwota) FROM kwoty k2 WHERE k2.idOferty = o.idOferty))) != '".$_SESSION["login"]."' ";
						break;
				}
			}
			return $wynik;
		}
		
		public static function licytacjeZakonczone($filtr)
		{
			$wynik = "";
			if ($filtr != "")
			{
				switch ($filtr)
				{
					case "wszystkie":
						break;
					case "potwierdzone":
						$wynik = " AND o.wybranySposobDostawy IS NOT NULL ";
						break;
					case "niepotwierdzone":
						$wynik = " AND o.wybranySposobDostawy IS NULL ";
						break;
					case "nieocenione":
						$wynik = " AND (SELECT count(*) FROM oceny oc WHERE oc.idOferty = o.idOferty) = 0 ";
						break;
				}
			}
			return $wynik;
		}
		
		public static function oceny($kryterium)
		{
			$wynik = " ORDER BY idOceny DESC ";
			if ($kryterium != "")
			{
				switch ($kryterium)
				{
					case "rosnąco":
						$wynik = " ORDER BY oc.ocena ASC ";
						break;
					case "malejąco":
						$wynik = " ORDER BY oc.ocena DESC ";
						break;
				}
			}
			return $wynik;
		}
		
		public static function sortowanie($kryterium, $porzadek)
		{
			if ($kryterium == null)
			{
				return "";
			}
			switch ($kryterium)
			{
				case "cena":
					$czesc1 = " ORDER BY ifnull(cena, (SELECT max(k.kwota) FROM kwoty k WHERE k.idOferty = oferty. idOferty)) ";
					break;
				case "pozostały czas":
					$czesc1 = " ORDER BY datediff(ifnull(dataZakonczenia, '3000/01/01'), now()) ";
					break;
				case "cena z dostawą":
					$czesc1 = " ORDER BY (ifnull(cena, (SELECT max(k.kwota) FROM kwoty k WHERE k.idOferty = oferty. idOferty)) + ifnull(least(cenaKurierZGory, cenaKurierPrzyOdbiorze), ifnull(cenaKurierZGory, ifnull(cenaKurierPrzyOdbiorze, 0))))";
					break;
			}
			switch ($porzadek)
			{
				case "rosnąco":
					$czesc2 = " ASC ";
					break;
				case "malejąco":
					$czesc2 = " DESC ";
					break;
			}
			return $czesc1.$czesc2;
		}
		
		public static function standard($typOferty, $cenaMin, $cenaMax, $stan, $kurierZGory, $kurierPrzyOdbiorze, $odbiorOsobisty, $darmowaDostawa)
		{
			$wynikTyp = "";
			if ($typOferty != "")
			{
				switch ($typOferty)
				{
					case "wszystkie":
						$wynikTyp = "";
						break;
					case "kup teraz":
						$wynikTyp = " AND typOferty = 'kup teraz' ";
						break;
					case "licytacje":
						$wynikTyp = " AND typOferty = 'licytacja' ";
						break;
				}
			}
			$wynikCena = "";
			if ($cenaMin != "" || $cenaMax != "")
			{
				if ($cenaMin == "")
				{
					$wynikCena = " AND ((typOferty = 'kup teraz' AND cena < ".$cenaMax.") OR (typOferty = 'licytacja' AND (SELECT max(k.kwota) FROM kwoty k WHERE k.idOferty = oferty.idOferty) < ".$cenaMax.")) ";
				}
				elseif ($cenaMax == "")
				{
					$wynikCena = " AND ((typOferty = 'kup teraz' AND cena > ".$cenaMin.") OR (typOferty = 'licytacja' AND (SELECT max(k.kwota) FROM kwoty k WHERE k.idOferty = oferty.idOferty) > ".$cenaMin.")) ";
				}
				else
				{
					$wynikCena = " AND ((typOferty = 'kup teraz' AND cena BETWEEN ".$cenaMin." AND ".$cenaMax.") OR (typOferty = 'licytacja' AND (SELECT max(k.kwota) FROM kwoty k WHERE k.idOferty = oferty.idOferty) BETWEEN ".$cenaMin." AND ".$cenaMax.")) ";
				}
			}
			$wynikStan = "";
			if ($stan != "")
			{
				switch ($stan)
				{
					case "wszystkie":
						$wynikStan = "";
						break;
					case "nowe":
						$wynikStan = " AND stan = 'nowy' ";
						break;
					case "używane":
						$wynikStan = " AND stan = 'używany' ";
						break;
				}
			}
			$wynikKurierZGory = "";
			if ($kurierZGory)
			{
				$wynikKurierZGory = " AND czasKurierZGory IS NOT NULL ";
			}
			$wynikKurierPrzyOdbiorze = "";
			if ($kurierPrzyOdbiorze)
			{
				$wynikKurierPrzyOdbiorze = " AND czasKurierPrzyOdbiorze IS NOT NULL ";
			}
			$wynikOdbiorOsobisty = "";
			if ($odbiorOsobisty)
			{
				$wynikOdbiorOsobisty = " AND czasOdbiorOsobisty IS NOT NULL ";
			}
			$wynikDarmowaDostawa = "";
			if ($darmowaDostawa)
			{
				$wynikDarmowaDostawa = " AND (cenaKurierZGory = 0 OR cenaKurierPrzyOdbiorze = 0) ";
			}
			$wynik = $wynikTyp.$wynikCena.$wynikStan.$wynikKurierZGory.$wynikKurierPrzyOdbiorze.$wynikOdbiorOsobisty.$wynikDarmowaDostawa;
			return $wynik;
		}
		
		public static function wczytajFiltr($filtr, $adres, $adresZastosuj = null, $adresWyczysc = null)
		{
			$_SESSION["adresWFiltrze"] = $adres;
			$_SESSION["adresZastosujWFiltrze"] = $adresZastosuj;
			$_SESSION["adresWyczyscWFiltrze"] = $adresWyczysc;
			include_once($filtr);
			unset($_SESSION["adresWFiltrze"]);
			unset($_SESSION["adresZastosujWFiltrze"]);
			unset($_SESSION["adresWyczyscWFiltrze"]);
		}
		
		public static function wyczyscFiltryHistoriaZakupow()
		{
			unset($_SESSION["filtrHistoriaZakupow"]);
			unset($_SESSION["filtryHistoriaZakupow"]);
		}
		
		public static function wyczyscFiltryLicytacjeTrwajace()
		{
			unset($_SESSION["filtrLicytacje"]);
			unset($_SESSION["filtrLicytacjeUdzial"]);
		}
		
		public static function wyczyscFiltryLicytacjeWygrane()
		{
			unset($_SESSION["filtrLicytacjeZakonczone"]);
			unset($_SESSION["filtryLicytacjeWygrane"]);
		}
		
		public static function wyczyscFiltrySzukaj()
		{
			unset($_SESSION["filtrStandard"]);
			unset($_SESSION["filtrSzukaj"]);
			unset($_SESSION["sortowanieStandard"]);
		}
		
		public static function wyczyscFiltryZamowieniaDoZrealizowania()
		{
			unset($_SESSION["filtrZamowieniaDoZrealizowania"]);
			unset($_SESSION["filtrZamowienia"]);
		}
		
		public static function wyczyscFiltryZamowieniaDoZrealizowaniaKupTeraz()
		{
			unset($_SESSION["filtrZamowieniaDoZrealizowaniaKupTeraz"]);
			unset($_SESSION["filtrZamowienia"]);
		}
		
		public static function wyczyscSortowanieOcen()
		{
			unset($_SESSION["sortowanieOceny"]);
			unset($_SESSION["filtryOceny"]);
		}
		
		public static function zamowieniaDoZrealizowania($filtr)
		{
			$wynik = "";
			if ($filtr != "")
			{
				switch ($filtr)
				{
					case "wszystkie":
						break;
					case "nieprzeczytane":
						$wynik = " AND przeczytana = 0 ";
						break;
					case "przeczytane":
						$wynik = " AND przeczytana = 1 ";
						break;
					case "do realizacji":
						$wynik = " AND przeczytana = 0 AND wybranySposobDostawy IS NOT NULL AND (SELECT login FROM uzytkownicy WHERE idUzytkownika = (SELECT idUzytkownika FROM kwoty k1 WHERE k1.idOferty = oferty.idOferty AND kwota = (SELECT max(kwota) FROM kwoty k WHERE k.idOferty = oferty.idOferty))) != '".$_SESSION["login"]."' ";
						break;
				}
			}
			return $wynik;
		}
		
		public static function zamowieniaDoZrealizowaniaKupTeraz($filtr)
		{
			$wynik = "";
			if ($filtr != "")
			{
				switch ($filtr)
				{
					case "wszystkie":
						break;
					case "nieprzeczytane":
						$wynik = " AND z.przeczytane = 0 ";
						break;
					case "przeczytane":
						$wynik = " AND z.przeczytane = 1 ";
						break;
					case "do realizacji":
						$wynik = " AND z.przeczytane = 0 AND z.wybranySposobDostawy IS NOT NULL ";
						break;
				}
			}
			return $wynik;
		}
	}
?>