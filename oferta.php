<?php
	include_once("klasy/licytacja.php");
	include_once("klasy/uzytkownik.php");
	include_once("klasy/walidacja.php");
	include_once("naglowek.php");
	if (!Licytacja::sprawdzCzyOfertaIstnieje($_GET["oferta"]))
	{
		header("Location: oferta_usunieta.php");
		exit();
	}
	$oferta = new Licytacja($_GET["oferta"]);
?>
<div class="div-boczny">
</div>
<div id="div-glowny">
	<div id="informacje-naglowek">
		<div id="widok-oferty-informacje-zdjecie">
			<div style="height: 300px;">
				<img id="widok-oferty-zdjecie"/>
			</div>
			<button type="button" class="btn btn-outline-info slajder-przycisk" onclick="licznik - 1 < 0 ? licznik = zdjecia.length - 1 : licznik--; zmienZdjecie(zdjecia, licznik);">
				poprzednie
			</button>
			<button type="button" class="btn btn-outline-info slajder-przycisk" onclick="licznik = (licznik + 1) % zdjecia.length; zmienZdjecie(zdjecia, licznik);">
				następne
			</button>
		</div>
		<div id="widok-oferty-informacje-informacje">
			<h1>
				<?php
					echo $oferta->nazwa;
				?>
			</h1>
			<h5>
				Od: <a id="link-login" href="strona_uzytkownika.php?strona=1&login=<?php echo $oferta->login; ?>&oceny=true"><strong><?php echo $oferta->login; ?></strong></a><br>
				Średnia ocena użytkownika:
				<h4>
					<?php
						$oferta->wypiszSredniaOcen();
					?>
				</h4>
			</h5>
			<p>
				<?php
					if ($oferta->stan)
					{
						echo "Stan: <strong>".$oferta->stan."</strong>";
					}
				?>
			</p>
			<h6>
				<?php
					if ($oferta->czas != "")
					{
						if ($oferta->typ == "licytacja")
						{
							echo 'Licytacja kończy się:<br/>'.$oferta->czas;
						}
						else
						{
							echo 'Oferta jest ważna do:<br/>'.$oferta->czas;
						}
					}
				?>
			</h6>
			<h5>
				<?php
					if ($oferta->typ == "Licytacja")
					{
						echo "Aktualna cena:";
					}
					else
					{
						echo "Cena:";
					}
				?>
			</h5>
			<h3>
				<?php
					echo $oferta->cena;
				?>
			</h3>
			<p>
				<?php
					if ($oferta->typ == "licytacja")
					{
						$oferta->zwrocOpisStanuLicytacji();
					}
				?>
			</p>
			<form method="post" action="<?php if ($oferta->typ == "licytacja") { echo "licytowanie_skrypt.php"; } else { echo "dodaj_do_koszyka_skrypt.php"; } ?>">
				<input name="id-oferty" type="hidden" value="<?php echo $_GET["oferta"]; ?>"/>
				<div class="form-group">
					<label for="oferta">
						<?php
							if ($oferta->typ == "licytacja")
							{
								echo "Twoja maksymalna oferta";
							}
							else
							{
								echo "Liczba sztuk";
							}
						?>
					</label>
						<?php
							if ($oferta->typ == "licytacja")
							{
								echo '<input id="oferta" class="form-control" name="oferta" type="number" step="0.01" min="'.($oferta->cena + 0.01).'"/>';
							}
							else
							{
								echo '<input id="liczba-sztuk" class="form-control" name="liczba-sztuk" type="number" step="1" min="1" max="'.$oferta->liczbaSztuk.'"/> z '.$oferta->liczbaSztuk.' dostępnych';
							}
						?>
					
				</div>
				<small id="oferta-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("licytowanieBlad");
					?>
				</small>
				<small id="liczba-sztuk-blad" class="form-text text-muted walidacja-blad">
					<?php
						Walidacja::wyswietlKomunikatOBledzie("dodajDoKoszykaBlad");
					?>
				</small>
				<button type="submit" class="btn btn-warning btn-block<?php if ($oferta->czas != "" && date("Y-m-d H:i:s") > $oferta->czas) { echo " disabled"; } ?>" style="margin-top: 10px;" onclick="return <?php echo ($oferta->typ == "licytacja" ? "walidacjaLicytacja()" : "walidacjaDodajDoKoszyka();"); ?>;">
					<?php
						if ($oferta->typ == "licytacja")
						{
							$wygrywajacy = Licytacja::zwrocWygrywajacegoUzytkownika($_GET["oferta"]);
							if (isset($_SESSION["login"]) && $_SESSION["login"] == $wygrywajacy)
							{
								echo "Zmień swoją ofertę";
							}
							else
							{
								echo "Licytuj";
							}
						}
						else
						{
							echo "Dodaj do koszyka";
						}
					?>
				</button>
			</form>
			<?php
				if ($oferta->typ == "licytacja" && isset($_SESSION["login"]) && $_SESSION["login"] != $oferta->login && $_SESSION["login"] == $wygrywajacy)
				{
					if (!(date("Y-m-d H:i:s") > $oferta->czas))
					{
						echo '<button class="btn btn-danger btn-block" style="margin-top: 10px;" data-toggle="modal" data-target="#usun-oferte">
									Anuluj swoją ofertę
								</button>';
					}
					else
					{
						echo '<button class="btn btn-danger btn-block disabled" style="margin-top: 10px;" data-toggle="modal" data-target="#usun-oferte">
									Anuluj swoją ofertę
								</button>';
					}
				}
			?>
		</div>
		<div style="clear: both;"></div>
	</div>
	<?php
		if ($oferta->opisOferty != "")
		{
			echo '<div class="opis-oferty">
						<h1>
							Opis
						</h1>
						<p>
							'.$oferta->opisOferty.'
						</p>
					</div>';
		}
	?>
	<div class="opis-oferty">
		<h1>
			Dostawa
		</h1>
		<table class="table">
			<thead>
				<tr>
					<th scope="col"/>
					<th scope="col">Cena</th>
					<th scope="col">Czas realizacji</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if ($oferta->kurierZGory->nazwa != null)
					{
						echo '<tr>
									<td>
										'.$oferta->kurierZGory->nazwa.'
									</td>
									<td>
										'.$oferta->kurierZGory->cena.'
									</td>
									<td>
										'.$oferta->kurierZGory->czas.'
									</td>
								</tr>';
					}
					if ($oferta->kurierPrzyOdbiorze->nazwa != null)
					{
						echo '<tr>
									<td>
										'.$oferta->kurierPrzyOdbiorze->nazwa.'
									</td>
									<td>
										'.$oferta->kurierPrzyOdbiorze->cena.'
									</td>
									<td>
										'.$oferta->kurierPrzyOdbiorze->czas.'
									</td>
								</tr>';
					}
					if ($oferta->odbiorOsobisty->nazwa != null)
					{
						echo '<tr>
									<td>
										'.$oferta->odbiorOsobisty->nazwa.'
									</td>
									<td>
										'.$oferta->odbiorOsobisty->cena.'
									</td>
									<td>
										'.$oferta->odbiorOsobisty->czas.'
									</td>
								</tr>';
					}
				?>
			</tbody>
		</table>
		<?php
			if ($oferta->opisDostawy != "")
			{
				echo '<h4>
							Dodatkowe informacje
						</h4>
						<p>
							'.$oferta->opisDostawy.'
						</p>';
			}
		?>
		<div class="opis-oferty">
			<h1>
				Zwroty
			</h1>
			<h4>
				Czas na odstąpienie od umowy
			</h4>
			<p>
				<?php
					echo $oferta->czasNaZwrot;
				?>
			</p>
			<h4>
				Adres do zwrotu
			</h4>
			<p>
				<?php
					echo $oferta->adresDoZwrotu;
				?>
			</p>
			<h4>
				Koszt przesyłki zwrotnej pokrywa
			</h4>
			<p>
				<?php
					echo $oferta->kosztyZwrotu;
				?>
			</p>
			<?php
				if ($oferta->opisZwrotow != "")
				{
					echo '<h4>
								Dodatkowe informacje
							</h4>
							<p>
								'.$oferta->opisZwrotow.'
							</p>';
				}
			?>
		</div>
		<?php
			if ($oferta->okresGwarancji != 0 || $oferta->opisGwarancji != "")
			{
				echo '<div class="opis-oferty">
							<h1>
								Gwarancja
							</h1>
							<h4>
								Okres gwarancji
							</h4>
							<p>
								'.$oferta->okresGwarancji.' miesięcy
							</p>';
				if ($oferta->opisGwarancji != "")
				{
					echo '<h4>
								Dodatkowe informacje
							</h4>
							<p>
								'.$oferta->opisGwarancji.'
							</p>';
				}
				echo '</div>';
			}
		?>
	</div>
