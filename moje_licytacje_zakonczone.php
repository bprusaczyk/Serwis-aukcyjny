<div id="tresc">
	<h1>
		Moje licytacje
	</h1>
	<?php
		Licytacja::wypiszOfertyUzytkownikaZakonczone($_SESSION["login"]);
	?>
</div>
<div style="clear: both;"></div>
<nav style="margin-top: 10px;">
	<ul class="pagination justify-content-center">
		<?php
			Licytacja::wypiszNumeryStronUzytkownikZakonczone($_SESSION["login"]);
		?>
	</ul>
</nav>