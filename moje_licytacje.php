<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("filtry/klasy/filtr.php");
	include_once("klasy/kup_teraz.php");
	include_once("klasy/licytacja.php");
	include_once("naglowek.php");
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<?php
		if (isset($_SESSION["licytacjaUtworzona"]) && $_SESSION["licytacjaUtworzona"])
		{
			echo '<div class="alert alert-success" role="alert">
						<h4 class="alert-heading">
							Licytacja została utworzona
						</h4>
					</div>';
			unset($_SESSION["licytacjaUtworzona"]);
		}
	?>
	<div id="menu">
		<?php
			if (isset($_GET["zakonczone"]) && $_GET["zakonczone"])
			{
				echo 'Zamówienia do zrealizowania
						<ul>
							<li>
								<a href="zamowienia_do_zrealizowania.php?strona=1">
									Kup teraz'.KupTeraz::zwrocLiczbeNieprzeczytanychZamowien($_SESSION["login"]).'
								</a>
							</li>
							<li>
								<a href="moje_licytacje.php?strona=1&zakonczone=true">
									Licytacje'.Licytacja::zwrocLiczbeNieprzeczytanychZamowien($_SESSION["login"]).'
								</a>
							</li>
						</ul>';
			}
		?>
		Licytacje
		<ul>
			<li>
				<a href="moje_licytacje.php?strona=1&trwajace=true">
					Trwające
				</a>
			</li>
			<li>
				<a href="moje_licytacje.php?strona=1&zakonczone=true">
					Zakończone
				</a>
			</li>
		</ul>
		<?php
			if (isset($_GET["trwajace"]) && $_GET["trwajace"])
			{
				Filtr::wczytajFiltr("filtry/standard.php", "moje_licytacje.php?strona=1&trwajace=true");
			}
			elseif (isset($_GET["zakonczone"]) && $_GET["zakonczone"])
			{
				Filtr::wczytajFiltr("filtry/zamowienia_do_zrealizowania.php", "moje_licytacje.php?strona=1&zakonczone=true", "filtr_zamowienia_skrypt.php", "filtr_zamowienia_wyczysc_skrypt.php");
			}
		?>
	</div>
	<?php
		if (isset($_GET["trwajace"]) && $_GET["trwajace"])
		{
			include_once("moje_licytacje_trwajace.php");
		}
		elseif (isset($_GET["zakonczone"]) && $_GET["zakonczone"])
		{
			include_once("moje_licytacje_zakonczone.php");
		}
	?>
<div class="div-boczny"></div>
<?php
	include_once("stopka.php");
?>