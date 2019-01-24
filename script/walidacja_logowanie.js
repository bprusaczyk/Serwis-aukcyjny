function walidacjaLogowania()
{
	var walidacja = true;
	walidacja = wymaganePole("login-email", "login-email-blad", "Wpisz login lub e-mail");
	walidacja = wymaganePole("haslo", "haslo-blad", "Wpisz hasło") && walidacja;
	return walidacja;
}