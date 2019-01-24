<div id="tresc">
	<h1>
		Oferty użytkownika <?php echo $_GET["login"]; ?>
	</h1>
	<?php
		Licytacja::wypiszOfertyUzytkownikaTrwajace($_GET["login"]);
	?>
</div>
<div style="clear: both;"></div>
<nav style="margin-top: 10px;">
	<ul class="pagination justify-content-center">
		<?php
			Licytacja::wypiszNumeryStronUzytkownikTrwajace($_GET["login"]);
		?>
	</ul>
</nav>