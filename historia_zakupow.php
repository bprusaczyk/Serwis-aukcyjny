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
		<?php
			Filtr::wczytajFiltr("filtry/historia_zakupow_filtr.php", "historia_zakupow.php?strona=1", "filtr_historia_zakupow_skrypt.php", "filtr_historia_zakupow_wyczysc_skrypt.php");
		?>
	</div>
	<div id="tresc">
		<?php
			if (isset($_SESSION["potwierdzenieZamowienia"]) && $_SESSION["potwierdzenieZamowienia"])
			{
				echo '<div class="alert alert-success" role="alert">
							<h4 class="alert-heading">
								'.$_SESSION["potwierdzenieZamowienia"].'
							</h4>
						</div>';
				unset($_SESSION["potwierdzenieZamowienia"]);
			}
			if (isset($_SESSION["ocenaDodana"]) && $_SESSION["ocenaDodana"])
			{
				echo '<div class="alert alert-success" role="alert">
							<h4 class="alert-heading">
								Twoja ocena została dodana
							</h4>
						</div>';
				unset($_SESSION["ocenaDodana"]);
			}
		?>
		<h1>
			Historia zakupów
		</h1>
		<?php
			KupTeraz::wypiszHistorieZakupow($_SESSION["login"]);
		?>
		<nav style="margin-top: 10px;">
			<ul class="pagination justify-content-center">
				<?php
					KupTeraz::wypiszNumeryStronHistoriaZakupow($_SESSION["login"]);
				?>
			</ul>
		</nav>
	</div>
</div>
<div class="div-boczny"></div>
<script src="script/wypisywanie_oceny.js"></script>
<?php
	include_once("stopka.php");
?>