<div id="tresc">
	<?php
		if (isset($_SESSION["ocenaDodana"]) && $_SESSION["ocenaDodana"])
		{
			echo '<div class="alert alert-success" role="alert">
						<h4 class="alert-heading">
							Twoja ocena została dodana
						</h4>
					</div>';
			unset($_SESSION["ocenaDodana"]);
		}
	?>
	<h1>
		Licytacje, które wygrałeś
	</h1>
	<?php
		Licytacja::wypiszWygraneLicytacje($_SESSION["login"]);
	?>
</div>
<div style="clear: both;"></div>
<nav style="margin-top: 10px;">
	<ul class="pagination justify-content-center">
		<?php
			Licytacja::wypiszNumeryStronLicytacjiWygranych($_SESSION["login"]);
		?>
	</ul>
</nav>