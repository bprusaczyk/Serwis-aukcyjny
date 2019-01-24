<h3>
	Filtry
</h3>
<form class="filtr" method="post" action="standard_skrypt.php">
	<input type="hidden" value="<?php echo $_SESSION["adresWFiltrze"]; ?>" name="powrot"/>
	<div class="form-group">
		<label>
			Rodzaj oferty
		</label>
		<div>
			<label>
				<input name="rodzaj-oferty" type="radio" value="wszystkie" <?php if (isset($_SESSION["filtrSzukaj"]["typOfertySzukaj"]) && $_SESSION["filtrSzukaj"]["typOfertySzukaj"] == "wszystkie") echo "checked"; ?>>Wszystkie
			</label>
		</div>
		<div>
			<label>
				<input name="rodzaj-oferty" type="radio" value="kup teraz" <?php if (isset($_SESSION["filtrSzukaj"]["typOfertySzukaj"]) && $_SESSION["filtrSzukaj"]["typOfertySzukaj"] == "kup teraz") echo "checked"; ?>>Kup teraz
			</label>
		</div>
		<div>
			<label>
				<input name="rodzaj-oferty" type="radio" value="licytacje" <?php if (isset($_SESSION["filtrSzukaj"]["typOfertySzukaj"]) && $_SESSION["filtrSzukaj"]["typOfertySzukaj"] == "licytacje") echo "checked"; ?>>Licytacje
			</label>
		</div>
	</div>
	<div class="form-group">
		<label>
			Cena
		</label><br>
		<input type="number" name="cena-min" step="0.01" class="filtr-cena" placeholder="od" <?php if (isset($_SESSION["filtrSzukaj"]["cenaMinSzukaj"])) echo 'value="'.$_SESSION["filtrSzukaj"]["cenaMinSzukaj"].'"' ?>/> - <input type="number" name="cena-max" step="0.01" class="filtr-cena" placeholder="do" <?php if (isset($_SESSION["filtrSzukaj"]["cenaMaxSzukaj"])) echo 'value="'.$_SESSION["filtrSzukaj"]["cenaMaxSzukaj"].'"' ?>/>
	</div>
	<div class="form-group">
		<label>
			Stan
		</label>
		<div>
			<label>
				<input name="stan" type="radio" value="wszystkie" <?php if (isset($_SESSION["filtrSzukaj"]["stanSzukaj"]) && $_SESSION["filtrSzukaj"]["stanSzukaj"] == "wszystkie") echo "checked"; ?>>Wszystkie
			</label>
		</div>
		<div>
			<label>
				<input name="stan" type="radio" value="nowe" <?php if (isset($_SESSION["filtrSzukaj"]["stanSzukaj"]) && $_SESSION["filtrSzukaj"]["stanSzukaj"] == "nowe") echo "checked"; ?>>Nowe
			</label>
		</div>
		<div>
			<label>
				<input name="stan" type="radio" value="używane" <?php if (isset($_SESSION["filtrSzukaj"]["stanSzukaj"]) && $_SESSION["filtrSzukaj"]["stanSzukaj"] == "używane") echo "checked"; ?>>Używane
			</label>
		</div>
	</div>
	<div class="form-group">
		<label>
			Sposób dostawy
		</label>
		<div>
			<label>
				<input name="kurier-z-gory" type="checkbox" <?php if (isset($_SESSION["filtrSzukaj"]["kurierZGory"]) && $_SESSION["filtrSzukaj"]["kurierZGory"]) echo "checked"; ?>>Kurier (płatność z góry)
			</label>
		</div>
		<div>
			<label>
				<input name="kurier-przy-odbiorze" type="checkbox" <?php if (isset($_SESSION["filtrSzukaj"]["kurierPrzyOdbiorze"]) && $_SESSION["filtrSzukaj"]["kurierPrzyOdbiorze"]) echo "checked"; ?>>Kurier (płatność przy odbiorze)
			</label>
		</div>
		<div>
			<label>
				<input name="odbior-osobisty" type="checkbox" <?php if (isset($_SESSION["filtrSzukaj"]["odbiorOsobisty"]) && $_SESSION["filtrSzukaj"]["odbiorOsobisty"]) echo "checked"; ?>>Odbiór osobisty
			</label>
		</div>
	</div>
	<div class="form-group">
		<label>
			Inne
		</label>
		<div>
			<label>
				<input name="darmowa-dostawa" type="checkbox" <?php if (isset($_SESSION["filtrSzukaj"]["darmowaDostawa"]) && $_SESSION["filtrSzukaj"]["darmowaDostawa"]) echo "checked"; ?>>Darmowa dostawa
			</label>
		</div>
	</div>
	<h4>
		Sortuj według
	</h4>
	<div class="form-group">
		<select name="sortowanie-kryterium">
			<option disabled selected value>
				-- wybierz --
			</option>
			<option <?php if (isset($_SESSION["filtrSzukaj"]["sortowanieKryterium"]) && $_SESSION["filtrSzukaj"]["sortowanieKryterium"] == "cena") echo "selected"; ?>>
				cena
			</option>
			<option <?php if (isset($_SESSION["filtrSzukaj"]["sortowanieKryterium"]) && $_SESSION["filtrSzukaj"]["sortowanieKryterium"] == "cena z dostawą") echo "selected"; ?>>
				cena z dostawą
			</option>
			<option <?php if (isset($_SESSION["filtrSzukaj"]["sortowanieKryterium"]) && $_SESSION["filtrSzukaj"]["sortowanieKryterium"] == "pozostały czas") echo "selected"; ?>>
				pozostały czas
			</option>
		</select>
		<select name="sortowanie-porzadek">
			<option <?php if (isset($_SESSION["filtrSzukaj"]["sortowaniePorzadek"]) && $_SESSION["filtrSzukaj"]["sortowaniePorzadek"] == "rosnąco") echo "selected"; ?>>
				rosnąco
			</option>
			<option <?php if (isset($_SESSION["filtrSzukaj"]["sortowaniePorzadek"]) && $_SESSION["filtrSzukaj"]["sortowaniePorzadek"] == "malejąco") echo "selected"; ?>>
				malejąco
			</option>
		</select>
	</div>
	<button type="submit" class="btn btn-success btn-block">
		Zastosuj
	</button>
</form>
<form class="filtr" method="post" action="czyszczenie_filtrow_szukaj_skrypt.php">
	<input type="hidden" value="<?php echo $_SESSION["adresWFiltrze"]; ?>" name="powrot"/>
	<button type="submit" class="btn btn-deafault btn-block">
		Wyczyść
	</button>
</form>
