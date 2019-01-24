function walidacjaUsunKonto()
{
	var walidacja;
	walidacja = wymaganePole("haslo", "haslo-blad", "Podaj hasło");
	return walidacja;
}