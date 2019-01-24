<?php
	include_once("naglowek.php");
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/uzytkownik.php");
	Bezpieczenstwo::zablokujDostepNiezalogowanemuuzytkownikowi();
	try
	{
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<div id="menu">
		<ul>
			<li>
				<a href="edycja_konta.php">
					Edytuj dane kontaktowe
				</a>
			</li>
			<li>
				<a href="zmiana_hasla.php">
					Zmień hasło
				</a>
			</li>
			<li>
				<a href="zmiana_maila.php">
					Zmień e-mail
				</a>
			</li>
			<li>
				<a href="usun_konto.php">
					Usuń konto
				</a>
			</li>
		</ul>
	</div>
	<div id="tresc">
		<?php
			if (isset($_SESSION["zmianaDanychOsobowychSukces"]) && $_SESSION["zmianaDanychOsobowychSukces"] == true)
			{
				echo '<div class="alert alert-success" role="alert">
							<h4 class="alert-heading">
								Dane zostały zmienione
							</h4>
						</div>';
				unset($_SESSION["zmianaDanychOsobowychSukces"]);
			}
			if (isset($_SESSION["edycjaDanychOsobowychStronaPowrotu"]))
			{
				echo '<div class="alert alert-danger" role="alert">
							<h4 class="alert-heading">
								Najpierw musisz podać swoje dane osobowe
							</h4>
						</div>';
			}
		?>
		<h1>
			Twoje dane osobowe
		</h1>
		<form action="edycja_konta_skrypt.php" method="post">
			<div class="form-group">
				<label for="imie">
					Imię (imiona)
				</label>
				<input id="imie" name="imie" type="text" class="form-control" placeholder="Imię (imiona)" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "imie"); ?>"/>
			</div>
			<div class="form-group">
				<label for="nazwisko">
					Nazwisko
				</label>
				<input id="nazwisko" name="nazwisko" type="text" class="form-control" placeholder="Nazwisko" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "nazwisko"); ?>"/>
			</div>
			<div class="form-group">
				<label for="numer-konta-bankowego">
					Numer konta bankowego
				</label>
				<input id="numer-konta-bankowego" name="numer-konta-bankowego" type="text" class="form-control" placeholder="Numer konta bankowego" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "numerKontaBankowego"); ?>"/>
			</div>
			<h5>
				Adres
			</h5>
			<div class="form-group">
				<label for="kraj">
					Kraj
				</label>
				<input id="kraj" name="kraj" type="text" class="form-control" placeholder="Kraj" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "kraj"); ?>"/>
			</div>
			<div class="form-group">
				<label for="miejscowosc">
					Miejscowość
				</label>
				<input id="miejscowosc" name="miejscowosc" type="text" class="form-control" placeholder="Miejscowość" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "miejscowosc"); ?>"/>
			</div>
			<div class="form-group">
				<label for="ulica">
					Ulica (*)
				</label>
				<input id="ulica" name="ulica" type="text" class="form-control" placeholder="Ulica" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "ulica"); ?>"/>
			</div>
			<div class="form-group">
				<label for="numer-budynku">
					Numer budynku
				</label>
				<input id="numer-budynku" name="numer-budynku" type="text" class="form-control" placeholder="Numer budynku" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "numerBudynku"); ?>"/>
			</div>
			<div class="form-group">
				<label for="numer-mieszkania">
					Numer mieszkania (*)
				</label>
				<input id="numer-mieszkania" name="numer-mieszkania" type="text" class="form-control" placeholder="Numer mieszkania" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "numerMieszkania"); ?>"/>
			</div>
			<div class="form-group">
				<label for="kod-pocztowy">
					Kod pocztowy
				</label>
				<input id="kod-pocztowy" name="kod-pocztowy" type="text" class="form-control" placeholder="Kod pocztowy" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "kodPocztowy"); ?>"/>
			</div>
			<span>
			<span>
				(*) Jeżeli brak, pozostaw puste
			</span>
			<h5 style="margin-top: 10px;">
				Dane do kontaktu
			</h5>
			<div class="form-group">
				<label for="numer-telefonu">
					Numer telefonu
				</label>
				<input id="numer-telefonu" name="numer-telefonu" type="text" class="form-control" placeholder="Numer telefonu" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "numerTelefonu"); ?>"/>
			</div>
			<div class="form-group">
				<label for="adres-email">
					Adres e-mail
				</label>
				<input id="adres-email" name="adres-email" type="text" class="form-control" placeholder="Adres e-mail" value="<?php echo Uzytkownik::zwrocDaneOsobowe($_SESSION["login"], "emailKontaktowy"); ?>"/>
			</div>
			<br>
			<button type="submit" class="btn btn-success" style="margin-top: 10px;">
				Zmień
			</button>
		</form>
	</div>
	<div style="clear: both;"></div>
</div>
<div class="div-boczny"></div>
<?php
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
	include_once("stopka.php");
?>