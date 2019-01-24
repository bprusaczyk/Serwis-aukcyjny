<?php
	include_once("klasy/baza_danych.php");
	include_once("klasy/licytacja.php");
	
	class KupTeraz
	{
		public static function dodajDoKoszyka($idOferty, $liczbaSztuk)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("INSERT INTO zamowienia VALUES (null, (SELECT idKoszyka FROM koszyki k, uzytkownicy u WHERE k.idUzytkownika = u.idUzytkownika AND u.login = '".$_SESSION["login"]."'), :idOferty, :liczbaSztuk, NULL, 0)");
			$st->bindParam(":idOferty", $idOferty);
			$st->bindParam(":liczbaSztuk", $liczbaSztuk);
			$st->execute();
		}
		
		public static function oznaczZamowienieJakoPrzeczytane($idZamowienia)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$stTest = $baza->prepare("SELECT count(*) FROM oferty o, zamowienia z WHERE o.idOferty = z.idOferty AND z.idZamowienia = :idZamowienia AND o.idUzytkownika = (SELECT u.idUzytkownika FROM uzytkownicy u WHERE u.login = :login) AND z.wybranySposobDostawy IS NOT NULL");
			$stTest->bindParam(":idZamowienia", $idZamowienia);
			$stTest->bindParam(":login", $_SESSION["login"]);
			$stTest->execute();
			$test = $stTest->fetch();
			if ($test["count(*)"] == 0)
			{
				header("Location: index.php");
				exit();
			}
			$st = $baza->prepare("UPDATE zamowienia SET przeczytane = 1 WHERE idZamowienia = :idZamowienia");
			$st->bindParam(":idZamowienia", $idZamowienia);
			$st->execute();
		}
		
		public static function potwierdzZamowienie($idZamowienia, $sposobDostawy)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$stTest = $baza->prepare("SELECT count(*) FROM zamowienia WHERE idZamowienia = :idZamowienia AND wybranySposobDostawy IS NULL AND idKoszyka = (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$_SESSION["login"]."'))");
			$stTest->bindParam(":idZamowienia", $idZamowienia);
			$stTest->execute();
			$wynikTest = $stTest->fetch();
			if ($wynikTest["count(*)"] != 1)
			{
				header("Location: index.php");
				exit();
			}
			$st = $baza->prepare("UPDATE zamowienia SET wybranySposobDostawy = :sposobDostawy WHERE idZamowienia = :idZamowienia");
			$st->bindParam(":sposobDostawy", $sposobDostawy);
			$st->bindParam(":idZamowienia", $idZamowienia);
			$st->execute();
			$st2 = $baza->prepare("SELECT o.idOferty, o.liczbaSztuk AS liczbaWszystkich, z.liczbaSztuk AS liczbaWZamowieniu FROM oferty o, zamowienia z WHERE o.idOferty = z.idOferty AND z.idZamowienia = :idZamowienia");
			$st2->bindParam(":idZamowienia", $idZamowienia);
			$st2->execute();
			$oferta = $st2->fetch();
			$st3 = $baza->prepare("UPDATE oferty SET liczbaSztuk = :liczbaSztuk WHERE idOferty = :idOferty");
			$roznica = $oferta["liczbaWszystkich"] - $oferta["liczbaWZamowieniu"];
			$st3->bindParam(":liczbaSztuk", $roznica);
			$st3->bindParam(":idOferty", $oferta["idOferty"]);
			$st3->execute();
		}
		
		public static function sprawdzCzyOfertaZostalaOceniona($idZamowienia)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM oceny WHERE idZamowienia = :idZamowienia");
			$st->bindParam(":idZamowienia", $idZamowienia);
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
		
		public static function sprawdzCzyZamowienieZostaloOcenione($idZamowienia)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM oceny o, zamowienia z WHERE o.idZamowienia = z.idZamowienia AND z.idZamowienia = :idZamowienia");
			$st->bindParam(":idZamowienia", $idZamowienia);
			$st->execute();
			$wynik = $st->fetch();
			if ($wynik["count(*)"] == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public static function sprawdzCzyZostalWybranySposobDostawy($idZamowienia)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM zamowienia WHERE idZamowienia = :idZamowienia AND wybranySposobDostawy IS NOT NULL");
			$st->bindParam(":idZamowienia", $idZamowienia);
			$st->execute();
			$wynik = $st->fetch();
			if ($wynik["count(*)"] == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public static function usunZamowienie($idZamowienia)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			$stTest = $baza->prepare("SELECT count(*) FROM zamowienia WHERE idZamowienia = :idZamowienia AND wybranySposobDostawy IS NULL AND idKoszyka = (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$_SESSION["login"]."'))");
			$stTest->bindParam(":idZamowienia", $idZamowienia);
			$stTest->execute();
			$wynikTest = $stTest->fetch();
			if ($wynikTest["count(*)"] != 1)
			{
				header("Location: index.php");
				exit();
			}
			$st = $baza->prepare("DELETE FROM zamowienia WHERE idZamowienia = :idZamowienia");
			$st->bindParam(":idZamowienia", $idZamowienia);
			$st->execute();
		}
		
		public static function utworz($login, $nazwa, $cena, $liczbaSztuk, $data, $kategoria, $opis, $stan, $czyKurierZGory, $cenaKurierZGory, $czasKurierZGory, $czyKurierPrzyOdbiorze, $cenaKurierPrzyOdbiorze, $czasKurierPrzyOdbiorze, $czyOdbiorOsobisty, $czasOdbiorOsobisty, $opisDostawy, $czasNaZwrot, $adresDoZwrotu, $kosztyZwrotu, $opisZwrotow, $okresGwarancji, $opisGwarancji)
		{
			$cenaKurierZGoryZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierZGory, "cenaKurierZGory");
			$czasKurierZGoryZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierZGory, "czasKurierZGory");
			$cenaKurierPrzyOdbiorzeZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierPrzyOdbiorze, "cenaKurierPrzyOdbiorze");
			$czasKurierPrzyOdbiorzeZmienna = Licytacja::zwrocCeneICzasDostawy($czyKurierPrzyOdbiorze, "czasKurierPrzyOdbiorze");
			$czasOdbiorOsobistyZmienna = Licytacja::zwrocCeneICzasDostawy($czyOdbiorOsobisty, "czasOdbiorOsobisty");
			if ($data == "")
			{
				$dataDoWstawienia = "null";
			}
			else
			{
				$dataDoWstawienia = ":data";
			}
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("INSERT INTO oferty (idOferty, idUzytkownika, nazwa, cena, liczbaSztuk, dataZakonczenia, idKategorii, opisOferty, stan, cenaKurierZGory, czasKurierZGory, cenaKurierPrzyOdbiorze, czasKurierPrzyOdbiorze, czasOdbiorOsobisty, opisDostawy, czasNaZwrot, adresDoZwrotu, kosztyZwrotu, opisZwrotow, okresGwarancji, opisGwarancji, typOferty) VALUES (null, (SELECT idUzytkownika FROM uzytkownicy WHERE login = :login), :nazwa, :cena, :liczbaSztuk, ".$dataDoWstawienia.", (SELECT idKategorii FROM kategorie WHERE nazwa = :kategoria), :opis, :stan, ".$cenaKurierZGoryZmienna.", ".$czasKurierZGoryZmienna.", ".$cenaKurierPrzyOdbiorzeZmienna.", ".$czasKurierPrzyOdbiorzeZmienna.", ".$czasOdbiorOsobistyZmienna.", :opisDostawy, :czasNaZwrot, :adresDoZwrotu, :kosztyZwrotu, :opisZwrotow, :okresGwarancji, :opisGwarancji, 'kup teraz')");
			$st->bindParam(":login", $login);
			$st->bindParam(":nazwa", $nazwa);
			$st->bindParam(":cena", $cena);
			$st->bindParam(":liczbaSztuk", $liczbaSztuk);
			if ($data != "")
			{
				$st->bindParam(":data", $data);
			}
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
			$idOferty = $baza->lastInsertId();
			return $idOferty;
		}
		
		public static function wypiszHistorieZakupow($login)
		{
			$filtr = isset($_SESSION["filtrHistoriaZakupow"]) ? $_SESSION["filtrHistoriaZakupow"] : "";
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$licytacje = $baza->query("SELECT z.idOferty, z.idZamowienia, o.nazwa, o.cena, z.liczbaSztuk, o.cenaKurierZGory, o.czasKurierZGory, o.cenaKurierPrzyOdbiorze, z.wybranySposobDostawy, o.idUzytkownika FROM zamowienia z, oferty o WHERE z.idOferty = o.idOferty AND z.idKoszyka = (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."')) AND z.wybranySposobDostawy IS NOT NULL ".$filtr." ORDER BY z.idZamowienia DESC LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
			$i = 0;
			foreach ($licytacje as $wiersz)
			{
				switch ($wiersz["wybranySposobDostawy"])
				{
					case "Kurier (płatność z góry)":
						$nrKonta = Uzytkownik::zwrocDaneOsobowePoId($wiersz["idUzytkownika"], "numerKontaBankowego");
						$drugaCzesc = '<p>
												Wpłatę należy dokonać na konto
											</p>
											<h4>
												'.$nrKonta.'
											</h4>
											<p>
												Towar: <strong>'.($wiersz["cena"] * $wiersz["liczbaSztuk"]).'</strong><br>
												Przesyłka: <strong>'.$wiersz["cenaKurierZGory"].'</strong><br>
												RAZEM: <strong>'.(($wiersz["cena"] * $wiersz["liczbaSztuk"]) + $wiersz["cenaKurierZGory"]).'</strong>
											</p>';
						break;
					case "Kurier (płatność przy odbiorze)":
						$drugaCzesc = '<p>
												Towar: <strong>'.($wiersz["cena"] * $wiersz["liczbaSztuk"]).'</strong><br>
												Przesyłka: <strong>'.$wiersz["cenaKurierPrzyOdbiorze"].'</strong><br>
												RAZEM: <strong>'.(($wiersz["cena"] * $wiersz["liczbaSztuk"]) + $wiersz["cenaKurierPrzyOdbiorze"]).'</strong>
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
									<strong>'.($wiersz["cena"] * $wiersz["liczbaSztuk"]).'</strong>
								</h3>
								Cena za sztukę: '.$wiersz["cena"].'<br>
								Liczba sztuk: '.$wiersz["liczbaSztuk"].'<br>
								Wybrany sposób dostawy: '.$wiersz["wybranySposobDostawy"].'
							</p>
							'.$drugaCzesc.(KupTeraz::sprawdzCzyOfertaZostalaOceniona($wiersz["idZamowienia"]) ? "" : '<form style="margin-top: 10px;" method="post" action="wystawianie_oceny_kup_teraz_skrypt.php">
																																				<input name="id-oferty" type="hidden" value="'.$wiersz["idOferty"].'"/>
																																				<input name="id-zamowienia" type="hidden" value="'.$wiersz["idZamowienia"].'"/>
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
		
		public static function wypiszKupTerazUzytkownika($login)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			$sortowanie = isset($_SESSION["sortowanieStandard"]) ? $_SESSION["sortowanieStandard"] : "";
			Licytacja::wypiszOferty("SELECT idOferty, nazwa, stan, dataZakonczenia, cenaKurierZGory, cenaKurierPrzyOdbiorze, cena, typOferty FROM oferty WHERE typOferty = 'kup teraz' AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') ".$filtr." ".$sortowanie." LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
		}
		
		public static function wypiszNumeryStronHistoriaZakupow($login)
		{
			$filtr = isset($_SESSION["filtrHistoriaZakupow"]) ? $_SESSION["filtrHistoriaZakupow"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM zamowienia z, oferty o WHERE z.idOferty = o.idOferty AND z.idKoszyka = (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."')) AND z.wybranySposobDostawy IS NOT NULL ".$filtr, "", "historia_zakupow.php");
		}
		
		public static function wypiszNumeryStronKoszyk($login)
		{
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM zamowienia WHERE idKoszyka = (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."')) AND wybranySposobDostawy IS NULL", "", "koszyk.php");
		}
		
		public static function wypiszNumeryStronKupTerazUzytkownika($login)
		{
			$filtr = isset($_SESSION["filtrStandard"]) ? $_SESSION["filtrStandard"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty WHERE typOferty = 'kup teraz' AND idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."') ".$filtr, "", "moje_kup_teraz.php");
		}
		
		public static function wypiszNumeryStronZamowieniaDoZrealizowania($login)
		{
			$filtr = isset($_SESSION["filtrZamowieniaDoZrealizowaniaKupTeraz"]) ? $_SESSION["filtrZamowieniaDoZrealizowaniaKupTeraz"] : "";
			Licytacja::wypiszNumeryStron("SELECT count(*) FROM oferty o, zamowienia z WHERE o.idOferty = z.idOferty AND z.wybranySposobDostawy IS NOT NULL AND o.idUzytkownika = (SELECT u1.idUzytkownika FROM uzytkownicy u1 WHERE login = '".$login."') ".$filtr, "", "zamowienia_do_zrealizowania.php");
		}
		
		public static function wypiszZamowienia($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$licytacje = $baza->query("SELECT z.idOferty, z.idZamowienia, o.nazwa, o.cena, z.liczbaSztuk, o.czasKurierZGory, o.czasKurierPrzyOdbiorze, o.czasOdbiorOsobisty FROM zamowienia z, oferty o WHERE z.idOferty = o.idOferty AND z.idKoszyka = (SELECT idKoszyka FROM koszyki WHERE idUzytkownika = (SELECT idUzytkownika FROM uzytkownicy WHERE login = '".$login."')) AND z.wybranySposobDostawy IS NULL ORDER BY z.idZamowienia DESC LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
			$i = 0;
			foreach ($licytacje as $wiersz)
			{
				$kurierZGory = $wiersz["czasKurierZGory"] == "" ? "" : '<option>
																							Kurier (płatność z góry)
																						</option>';
				$kurierPrzyOdbiorze = $wiersz["czasKurierPrzyOdbiorze"] == "" ? "" : '<option>
																												Kurier (płatność przy odbiorze)
																											</option>';
				$odbiorOsobisty = $wiersz["czasOdbiorOsobisty"] == "" ? "" : '<option>
																									Odbiór osobisty
																								</option>';
				$drugaCzesc = '<form method="post" action="potwierdzanie_kup_teraz_skrypt.php">
										<input type="hidden" name="id-zamowienia" value="'.$wiersz["idZamowienia"].'"/>
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
										<button type="submit" class="btn btn-success" onclick="return walidacjaWyborSposobuDostawy('.$i++.');">
											Potwierdź
										</button>
									</form>
									<form method="post" action="anuluj_kup_teraz_skrypt.php" style="margin-top: 10px;">
										<input type="hidden" name="id-zamowienia" value="'.$wiersz["idZamowienia"].'"/>
										<button type="submit" class="btn btn-danger" onclick="return walidacjaWyborSposobuDostawy('.$i++.');">
											Anuluj
										</button>
									</form>';
				if (isset($_SESSION["wybieranieSposobuDostawyBlad"]))
				{
					unset($_SESSION["wybieranieSposobuDostawyBlad"]);
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
									<strong>'.($wiersz["cena"] * $wiersz["liczbaSztuk"]).'</strong>
								</h3>
								Cena za sztukę: '.$wiersz["cena"].'<br>
								Liczba sztuk: '.$wiersz["liczbaSztuk"].'
							</p>
							'.$drugaCzesc.'
						</div>';
			}
		}
		
		public function wypiszZamowieniaDoZrealizowania($login)
		{
			$filtr = isset($_SESSION["filtrZamowieniaDoZrealizowaniaKupTeraz"]) ? $_SESSION["filtrZamowieniaDoZrealizowaniaKupTeraz"] : "";
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$oferty = $baza->query("SELECT z.wybranySposobDostawy, (SELECT k.idUzytkownika FROM koszyki k WHERE k.idKoszyka = z.idKoszyka) AS idUzytkownika, o.idOferty, o.nazwa, z.przeczytane, z.idZamowienia, z.liczbaSztuk FROM oferty o, zamowienia z WHERE o.idOferty = z.idOferty AND z.wybranySposobDostawy IS NOT NULL AND o.idUzytkownika = (SELECT u1.idUzytkownika FROM uzytkownicy u1 WHERE login = '".$login."') ".$filtr." ORDER BY z.idZamowienia DESC LIMIT 10 OFFSET ".($_GET["strona"] * 10 - 10));
			foreach($oferty as $wiersz)
			{
				if ($wiersz["wybranySposobDostawy"] == "Kurier (płatność z góry)")
				{
					$tekst = 'Wybrany sposób dostawy: <strong>przesyłka kurierska z płatnością z góry</strong><br>
								 Przesyłkę należy wysłać na adres:<br>
								 <strong>'.Uzytkownik::wypiszAdres($wiersz["idUzytkownika"]).'</strong>';
				}
				elseif ($wiersz["wybranySposobDostawy"] == "Kurier (płatność przy odbiorze)")
				{
					$tekst = 'Wybrany sposób dostawy: <strong>przesyłka kurierska z płatnością przy odbiorze</strong><br>
								 Przesyłkę należy wysłać na adres:<br>
								 <strong>'.Uzytkownik::wypiszAdres($wiersz["idUzytkownika"]).'</strong>';
				}
				elseif ($wiersz["wybranySposobDostawy"] == "Odbiór osobisty")
				{
					$tekst = 'Wybrany sposób dostawy: <strong>odbiór osobisty</strong><br>';
				}
				if ($wiersz["przeczytane"] == 0)
				{
					$form = '<form method="post" action="przeczytanie_zamowienia_kup_teraz_skrypt.php">
									<input type="hidden" name="id-zamowienia" value="'.$wiersz["idZamowienia"].'"/>
									<button type="submit" class="btn btn-info">
										Oznacz jako przeczytane
									</button>
								</form>';
				}
				else
				{
					$form = '';
				}
				$klasa = $wiersz["przeczytane"] == 1 ? " przeczytane" : "";
				echo '<div class="oferta'.$klasa.'">
							<a href="oferta.php?oferta='.$wiersz["idOferty"].'" class="oferta-informacje-nazwa">
								<h2>
									'.$wiersz["nazwa"].'
								</h2>
							</a>
							<p>
								Liczba sztuk: <strong>'.$wiersz["liczbaSztuk"].'</strong><br>
								'.$tekst.'
							</p>
							'.$form.'
						</div>';
			}
		}
		
		public static function zapamietajDane()
		{
			$_SESSION["staraNazwa"] = $_POST["nazwa"];
			$_SESSION["staraCena"] = $_POST["cena"];
			$_SESSION["staraLiczbaSztuk"] = $_POST["liczba-sztuk"];
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
		
		public static function zwrocLoginZamawiajacego($idZamowienia)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT login FROM uzytkownicy WHERE idUzytkownika = (SELECT idUzytkownika FROM koszyki WHERE idKoszyka = (SELECT idKoszyka FROM zamowienia WHERE idZamowienia = :idZamowienia))");
			$st->bindParam(":idZamowienia", $idZamowienia);
			$st->execute();
			$wiersz = $st->fetch();
			return $wiersz["login"];
		}
		
		public static function zwrocLiczbeNieprzeczytanychZamowien($login)
		{
			$baza = new PDO(DANE_BAZY, UZYTKOWNIK_BAZY, HASLO_BAZY);
			BazaDanych::ustawKodowanie($baza);
			$st = $baza->prepare("SELECT count(*) FROM oferty o, zamowienia z WHERE o.idOferty = z.idOferty AND z.wybranySposobDostawy IS NOT NULL AND o.idUzytkownika = (SELECT u1.idUzytkownika FROM uzytkownicy u1 WHERE login = :login) AND z.przeczytane = 0");
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
	}
?>