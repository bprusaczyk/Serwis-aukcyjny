<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/walidacja.php");
	include_once("naglowek.php");
	Bezpieczenstwo::zablokujDostepZalogowanemuuzytkownikowi();
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<h1>
		Wypełnij poniższy formularz, aby założyć konto.
	</h1>
	<form action="rejestracja_skrypt.php" method="post">
		<div class="form-group">
			<label for="login">
				Login
			</label>
			<input id="login" name="login" type="text" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("rejestracjaBladLogin"); ?>" placeholder="Wpisz login" <?php echo Walidacja::zwrocStaraWartosc("staryLogin"); ?>/>
			<small id="login-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("rejestracjaBladLogin");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="haslo1">
				Hasło
			</label>
			<input id="haslo1" name="haslo1" type="password" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("rejestracjaBladHaslo1"); ?>" placeholder="Wpisz hasło"/>
			<small id="haslo1-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("rejestracjaBladHaslo1");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="haslo2">
				Powtórz hasło
			</label>
			<input id="haslo2" name="haslo2" type="password" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("rejestracjaBladHaslo2"); ?>" placeholder="Wpisz ponownie hasło"/>
			<small id="haslo2-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("rejestracjaBladHaslo2");
				?>
			</small>
		</div>
		<div class="form-group">
			<label for="email">
				E-mail
			</label>
			<input id="email" name="email" type="email" class="form-control<?php echo Walidacja::dodajKlaseIsInvalid("rejestracjaBladMail"); ?>" placeholder="Wpisz swój adres e-mail" <?php echo Walidacja::zwrocStaraWartosc("staryEmail"); ?>/>
			<small id="email-blad" class="form-text text-muted walidacja-blad">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("rejestracjaBladMail");
				?>
			</small>
		</div>
		<div class="form-group">
			<input id="regulamin" name="regulamin" type="checkbox"/>
			<label id="akceptuje-regulamin"<?php if (isset($_SESSION["rejestracjaBladRegulamin"])) {echo " class=\"walidacja-blad\""; unset($_SESSION["rejestracjaBladRegulamin"]);} ?>>Akceptuję regulamin.</label>
		</div>
		<div class="g-recaptcha" data-sitekey="6LewkV8UAAAAAGrSKlBiytOewYVHJJhkMX0ZEofr" style="margin-bottom: 10px"></div>
		<small id="re-captcha-blad" class="form-text text-muted walidacja-blad" style="margin-bottom: 10px;">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("rejestracjaBladReCaptcha");
				?>
			</small>
		<button type="submit" class="btn btn-success" onclick="return walidacjaRejestracji();">Załóż konto</button>
	</form>
</div>
<div class="div-boczny"></div>
<div style="clear: both"></div>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="script/walidacja_rejestracja.js"></script>
<script src="script/walidacja.js"></script>
<?php
	include_once("stopka.php");
?>