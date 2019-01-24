<?php
	include_once("naglowek.php");
	if(isset($_SESSION["blad_serwera"]))
	{
	    echo $_SESSION["blad_serwera"];
	    unset($_SESSION["blad_serwera"]);
	}
?>
		<div class="div-boczny"></div>
		<div id="div-glowny">
			<h1 style="text-align: center;">
				Wybierz kategorię
			</h1>
			<div id="strona-glowna-glowny-kontener">
				<a href="szukaj.php?strona=1&kategoria=1">
					<div id="artykuly-spozywcze" class="strona-glowna-kontener">
						<h1 class="dwie-linijki">
							<i class="demo-icon icon-food"></i><br>
							Artykuły spożywcze
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=2">
					<div id="bizuteria" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-wristwatch"></i><br>
							Biżuteria
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=3">
					<div id="dla-dzieci" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-gamepad"></i><br>
							Dla dzieci
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=4">
					<div id="dom-i-ogrod" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-home"></i><br>
							Dom i ogród
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=5">
					<div id="elektronika" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-television"></i><br>
							Elektronika
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=6">
					<div id="filmy" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-video"></i><br>
							Filmy
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=7">
					<div id="firma" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-pencil"></i><br>
							Firma
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=8">
					<div id="kolekcje-i-sztuka" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-picture"></i><br>
							Kolekcje i sztuka
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=9">
					<div id="ksiazki" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-book"></i><br>
							Książki
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=10">
					<div id="motoryzacja" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-truck"></i><br>
							Motoryzacja
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=11">
					<div id="muzyka" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-music"></i><br>
							Muzyka
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=12">
					<div id="odziez-i-obuwie" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-female"></i><br>
							Odzież i obuwie
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=13">
					<div id="sport-i-wypoczynek" class="strona-glowna-kontener">
						<h1 class="dwie-linijki">
							<i class="demo-icon icon-soccer-ball"></i><br>
							Sport i wypoczynek
						</h1>
					</div>
				</a>
				<a href="szukaj.php?strona=1&kategoria=14">
					<div id="uroda-i-zdrowie" class="strona-glowna-kontener">
						<h1 class="jedna-linijka">
							<i class="demo-icon icon-shower"></i><br>
							Uroda i zdrowie
						</h1>
					</div>
				</a>
			</div>
		</div>
		<div class="div-boczny"></div>
<?php
	include_once("stopka.php");
?>