<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("filtry/klasy/filtr.php");
	include_once("klasy/kup_teraz.php");
	include_once("naglowek.php");
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<div id="menu">
		Zamówienia do zrealizowania
		<ul>
			<li>
				<a href="zamowienia_do_zrealizowania.php?strona=1">
					Kup teraz<?php echo KupTeraz::zwrocLiczbeNieprzeczytanychZamowien($_SESSION["login"]); ?>
				</a>
			</li>
			<li>
				<a href="moje_licytacje.php?strona=1&zakonczone=true">
					Licytacje<?php echo Licytacja::zwrocLiczbeNieprzeczytanychZamowien($_SESSION["login"]); ?>
				</a>
			</li>
		</ul>
		<?php
			Filtr::wczytajFiltr("filtry/zamowienia_do_zrealizowania.php", "zamowienia_do_zrealizowania.php?strona=1", "filtr_zamowienia_kup_teraz_skrypt.php", "filtr_zamowienia_kup_teraz_wyczysc_skrypt.php");
		?>
	</div>
	<div id="tresc">
		<h1>
			Zamówienia do zrealizowania
		</h1>
		<?php
			KupTeraz::wypiszZamowieniaDoZrealizowania($_SESSION["login"]);
		?>
	</div>
	<div style="clear: both"></div>
	<nav style="margin-top: 10px;">
		<ul class="pagination justify-content-center">
			<?php
				KupTeraz::wypiszNumeryStronZamowieniaDoZrealizowania($_SESSION["login"]);
			?>
		</ul>
	</nav>
</div>
<div class="div-boczny"></div>
<?php
	include_once("stopka.php");
?>