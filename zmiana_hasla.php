<?php
	include_once("naglowek.php");
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/walidacja.php");
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
			if (isset($_SESSION["zmianaHaslaSukces"]) && $_SESSION["zmianaHaslaSukces"] == true)
			{
				echo '<div class="alert alert-success" role="alert">
							<h4 class="alert-heading">
								Hasło zostało zmienione
							</h4>
						</div>';
				unset($_SESSION["zmianaHaslaSukces"]);
			}
		?>
		<h1>
			Zmień hasło
		</h1>
		<form action="zmiana_hasla_skrypt.php" method="post">
			<div class="form-group">
				<label for="stare-haslo">
					Stare hasło
				</label>
				<input id="stare-haslo" name="stare-haslo" type="password" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("zmianaHaslaBladStareHaslo"); ?>" placeholder="Stare hasło"/>
				<small id="stare-haslo-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("zmianaHaslaBladStareHaslo");
					?>
				</small>
			</div>
			<div class="form-group">
				<label for="nowe-haslo-1">
					Nowe hasło
				</label>
				<input id="nowe-haslo-1" name="nowe-haslo-1" type="password" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("zmianaHaslaBladNoweHaslo1"); ?>" placeholder="Nowe hasło"/>
				<small id="nowe-haslo-1-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("zmianaHaslaBladNoweHaslo1");
					?>
				</small>
			</div>
			<div class="form-group">
				<label for="nowe-haslo-2">
					Powtórz hasło
				</label>
				<input id="nowe-haslo-2" name="nowe-haslo-2" type="password" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("zmianaHaslaBladNoweHaslo2"); ?>" placeholder="Powtórz hasło"/>
				<small id="nowe-haslo-2-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("zmianaHaslaBladNoweHaslo2");
					?>
				</small>
			</div>
			<button type="submit" class="btn btn-success" style="margin-top: 10px;" onclick="return walidacjaZmianaHasla();">
				Zmień
			</button>
		</form>
	</div>
	<div style="clear: both;"></div>
</div>
<div class="div-boczny"></div>
<script src="script/walidacja.js"></script>
<script src="script/walidacja_zmiana_hasla.js"></script>
<?php
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
	include_once("stopka.php");
?>