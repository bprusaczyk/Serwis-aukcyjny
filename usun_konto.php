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
		<h1>
			Usuń konto
		</h1>
		<h2>
			Czy na pewno chcesz usunąć konto? Tej decyzji nie można cofnąć.
		</h2>
		<form action="usun_konto_skrypt.php" method="post">
			<div class="form-group">
				<label for="haslo">
					Hasło
				</label>
				<input id="haslo" name="haslo" type="password" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("usunKontoBladHaslo"); ?>" placeholder="Podaj hasło"/>
				<small id="haslo-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("usunKontoBladHaslo");
					?>
				</small>
			</div>
			<button type="submit" class="btn btn-danger" style="margin-top: 10px;" onclick="return walidacjaUsunKonto();">
				Usuń
			</button>
		</form>
	</div>
	<div style="clear: both;"></div>
</div>
<div class="div-boczny"></div>
<script src="script/walidacja.js"></script>
<script src="script/walidacja_usun_konto.js"></script>
<?php
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
	include_once("stopka.php");
?>