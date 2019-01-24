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
			Filtr::wczytajFiltr("filtry/standard.php", "moje_kup_teraz.php?strona=1")
		?>
	</div>
	<div id="tresc">
		<?php
			if (isset($_SESSION["kupTerazUtworzone"]) && $_SESSION["kupTerazUtworzone"])
			{
				echo '<div class="alert alert-success" role="alert">
							<h4 class="alert-heading">
								Oferta została utworzona
							</h4>
						</div>';
				unset($_SESSION["kupTerazUtworzone"]);
			}
		?>
		<h1>
			Moje oferty z opcją zakupu natychmiastowego
		</h1>
		<?php
			KupTeraz::wypiszKupTerazUzytkownika($_SESSION["login"]);
		?>
		
	</div>
	<div style="clear: both;"></div>
	<nav style="margin-top: 10px;">
		<ul class="pagination justify-content-center">
			<?php
				KupTeraz::wypiszNumeryStronKupTerazUzytkownika($_SESSION["login"]);
			?>
		</ul>
	</nav>
</div>
<div class="div-boczny"></div>
<?php
	include_once("stopka.php");
?>