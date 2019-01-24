function walidacjaZmianyEMaila()
{
	var walidacja;
	walidacja = wymaganePole("haslo", "haslo-blad", "Podaj hasło");
	walidacja = wymaganePole("nowy-e-mail", "nowy-e-mail-blad", "Podaj nowy adres e-mail") && kontrolaEMaila("nowy-e-mail", "nowy-e-mail-blad") && walidacja;
	return walidacja;
}