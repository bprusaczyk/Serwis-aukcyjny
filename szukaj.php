<?php
	include_once("filtry/klasy/filtr.php");
	include_once("klasy/kategoria.php");
	include_once("klasy/licytacja.php");
	include_once("naglowek.php");
	try
	{
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<h1 style="margin-bottom: 30px;">
		<?php
			if (isset($_GET["kategoria"]))
			{
				echo "Szukasz w kategorii: ".Kategoria::zwrocNazweKategorii($_GET["kategoria"]);
			}
			elseif (isset($_GET["fraza"]))
			{
				echo "Szukasz: \"".htmlentities($_GET["fraza"])."\"";
			}
		?>
	</h1>
	<div id="menu">
		<?php
			if (isset($_GET["fraza"]))
			{
				Filtr::wczytajFiltr("filtry/standard.php", "szukaj.php?strona=1&fraza=".$_GET["fraza"]);
			}
			elseif (isset($_GET["kategoria"]))
			{
				Filtr::wczytajFiltr("filtry/standard.php", "szukaj.php?strona=1&kategoria=".$_GET["kategoria"]);
			}
		?>
	</div>
	<div id="tresc">
		<?php
			if (isset($_GET["kategoria"]))
			{
				Licytacja::wypiszOfertyWgKategorii($_GET["kategoria"]);
			}
			elseif (isset($_GET["fraza"]))
			{
				Licytacja::wypiszOfertyWgFrazy($_GET["fraza"]);
			}
		?>
	</div>
	<div style="clear: both;"></div>
	<nav style="margin-top: 10px;">
		<ul class="pagination justify-content-center">
			<?php
				if (isset($_GET["kategoria"]))
				{
					Licytacja::wypiszNumeryStronKategoria($_GET["kategoria"]);
				}
				elseif (isset($_GET["fraza"]))
				{
					Licytacja::wypiszNumeryStronFraza($_GET["fraza"]);
				}
			?>
		</ul>
	</nav>
</div>
<div class="div-boczny"></div>
<?php
	}
	catch (Exception $e)
	{
		header("Location: blad.php");
	}
	include_once("stopka.php");
?>