<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
	include_once("klasy/walidacja.php");
	session_start();
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
	Uzytkownik::sprawdzDaneUzytkownika($_SESSION["login"]);
	include_once("naglowek.php");
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<h1>
		Utwórz aukcję z opcją zakupu natychmiastowego
	</h1>
	<br>
	<form action="tworzenie_kup_teraz_skrypt.php" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label for="nazwa">
				Nazwa oferty
			</label>
			<input id="nazwa" name="nazwa" type="text" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("tworzenieKupTerazBladNazwa"); ?>" placeholder="Nazwa oferty" <?php echo Walidacja::zwrocStaraWartosc("staraNazwa"); ?>/>
			<small id="nazwa-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladNazwa");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="cena">
				Cena
			</label>
			<input id="cena" name="cena" type="number" step="0.01" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("tworzenieKupTerazBladCena"); ?>" placeholder="Cena" <?php echo Walidacja::zwrocStaraWartosc("staraCena"); ?>/>
			<small id="cena-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladCena");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="liczba-sztuk">
				Liczba sztuk
			</label>
			<input id="liczba-sztuk" name="liczba-sztuk" type="number" step="1" min="1" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("tworzenieKupTerazBladLiczbaSztuk"); ?>" placeholder="Liczba sztuk" <?php echo Walidacja::zwrocStaraWartosc("staraLiczbaSztuk"); ?>/>
			<small id="liczba-sztuk-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladLiczbaSztuk");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="data-koniec">
				Data ważności oferty (opcjonalnie)
			</label>
			<input id="data-koniec" name="data-koniec" type="datetime-local" class="form-control" placeholder="Data zakończenia licytacji" <?php echo Walidacja::zwrocStaraWartosc("staraDataKoniec"); ?>/>
		</div>
		<div class="form-group">
			<label for="kategoria">
				Kategoria
			</label>
			<br>
			<select id="kategoria" name="kategoria" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("tworzenieKupTerazBladKategoria"); ?>" onchange="aktywujKontrolki('Artykuły spożywcze', 'kategoria', 'stan-nowy'); aktywujKontrolki('Artykuły spożywcze', 'kategoria', 'stan-uzywany');">
				<option disabled selected value>
					-- wybierz kategorię --
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Artykuły spożywcze"); ?>>
					Artykuły spożywcze
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Biżuteria"); ?>>
					Biżuteria
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Dla dzieci"); ?>>
					Dla dzieci
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Dom i ogród"); ?>>
					Dom i ogród
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Elektronika"); ?>>
					Elektronika
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Filmy"); ?>>
					Filmy
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Firma"); ?>>
					Firma
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Kolekcje i sztuka"); ?>>
					Kolekcje i sztuka
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Książki"); ?>>
					Książki
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Motoryzacja"); ?>>
					Motoryzacja
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Muzyka"); ?>>
					Muzyka
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Odzież i obuwie"); ?>>
					Odzież i obuwie
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Sport i wypoczynek"); ?>>
					Sport i wypoczynek
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Uroda i zdrowie"); ?>>
					Uroda i zdrowie
				</option>
				<option <?php echo Walidacja::zwrocStaraWartoscRozwijanejListy("staraKategoria", "Inne"); ?>>
					Inne
				</option>
			</select>
			<small id="kategoria-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladKategoria");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="opis">
				Opis oferty
			</label>
			<textarea id="opis" name="opis" class="form-control" placeholder="Tutaj możesz zawrzeć dodatkowe informacje o ofercie, które nie zostały uwzględnione w formularzu"><?php Walidacja::wypiszStaraWartosc("staryOpis"); ?></textarea>
		</div>
		<div class="form-group">
			<label for="zdjecia">
				Zdjęcia
			</label>
			<input id="zdjecia" name="zdjecia[]" type="file" class="form-control" multiple accept="image/*"/>
		</div>
		<div class="form-group">
			<label id="stan-napis">
				Stan
			</label>
			<div>
				<label>
					<input id="stan-nowy" name="stan" type="radio" value="nowy" <?php echo Walidacja::zwrocStaraWartoscRadioButtonow("staryStan", "nowy"); ?>>Nowy
				</label>
			</div>
			<div>
				<label>
					<input id="stan-uzywany" name="stan" type="radio" value="używany" <?php echo Walidacja::zwrocStaraWartoscRadioButtonow("staryStan", "używany"); ?>>Używany
				</label>
			</div>
			<small id="stan-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladStan");
				?>
			</small>
		</div>
		<h2 id="dostawa-napis" style="margin: 10px 0 10px 0;">
			Dostawa i płatność
		</h2>
		<div class="form-group">
			<table class="table">
				<tr>
					<th/>
					<th/>
					<th>
						Cena
					</th>
					<th>
						Czas realizacji (w dniach roboczych)
					</th>
				</tr>
				<tr>
					<td>
						<input id="kurier-z-gory" name="kurier-z-gory" type="checkbox" onchange="aktywujKontrolkiCheckbox(false, 'kurier-z-gory', 'cena-kurier-z-gory'); aktywujKontrolkiCheckbox(false, 'kurier-z-gory', 'czas-kurier-z-gory');" <?php echo Walidacja::zwrocStaraWartoscRadioButtonow("staryKurierZGory", "on"); ?>/>
					</td>
					<td>
						Kurier (płatność z góry)
					</td>
					<td>
						<input id="cena-kurier-z-gory" name="cena-kurier-z-gory" type="number" step="0.01" min="0" disabled <?php echo Walidacja::zwrocStaraWartosc("staraCenaKurierZGory"); ?>/>
					</td>
					<td>
						<input id="czas-kurier-z-gory" name="czas-kurier-z-gory" type="number" min="0" disabled <?php echo Walidacja::zwrocStaraWartosc("staryCzasKurierZGory"); ?>/>
					</td>
				</tr>
				<tr>
					<td>
						<input id="kurier-przy-odbiorze" name="kurier-przy-odbiorze" type="checkbox" onchange="aktywujKontrolkiCheckbox(false, 'kurier-przy-odbiorze', 'cena-kurier-przy-odbiorze'); aktywujKontrolkiCheckbox(false, 'kurier-przy-odbiorze', 'czas-kurier-przy-odbiorze');" <?php echo Walidacja::zwrocStaraWartoscRadioButtonow("staryKurierPrzyOdbiorze", "on"); ?>/>
					</td>
					<td>
						Kurier (płatność przy odbiorze)
					</td>
					<td>
						<input id="cena-kurier-przy-odbiorze" name="cena-kurier-przy-odbiorze" type="number" step="0.01" min="0" disabled <?php echo Walidacja::zwrocStaraWartosc("staraCenaKurierPrzyOdbiorze"); ?>/>
					</td>
					<td>
						<input id="czas-kurier-przy-odbiorze" name="czas-kurier-przy-odbiorze" type="number" min="0" disabled <?php echo Walidacja::zwrocStaraWartosc("staryCzasKurierPrzyOdbiorze"); ?>/>
					</td>
				</tr>
				<tr>
					<td>
						<input id="odbior-osobisty" name="odbior-osobisty" name="odbior-osobisty" type="checkbox" onchange="aktywujKontrolkiCheckbox(false, 'odbior-osobisty', 'czas-odbior-osobisty');" <?php echo Walidacja::zwrocStaraWartoscRadioButtonow("staryOdbiorOsobisty", "on"); ?>/>
					</td>
					<td>
						Odbiór osobisty
					</td>
					<td>
						Nie dotyczy
					</td>
					<td>
						<input id="czas-odbior-osobisty" name="czas-odbior-osobisty" type="number" min="0" disabled <?php echo Walidacja::zwrocStaraWartosc("staryCzasOdbiorOsobisty"); ?>/>
					</td>
				</tr>
			</table>
			<small id="dostawa-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladDostawa");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="opis-dostawa">
				Dodatkowe informacje o dostawie i płatności
			</label>
			<textarea id="opis-dostawa" name="opis-dostawa" class="form-control"  placeholder="Tutaj możesz zawrzeć dodatkowe informacje o dostawie i płatności, które nie zostały uwzględnione w formularzu"><?php Walidacja::wypiszStaraWartosc("staryOpisDostawa"); ?></textarea>
		</div>
		<h2 style="margin: 10px 0 10px 0;">
			Zwroty
		</h2>
		<div class="form-group">
			<label for="czas-na-zwrot">
				Czas na odstąpienie od umowy (w dniach)
			</label>
			<input id="czas-na-zwrot" name="czas-na-zwrot" type="number" min="14" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("tworzenieKupTerazBladCzasNaZwrot"); ?>" placeholder="Czas na odstąpienie od umowy (w dniach)"/>
			<small id="czas-na-zwrot-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladCzasNaZwrot");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="adres-zwrotu">
				Adres do zwrotu
			</label>
			<textarea id="adres-zwrotu" name="adres-zwrotu" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("tworzenieKupTerazBladAdresZwrotu"); ?>" placeholder="Adres do zwrotu"><?php Walidacja::wypiszStaraWartosc("staryAdresZwrotu"); ?></textarea>
			<small id="adres-zwrotu-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladAdresZwrotu");
				?>
			</small>
		</div>
		<div class="form-group">
			<label id="zwroty-napis">
				Koszty przesyłki zwrotnej pokrywa
			</label>
			<div>
				<label>
					<input id="koszty-zwrotu-sprzedajacy" name="koszty-zwrotu" type="radio" value="sprzedający" <?php echo Walidacja::zwrocStaraWartoscRadioButtonow("stareKosztyZwrotu", "sprzedający"); ?>>Sprzedający
				</label>
			</div>
			<div>
				<label>
					<input id="koszty-zwrotu-kupujacy" name="koszty-zwrotu" type="radio" value="kupujący" <?php echo Walidacja::zwrocStaraWartoscRadioButtonow("stareKosztyZwrotu", "kupujący"); ?>>Kupujący
				</label>
			</div>
			<small id="zwroty-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladKosztyZwrotu");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="opis-zwroty">
				Dodatkowe informacje o zwrotach
			</label>
			<textarea id="opis-zwroty" name="opis-zwroty" class="form-control" placeholder="Tutaj możesz zawrzeć dodatkowe informacje o zwrotach, które nie zostały uwzględnione w formularzu"><?php Walidacja::wypiszStaraWartosc("staryOpisZwroty"); ?></textarea>
		</div>
		<h2 style="margin: 10px 0 10px 0;">
			Gwarancja
		</h2>
		<div class="form-group">
			<label for="gwarancja">
				Okres gwarancji (w miesiącach)
			</label>
			<input id="gwarancja" name="gwarancja" type="number" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("tworzenieKupTerazBladGwarancja"); ?>" placeholder="Okres gwarancji (w miesiącach)" min="0" <?php echo Walidacja::zwrocStaraWartosc("staraGwarancja"); ?>/>
			<small id="gwarancja-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("tworzenieKupTerazBladGwarancja");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="gwarancja-opis">
				Dodatkowe informacje o gwarancji
			</label>
			<textarea id="gwarancja-opis" name="gwarancja-opis" class="form-control" placeholder="Tutaj możesz zawrzeć dodatkowe informacje o gwarancji, które nie zostały uwzględnione w formularzu"><?php Walidacja::wypiszStaraWartosc("staraGwarancjaOpis"); ?></textarea>
		</div>
		<button type="submit" class="btn btn-success" onclick="return walidacjaTworzeniaKupTeraz();">Utwórz ofertę</button>
	</form>
</div>
<div class="div-boczny"></div>
<script src="script/aktywacja_formularzy.js"></script>
<script src="script/walidacja_tworzenie_oferty.js"></script>
<script src="script/walidacja.js"></script>
<?php
	include_once("stopka.php");
?>