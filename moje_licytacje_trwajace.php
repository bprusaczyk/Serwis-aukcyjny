<div id="tresc">
	<h1>
		Moje licytacje
	</h1>
	<?php
		Licytacja::wypiszOfertyUzytkownikaTrwajaceMojeLicytacje($_SESSION["login"]);
	?>
</div>
<div style="clear: both;"></div>
<nav style="margin-top: 10px;">
	<ul class="pagination justify-content-center">
		<?php
			Licytacja::wypiszNumeryStronUzytkownikTrwajaceMojeLicytacje($_SESSION["login"]);
		?>
	</ul>
</nav>