</div>
<div class="div-boczny">
</div>
<?php
	if ($oferta->typ=="licytacja" && isset($_SESSION["login"]) && $_SESSION["login"] == $wygrywajacy)
	{
		echo '<div id="usun-oferte" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title">
									Ostrzeżenie
								</h4>
								<button type="button" class="close" data-dismiss="modal">
									&times;
								</button>
							</div>
							<div class="modal-body">
								<p>
									Czy na pewno chcesz anulować swoją ofertę?
								</p>
								<form method="post" action="anulowanie_oferty_skrypt.php">
									<input name="id-oferty" type="hidden" value="'.$_GET["oferta"].'"/>
									<button type="submit" class="btn btn-success btn-block" style="margin-top: 10px;">
										OK
									</button>
								</form>
							</div>
						</div>
					</div>
				</div>';
	}
?>
<script>
	<?php
		echo "var zdjecia = [";
		$zdjecia = Licytacja::zwrocZdjecia($_GET["oferta"]);
		foreach ($zdjecia as $zdjecie)
		{
			echo "\"".$zdjecie."\",";
		}
		echo "];";
	?>
	var licznik = 0;
	if (zdjecia.length == 0)
	{
		document.getElementById("widok-oferty-zdjecie").src = "zdjecia/Brak_zdjęcia.svg";
	}
	else
	{
		document.getElementById("widok-oferty-zdjecie").src = zdjecia[0];
	}
	
	function zmienZdjecie(zdjecia, indeks)
	{
		if (zdjecia.length == 0)
		{
			document.getElementById("widok-oferty-zdjecie").src = "zdjecia/Brak_zdjęcia.svg";
		}
		else
		{
			document.getElementById("widok-oferty-zdjecie").src = zdjecia[licznik];
		}
	}
</script>
<script src="script/walidacja.js"></script>
<script src="script/walidacja_licytacja.js"></script>
<?php
	include_once("stopka.php");
?>