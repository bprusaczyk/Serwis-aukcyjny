function walidacjaZmianaHasla()
{
	var walidacja;
	walidacja = wymaganePole("stare-haslo", "stare-haslo-blad", "Podaj stare hasło");
	walidacja = wymaganePole("nowe-haslo-1", "nowe-haslo-1-blad", "Podaj nowe hasło") && kontrolaHasel("nowe-haslo-1", "nowe-haslo-1-blad") && walidacja;
	walidacja = kontrolujPowtorzenieHasla("nowe-haslo-1", "nowe-haslo-2", "nowe-haslo-2-blad") && walidacja;
	return walidacja;
}