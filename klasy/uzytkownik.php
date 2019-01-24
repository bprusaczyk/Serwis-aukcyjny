<?php
    include_once("klasy/baza_danych.php");

    class Uzytkownik
    {
		public static function aktualizujDaneOsobowe($login, $imie, $nazwisko, $kraj, $miejscowosc, $ulica, $numerBudynku, $numerMieszkania, $numerTelefonu, $emailKontaktowy, $numerKontaBankowego, $kodPocztowy)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE uzytkownicy SET imie = :imie, nazwisko = :nazwisko, kraj = :kraj, miejscowosc = :miejscowosc, ulica = :ulica, numerBudynku = :numerBudynku, numerMieszkania = :numerMieszkania, numerTelefonu = :numerTelefonu, emailKontaktowy = :emailKontaktowy, numerKontaBankowego = :numerKontaBankowego, kodPocztowy = :kodPocztowy WHERE login = :login");
			$st->bindParam(":login", $login);
			$st->bindParam(":imie", $imie);
			$st->bindParam(":nazwisko", $nazwisko);
			$st->bindParam(":kraj", $kraj);
			$st->bindParam(":miejscowosc", $miejscowosc);
			$st->bindParam(":ulica", $ulica);
			$st->bindParam(":numerBudynku", $numerBudynku);
			$st->bindParam(":numerMieszkania", $numerMieszkania);
			$st->bindParam(":numerTelefonu", $numerTelefonu);
			$st->bindParam(":emailKontaktowy", $emailKontaktowy);
			$st->bindParam(":numerKontaBankowego", $numerKontaBankowego);
			$st->bindParam(":kodPocztowy", $kodPocztowy);
			$st->execute();
		}
		
		public static function dodajNieudanaProbeLogowania($loginEmail)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE uzytkownicy SET liczbaNieudanychProbLogowania = liczbaNieudanychProbLogowania + 1 WHERE login = :loginEmail OR email = :loginEmail");
			$st->bindParam(":loginEmail", $loginEmail);
			$st->execute();
			$st2 = $baza->prepare("SELECT liczbaNieudanychProbLogowania FROM uzytkownicy WHERE login = :loginEmail OR email = :loginEmail");
			$st2->bindParam(":loginEmail", $loginEmail);
			$st2->execute();
			$wiersz = $st2->fetch();
			if ($wiersz["liczbaNieudanychProbLogowania"] >= 10)
			{
				Uzytkownik::zablokujUzytkownika($loginEmail);
			}
		}
		
		private static function przejdzDoEdycjiDanychOsobowych()
		{
			$_SESSION["edycjaDanychOsobowychStronaPowrotu"] = basename($_SERVER["PHP_SELF"]);
			header("Location: edycja_konta.php");
			exit();
		}
		
		private static function przejdzDoEdycjiDanychOsobowychKlient()
		{
			$_SESSION["edycjaDanychOsobowychStronaPowrotu"] = $_SERVER["HTTP_REFERER"];
			header("Location: edycja_konta.php");
			exit();
		}
		
		public static function sprawdzCzyUzytkownikJestZablokowany($loginEmail)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT now() < odblokowanieKonta as test FROM uzytkownicy WHERE login = :loginEmail OR email = :loginEmail");
			$st->bindParam(":loginEmail", $loginEmail);
			$st->execute();
			$wiersz = $st->fetch();
			if ($wiersz["test"] == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public static function sprawdzDaneUzytkownika($login)
		{
			if(!Uzytkownik::sprawdzCzyUzytkownikMaWypelnioneWymaganePola($login))
			{
				Uzytkownik::przejdzDoEdycjiDanychOsobowych();
			}
		}
		
		public static function sprawdzDaneUzytkownikaKlient($login)
		{
			if(!Uzytkownik::sprawdzCzyUzytkownikMaWypelnioneWymaganePola($login))
			{
				Uzytkownik::przejdzDoEdycjiDanychOsobowychKlient();
			}
		}
		
		public static function sprawdzDostepnoscLoginu($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM uzytkownicy WHERE login = :login");
			$st->bindParam(":login", $login);
			$st->execute();
			$wiersz = $st->fetch();
			if ($wiersz["count(*)"] == "0")
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public static function sprawdzDostepnoscMaila($email)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM uzytkownicy WHERE email = :email");
			$st->bindParam(":email", $email);
			$st->execute();
			$wiersz = $st->fetch();
			if ($wiersz["count(*)"] == "0")
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public static function sprawdzCzyUzytkownikMaWypelnioneWymaganePola($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$zapytanie = $baza->query("SELECT imie, nazwisko, kraj, miejscowosc, numerBudynku, kodPocztowy, numerKontaBankowego FROM uzytkownicy WHERE login ='".$login."'");
			foreach ($zapytanie as $wiersz)
			{
				$pola = array($wiersz["imie"], $wiersz["nazwisko"], $wiersz["kraj"], $wiersz["miejscowosc"], $wiersz["numerBudynku"], $wiersz["kodPocztowy"], $wiersz["numerKontaBankowego"]);
			}
			foreach ($pola as $pole)
			{
				if ($pole == "")
				{
					return false;
				}
			}
			return true;
		}
		
		public static function usunKonto($login)
		{
			Uzytkownik::usunZdjeciaUzytkownika($login);
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$polecenia = array(
				0 => "DELETE FROM zdjecia WHERE idOferty IN (SELECT idOferty FROM oferty WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login))",
				1 => "DELETE FROM oceny WHERE idOferty IN (SELECT idOferty FROM oferty WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login))",
				2 => "DELETE FROM oceny WHERE idZamowienia IN (SELECT idZamowienia FROM zamowienia WHERE idKoszyka = (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login)))",
				3 => "DELETE FROM zamowienia WHERE idOferty IN (SELECT idOferty FROM oferty WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login))",
				4 => "DELETE FROM zamowienia WHERE idKoszyka IN (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login))",
				5 => "DELETE FROM kwoty WHERE idOferty IN (SELECT idOferty FROM oferty WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login))",
				6 => "DELETE FROM kwoty WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login)",
				7 => "DELETE FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login)",
				8 => "DELETE FROM oferty WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login)",
				9 => "DELETE FROM uzytkownicy WHERE login = :login");
			for ($i = 0; $i<=9; $i++)
			{
				$st = $baza->prepare($polecenia[$i]);
				$st->bindParam(":login", $login);
				$st->execute();
			}
		}
		
		private static function usunZdjeciaUzytkownika($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$zapytanie = $baza->query("SELECT sciezka FROM zdjecia z, oferty o WHERE z.idOferty = o.idOferty AND o.idUzytkownika = (SELECT u.idUzytkownika FROM uzytkownicy u WHERE login = '".$login."')");
			foreach ($zapytanie as $wiersz)
			{
				unlink("zdjecia/".$wiersz["sciezka"]);
			}
		}
		
		public static function wypiszAdres($id)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$zapytanie = $baza->query("SELECT imie, nazwisko, kraj, miejscowosc, ulica, numerBudynku, numerMieszkania, numerTelefonu, emailKontaktowy, kodPocztowy FROM uzytkownicy WHERE idUzytkownika = ".$id);
			$adres = null;
			foreach ($zapytanie as $wiersz)
			{
				$nrDomu = $wiersz["numerMieszkania"] == "" ? $wiersz["numerBudynku"] : $wiersz["numerBudynku"]."/".$wiersz["numerMieszkania"];
				$ulica = $wiersz["ulica"] == "" ? $wiersz["miejscowosc"]." ".$nrDomu : $wiersz["miejscowosc"].", ul. ".$wiersz["ulica"]." ".$nrDomu;
				$daneKontaktowe = $wiersz["numerTelefonu"] != "" || $wiersz["emailKontaktowy"] != "" ? 'Kontakt:<br>
																																		'.($wiersz["numerTelefonu"] != "" ? "Telefon: ".htmlentities($wiersz["numerTelefonu"])."<br>" : "").($wiersz["emailKontaktowy"] != "" ? "E-mail: ".htmlentities($wiersz["emailKontaktowy"])."<br>" : "") : "";
				$adres = htmlentities($wiersz["imie"]).' '.htmlentities($wiersz["nazwisko"]).'<br>
							'.htmlentities($ulica).'<br>
							'.htmlentities($wiersz["kodPocztowy"]).'<br>
							'.htmlentities($wiersz["kraj"]).'<br>
							'.$daneKontaktowe;
			}
			return $adres;
		}
		
		private static function wyzerujNieudaneProbyLogowania($loginEmail)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT timestampdiff(HOUR, ostatniaProbaLogowania, now()) as roznica FROM uzytkownicy WHERE login = :loginEmail OR email = :loginEmail");
			$st->bindParam(":loginEmail", $loginEmail);
			$st->execute();
			$wynik = $st->fetch();
			if ($wynik["roznica"] >= 1)
			{
				$st2 = $baza->prepare("UPDATE uzytkownicy SET liczbaNieudanychProbLogowania = 0 WHERE login = :loginEmail OR email = :loginEmail");
				$st2->bindParam(":loginEmail", $loginEmail);
				$st2->execute();
			}
		}
		
		private static function zablokujUzytkownika($loginEmail)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE uzytkownicy SET odblokowanieKonta = date_add(now(), INTERVAL 1 HOUR) WHERE login = :loginEmail OR email = :loginEmail");
			$st->bindParam(":loginEmail", $loginEmail);
			$st->execute();
		}
		
		public static function zaloguj($loginEmail, $haslo)
		{
			Uzytkownik::wyzerujNieudaneProbyLogowania($loginEmail);
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM uzytkownicy WHERE (login = :loginEmail OR email = :loginEmail) AND haslo = :haslo");
			$st->bindParam(":loginEmail", $loginEmail);
			$st->bindParam(":haslo", hash("md5", $haslo));
			$st->execute();
			$wiersz = $st->fetch();
			if ($wiersz["count(*)"] == 1)
			{
				if (preg_match("/^\S+@\S+$/", $loginEmail) != 1)
				{
					$_SESSION["login"] = $loginEmail;
					Uzytkownik::zmienDateOstatniegoPoprawnegoLogowania($loginEmail);
				}
				else
				{
					$st = $baza->prepare("SELECT login FROM uzytkownicy WHERE email = :email");
					$st->bindParam(":email", $loginEmail);
					$st->execute();
					$wiersz = $st->fetch();
					$_SESSION["login"] = $wiersz["login"];
					Uzytkownik::zmienDateOstatniegoPoprawnegoLogowania($wiersz["login"]);
				}
				return true;
			}
			else
			{
				Uzytkownik::zmienDateOstatniegoLogowania($loginEmail);
				return false;
			}
		}
		
		public static function zapamietajDane()
		{
			$_SESSION["staryLogin"] = $_POST["login"];
			$_SESSION["staryEmail"] = $_POST["email"];
		}
		
		public static function zarejestruj($login, $haslo, $email)
        {
            $zaszyfrowaneHaslo = hash("md5", $haslo);
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("insert into uzytkownicy (idUzytkownika, login, haslo, email, emailKontaktowy) values (null, :login, :haslo, :email, :email)");
			$st->bindParam(":login", $login);
			$st->bindParam(":haslo", $zaszyfrowaneHaslo);
			$st->bindParam(":email", $email);
			$st->execute();
			$baza->exec("INSERT INTO koszyki VALUES (NULL, ".$baza->lastInsertId().")");
        }
		
		private static function zmienDateOstatniegoLogowania($loginEmail)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE uzytkownicy SET ostatniaProbaLogowania = now() WHERE login = :loginEmail OR email = :loginEmail");
			$st->bindParam(":loginEmail", $loginEmail);
			$st->execute();
		}
		
		private static function zmienDateOstatniegoPoprawnegoLogowania($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE uzytkownicy SET ostatniaProbaLogowania = now(), liczbaNieudanychProbLogowania = 0 WHERE login = :login");
			$st->bindParam(":login", $login);
			$st->execute();
		}
		
		public static function zmienEMail($nowyEMail, $login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE uzytkownicy SET email = :email WHERE login = :login");
			$st->bindParam(":email", $nowyEMail);
			$st->bindParam(":login", $login);
			$st->execute();
		}
		
		public static function zmienHaslo($noweHaslo, $login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("UPDATE uzytkownicy SET haslo = :haslo WHERE login = :login");
			$st->bindParam(":haslo", hash("md5", $noweHaslo));
			$st->bindParam(":login", $login);
			$st->execute();
		}
		
		public static function zwrocDaneOsobowe($login, $dane)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT * FROM uzytkownicy WHERE login = :login");
			$st->bindParam(":login", $login);
			$st->execute();
			$wiersz = $st->fetch();
			return htmlentities($wiersz[$dane]);
		}
		
		public static function zwrocDaneOsobowePoId($id, $dane)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$zapytanie = $baza->query("SELECT * FROM uzytkownicy WHERE idUzytkownika = ".$id);
			foreach ($zapytanie as $wiersz)
			{
				$wynik = $wiersz[$dane];
			}
			return $wynik;
		}
		
		public static function zwrocSredniaOcenUzytkownika($id)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT avg(oc.ocena) FROM oceny oc, oferty of, uzytkownicy u WHERE oc.idOferty = of.idOferty AND of.idUzytkownika = u.idUzytkownika AND u.idUzytkownika = :id");
			$st->bindParam(":id", $id);
			$st->execute();
			$wiersz = $st->fetch();
			return $wiersz["avg(oc.ocena)"] != "" ? $wiersz["avg(oc.ocena)"] : "Użytkownik nie został jeszcze oceniony";
		}
		
		public static function zwrocSredniaOcenUzytkownikaLogin($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT avg(oc.ocena) FROM oceny oc, oferty of, uzytkownicy u WHERE oc.idOferty = of.idOferty AND of.idUzytkownika = u.idUzytkownika AND u.login = :login");
			$st->bindParam(":login", $login);
			$st->execute();
			$wiersz = $st->fetch();
			return $wiersz["avg(oc.ocena)"] != "" ? $wiersz["avg(oc.ocena)"] : "Użytkownik nie został jeszcze oceniony";
		}
    }
?>