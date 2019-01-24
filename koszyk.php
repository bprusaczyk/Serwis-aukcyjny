<?php
	include_once("klasy/bezpieczenstwo.php");
	include_once("klasy/kup_teraz.php");
	include_once("naglowek.php");
	Bezpieczenstwo::zablokujDostepNiezalogowanemuUzytkownikowi()
?>
<div class="div-boczny"></div>
<div id="div-glowny">
	<?php
		if (isset($_SESSION["dodanoDoKoszyka"]) && $_SESSION["dodanoDoKoszyka"])
		{
			echo '<div class="alert alert-success" role="alert">
						<h4 class="alert-heading">
							Towar został dodany do koszyka
						</h4>
					</div>';
			unset($_SESSION["dodanoDoKoszyka"]);
		}
		if (isset($_SESSION["anulowanieZamowienia"]) && $_SESSION["anulowanieZamowienia"])
		{
			echo '<div class="alert alert-success" role="alert">
						<h4 class="alert-heading">
							Zamówienie zostało anulowane
						</h4>
					</div>';
			unset($_SESSION["anulowanieZamowienia"]);
		}
		if (isset($_SESSION["koszykBlad"]))
		{
			echo '<div class="alert alert-danger" role="alert">
						<h4 class="alert-heading">
							'.$_SESSION["koszykBlad"].'
						</h4>
					</div>';
			unset($_SESSION["koszykBlad"]);
		}
	?>
	<h1>
		Koszyk
	</h1>
	<?php
		KupTeraz::wypiszZamowienia($_SESSION["login"]);
	?>
	<nav style="margin-top: 10px;">
		<ul class="pagination justify-content-center">
			<?php
				KupTeraz::wypiszNumeryStronKoszyk($_SESSION["login"]);
			?>
		</ul>
	</nav>	
</div>
<div class="div-boczny"></div>
<script src="script/walidacja_wybor_sposobu_dostawy.js"></script>
<script src="script/walidacja.js"></script>
<?php
	include_once("stopka.php");
?>