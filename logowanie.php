<?php
	session_start();
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/walidacja.php");
	Bezpieczenstwo::zablokujDostepZalogowanemuuzytkownikowi();
	include_once("naglowek.php");
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<h1>
		Zaloguj się
	</h1>
	<form action="logowanie_skrypt.php" method="post">
		<div class="form-group">
			<label for="login-email">
				Login lub e-mail
			</label>
			<input id="login-email" name="login-email" type="text" class="form-control" placeholder="Wpisz login lub e-mail"/>
			<small id="login-email-blad" class="form-text text-muted walidacja-blad"></small>
		</div>
		<div class="form-group">
			<label for="haslo">
				Hasło
			</label>
			<input id="haslo" name="haslo" type="password" class="form-control" placeholder="Wpisz hasło"/>
			<small id="haslo-blad" class="form-text text-muted walidacja-blad"></small>
		</div>
		<div>
			<small id="haslo-blad" class="form-text text-muted walidacja-blad" style="margin-bottom: 10px;">
				<?php
					Walidacja::wyswietlKomunikatOBledzie("logowanieBlad");
				?>
			</small>
		</div>
		<button type="submit" class="btn btn-success" onclick="return walidacjaLogowania();">Zaloguj</button>
	</form>
</div>
<div class="div-boczny"></div>
<script src="script/walidacja_logowanie.js"></script>
<script src="script/walidacja.js"></script>
<?php
	include_once("stopka.php");
?>