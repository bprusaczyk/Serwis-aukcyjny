<?php
	include_once("filtry/klasy/filtr.php");
	include_once("klasy/ocena.php");
	include_once("naglowek.php");
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<div id="menu">
		<ul>
			<li>
				<a href="strona_uzytkownika.php?strona=1&login=<?php echo $_GET["login"]; ?>&oceny=true">
					Oceny
				</a>
			</li>
			<li>
				<a href="strona_uzytkownika.php?strona=1&login=<?php echo $_GET["login"]; ?>&oferty=true">
					Oferty
				</a>
			</li>
		</ul>
		<?php
			if (isset($_GET["oferty"]) && $_GET["oferty"])
			{
				Filtr::wczytajFiltr("filtry/standard.php", "strona_uzytkownika.php?strona=1&login=".$_GET["login"]."&oferty=true");
			}
			elseif (isset($_GET["oceny"]) && $_GET["oceny"])
			{
				Filtr::wczytajFiltr("filtry/oceny_filtr.php", "strona_uzytkownika.php?strona=1&login=".$_GET["login"]."&oceny=true", "filtr_oceny_skrypt.php", "filtr_oceny_wyczysc_skrypt.php");
			}
		?>
	</div>
	<?php
		if (isset($_GET["oceny"]) && $_GET["oceny"])
		{
			include_once("strona_uzytkownika_oceny.php");
		}
		elseif (isset($_GET["oferty"]) && $_GET["oferty"])
		{
			include_once("strona_uzytkownika_oferty.php");
		}
	?>
</div>
<div class="div-boczny"></div>
<?php
	include_once("stopka.php");
?>