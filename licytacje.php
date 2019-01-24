<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("filtry/klasy/filtr.php");
	include_once("klasy/licytacja.php");
	include_once("naglowek.php");
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi();
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<div id="menu">
		<ul>
			<li>
				<a href="licytacje.php?strona=1&trwajace=true">
					Trwające
				</a>
			</li>
			<li>
				<a href="licytacje.php?strona=1&wygrane=true">
					Wygrane
				</a>
			</li>
		</ul>
		<?php
			if (isset($_GET["trwajace"]) && $_GET["trwajace"])
			{
				Filtr::wczytajFiltr("filtry/licytacje.php", "licytacje.php?strona=1&trwajace=true", "filtr_licytacje_skrypt.php", "filtr_licytacje_wyczysc_skrypt.php");
			}
			elseif (isset($_GET["wygrane"]) && $_GET["wygrane"])
			{
				Filtr::wczytajFiltr("filtry/licytacje_zakonczone_filtr.php", "licytacje.php?strona=1&wygrane=true", "filtr_licytacje_zakonczone_skrypt.php", "filtr_licytacje_zakonczone_wyczysc_skrypt.php");
			}
		?>
	</div>
	<?php
		if (isset($_GET["trwajace"]) && $_GET["trwajace"])
		{
			include_once("licytacje_trwajace.php");
		}
		elseif (isset($_GET["wygrane"]) && $_GET["wygrane"])
		{
			include_once("licytacje_wygrane.php");
		}
	?>
</div>
<div class="div-boczny"></div>
<script src="script/walidacja_wybor_sposobu_dostawy.js"></script>
<script src="script/walidacja.js"></script>
<script src="script/wypisywanie_oceny.js"></script>
<?php
	include_once("stopka.php");
?>