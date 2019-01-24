<?php
	include_once("naglowek.php");
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/walidacja.php");
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
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
			if (isset($_SESSION["zmianaEMailaSukces"]) && $_SESSION["zmianaEMailaSukces"] == true)
			{
				echo '<div class="alert alert-success" role="alert">
							<h4 class="alert-heading">
								Adres e-mail został zmieniony
							</h4>
						</div>';
				unset($_SESSION["zmianaEMailaSukces"]);
			}
		?>
		<h1>
			Zmień adres e-mail
		</h1>
		<form action="zmiana_maila_skrypt.php" method="post">
			<div class="form-group">
				<label for="haslo">
					Wpisz hasło
				</label>
				<input id="haslo" name="haslo" type="password" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("zmianaEMailaBladHaslo"); ?>" placeholder="Hasło"/>
				<small id="haslo-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("zmianaEMailaBladHaslo");
					?>
				</small>
			</div>
			<div class="form-group">
				<label for="nowy-e-mail">
					Nowy e-mail
				</label>
				<input id="nowy-e-mail" name="nowy-e-mail" type="email" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("zmianaEMailaBladNowyEMail"); ?>" placeholder="Nowy e-mail" <?php echo Walidacja::zwrocStaraWartosc("staryNowyEMail"); ?>/>
				<small id="nowy-e-mail-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("zmianaEMailaBladNowyEMail");
					?>
				</small>
			</div>
			<button type="submit" class="btn btn-success" style="margin-top: 10px;" onclick="return walidacjaZmianyEMaila();">
				Zmień
			</button>
		</form>
	</div>
	<div style="clear: both;"></div>
</div>
<div class="div-boczny"></div>
<script src="script/walidacja.js"></script>
<script src="script/walidacja_zmiana_maila.js"></script>
<?php
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
	include_once("stopka.php");
?>