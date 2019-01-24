function walidacjaLicytacja()
{
	var walidacja;
	walidacja = wymaganePole("oferta", "oferta-blad", "Podaj swoją ofertę") && czyWartoscJestWiekszaLubRownaNiz("oferta", "oferta-blad", "Twoja oferta musi być większa niż dotychczasowa", document.getElementById("oferta").min) && czyWartoscJestMniejszaLubRownaNiz("oferta", "oferta-blad", "Niedozwolona wartość", 99999999.99);
	return walidacja;
}

function walidacjaDodajDoKoszyka()
{
	var walidacja = wymaganePole("liczba-sztuk", "liczba-sztuk-blad", "Podaj liczbę sztuk w zamówieniu") && czyWartoscJestWiekszaNiz("liczba-sztuk", "liczba-sztuk-blad", "Niepoprawna wartość", 0) && czyWartoscJestMniejszaLubRownaNiz("liczba-sztuk", "liczba-sztuk-blad", "Towar w podanej liczbie jest niedostępny", document.getElementById("liczba-sztuk").max);
	return walidacja;
}