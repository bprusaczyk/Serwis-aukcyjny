<div id="tresc">
	<h1>
		<?php
			echo $_GET["login"];
		?>
	</h1>
	<div>
		<h3>
			Średnia ocen użytkownika: <?php echo Uzytkownik::zwrocSredniaOcenUzytkownikaLogin($_GET["login"]); ?>
		</h3>
	</div>
	<?php
		Ocena::wypiszOceny($_GET["login"]);
	?>
</div>
<div style="clear: both;"></div>
<nav style="margin-top: 10px;">
	<ul class="pagination justify-content-center">
		<?php
			Ocena::wypiszNumeryStronOceny($_GET["login"]);
		?>
	</ul>
</nav>
