<?php
	include_once("klasy/licytacja.php");
	if (session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>Serwis aukcyjny</title>
		<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
		<link rel="stylesheet" href="css/fontello.css"/>
		<link rel="stylesheet" href="style/style.css"/>
		<link rel="stylesheet" href="style/style_strona_glowna.css"/>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-gorne" aria-controls="menu-gorne" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div id="menu-gorne" class="collapse navbar-collapse">
				<a class="navbar-brand" href="index.php">
					Strona główna
				</a>
				<ul class="navbar-nav mr-auto">
					<li class="nav-item dropdown">
						<a id="twoje-konto" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php
								if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true)
								{
									echo $_SESSION["login"];
								}
								else
								{
									echo "Twoje konto";
								}
							?>
						</a>
						<div class="dropdown-menu" aria-labelledby="twoje-konto">
							<?php
								if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true)
								{
									try
									{
										echo '<a class="dropdown-item" href="tworzenie_licytacji.php">
													Utwórz licytację
												</a>
												<a class="dropdown-item" href="tworzenie_kup_teraz.php">
													Utwórz ofertę "kup teraz"
												</a>
												<div class="dropdown-divider"></div>
												<a class="dropdown-item" href="edycja_konta.php">
													Edytuj konto
												</a>
												<a class="dropdown-item" href="moje_licytacje.php?strona=1&trwajace=true">
													Moje licytacje
												</a>
												<a class="dropdown-item" href="moje_kup_teraz.php?strona=1">
													Moje "kup teraz"
												</a>
												<a class="dropdown-item" href="zamowienia_do_zrealizowania.php?strona=1">
													Zamówienia do zrealizowania'.Licytacja::zwrocLiczbeWszystkichNieprzeczytanychZamowien($_SESSION["login"]).'
												</a>
												<a class="dropdown-item" href="licytacje.php?strona=1&trwajace=true">
													Licytacje, w których bierzesz udział
												</a>
												<a class="dropdown-item" href="koszyk.php?strona=1">
													Koszyk
												</a>
												<a class="dropdown-item" href="historia_zakupow.php?strona=1">
													Historia zakupów
												</a>
												<div class="dropdown-divider"></div>';
									}
									catch (PDOException $e) { }
								}
							?>
							<a class="dropdown-item" href="<?php if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true) {echo "wylogowanie.php";} else {echo "logowanie.php";}?>">
								<?php
								if (isset($_SESSION["zalogowany"]) && $_SESSION["zalogowany"] == true)
								{
									echo "Wyloguj";
								}
								else
								{
									echo "Zaloguj";
								}
							?>
							</a>
							<?php
								if (!isset($_SESSION["zalogowany"]) || $_SESSION["zalogowany"] == false)
								{
									echo '<a class="dropdown-item" href="rejestracja.php">
												Utwórz konto
											</a>';
								}
							?>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a id="kategorie" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Kategorie
						</a>
						<div class="dropdown-menu" arialabelledby="kategorie">
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=1">
								Artykuły spożywcze
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=2">
								Biżuteria
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=3">
								Dla dzieci
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=4">
								Dom i ogród
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=5">
								Elektronika
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=6">
								Filmy
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=7">
								Firma
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=8">
								Kolekcje i sztuka
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=9">
								Książki
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=10">
								Motoryzacja
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=11">
								Muzyka
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=12">
								Odzież i obuwie
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=13">
								Sport i wypoczynek
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=14">
								Uroda i zdrowie
							</a>
							<a class="dropdown-item" href="szukaj.php?strona=1&kategoria=15">
								Inne
							</a>
						</div>
					</li>
					<li>
						<form class="form-inline my-2 my-lg-0" action="szukaj.php" method="get">
							<input type="hidden" name="strona" value="1"/>
							<input class="form-control mr-sm-2" type="search" placeholder="Szukaj" name="fraza"/>
							<button class="btn btn-outline-success my-2 my-sm-0" type="submit">
								Szukaj
							</button>
						</form>
					</li>
				</ul>
			</div>
		</nav>