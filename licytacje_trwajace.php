<div id="tresc">
	<h1>
		Licytacje, w których bierzesz udział
	</h1>
	<?php
		Licytacja::wypiszLicytacjeWKtorychUzytkownikBierzeUdzial($_SESSION["login"]);
	?>
</div>
<div style="clear: both;"></div>
<nav style="margin-top: 10px;">
	<ul class="pagination justify-content-center">
		<?php
			Licytacja::wypiszNumeryStronLicytacjiTrwajacych($_SESSION["login"]);
		?>
	</ul>
</nav>