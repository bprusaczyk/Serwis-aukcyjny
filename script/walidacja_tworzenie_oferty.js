function walidacjaTworzeniaOferty()
{
	var walidacja;
	walidacja = wymaganePole("nazwa", "nazwa-blad", "Podaj nazwę oferty");
	walidacja = czyWartoscJestWiekszaNiz("cena", "cena-blad", "Cena musi mieć wartość większą od 0", 0) && czyWartoscJestMniejszaLubRownaNiz("cena", "cena-blad", "Niedozwolona wartość ceny", 99999999.99) && walidacja;
	walidacja = wymaganePole("data-koniec", "data-koniec-blad", "Podaj datę i godzinę zakończenia licytacji") && walidacja;
	walidacja = wymaganePole("kategoria", "kategoria-blad", "Wybierz kategorię oferty") && walidacja;
	if (document.getElementById("kategoria").value != "Artykuły spożywcze")
	{
		var stan = ["stan-nowy", "stan-uzywany"];
		walidacja = sprawdzCzyRadioButtonJestWybrany(stan, "stan-napis", "stan-blad", "Wybierz stan towaru") && walidacja;
	}
	else
	{
		document.getElementById("stan-blad").innerHTML = "";
	}
	var dostawa = ["kurier-z-gory", "kurier-przy-odbiorze", "odbior-osobisty"];
	walidacja = sprawdzCzyRadioButtonJestWybrany(dostawa, "dostawa-napis", "dostawa-blad", "Wybierz przynajmniej jeden sposób dostawy") && wymaganePoleDostawa("kurier-z-gory", "cena-kurier-z-gory", "czas-kurier-z-gory") && wymaganePoleDostawa("kurier-przy-odbiorze", "cena-kurier-przy-odbiorze", "czas-kurier-przy-odbiorze") && wymaganePoleDostawa2("odbior-osobisty", "czas-odbior-osobisty") && walidacja;
	walidacja = czyWartoscJestWiekszaLubRownaNiz("czas-na-zwrot", "czas-na-zwrot-blad", "Czas na odstąpienie od umowy nie może być mniejszy niż 14 dni", 14) && czyWartoscJestMniejszaLubRownaNiz("czas-na-zwrot", "czas-na-zwrot-blad", "Niedozwolona wartość", 2147483647) && walidacja;
	walidacja = wymaganePole("adres-zwrotu", "adres-zwrotu-blad", "Podaj adres do zwrotu") && walidacja;
	var zwroty= ["koszty-zwrotu-sprzedajacy", "koszty-zwrotu-kupujacy"];
	walidacja = sprawdzCzyRadioButtonJestWybrany(zwroty, "zwroty-napis", "zwroty-blad", "Podaj, kto ponosi koszty przesyłki zwrotnej") && walidacja;
	walidacja = wymaganePole("gwarancja", "gwarancja-blad", "Podaj okres gwarancji (jeżeli brak gwarancji wpisz 0)") && czyWartoscJestWiekszaLubRownaNiz("gwarancja", "gwarancja-blad", "Niepoprawny wpis", 0) && walidacja;
	return walidacja;
}

function walidacjaTworzeniaKupTeraz()
{
	var walidacja;
	walidacja = wymaganePole("nazwa", "nazwa-blad", "Podaj nazwę oferty");
	walidacja = czyWartoscJestWiekszaNiz("cena", "cena-blad", "Cena musi mieć wartość większą od 0", 0) && czyWartoscJestMniejszaLubRownaNiz("cena", "cena-blad", "Niedozwolona wartość ceny", 99999999.99) && walidacja;
	walidacja = czyWartoscJestWiekszaNiz("liczba-sztuk", "liczba-sztuk-blad", "Liczba sztuk musi być większa od 0", 0) && czyWartoscJestMniejszaLubRownaNiz("liczba-sztuk", "liczba-sztuk-blad", "Niedozwolona wartość", 2147483647) && walidacja;
	walidacja = wymaganePole("kategoria", "kategoria-blad", "Wybierz kategorię oferty") && walidacja;
	if (document.getElementById("kategoria").value != "Artykuły spożywcze")
	{
		var stan = ["stan-nowy", "stan-uzywany"];
		walidacja = sprawdzCzyRadioButtonJestWybrany(stan, "stan-napis", "stan-blad", "Wybierz stan towaru") && walidacja;
	}
	else
	{
		document.getElementById("stan-blad").innerHTML = "";
	}
	var dostawa = ["kurier-z-gory", "kurier-przy-odbiorze", "odbior-osobisty"];
	walidacja = sprawdzCzyRadioButtonJestWybrany(dostawa, "dostawa-napis", "dostawa-blad", "Wybierz przynajmniej jeden sposób dostawy") && wymaganePoleDostawa("kurier-z-gory", "cena-kurier-z-gory", "czas-kurier-z-gory") && wymaganePoleDostawa("kurier-przy-odbiorze", "cena-kurier-przy-odbiorze", "czas-kurier-przy-odbiorze") && wymaganePoleDostawa2("odbior-osobisty", "czas-odbior-osobisty") && walidacja;
	walidacja = czyWartoscJestWiekszaLubRownaNiz("czas-na-zwrot", "czas-na-zwrot-blad", "Czas na odstąpienie od umowy nie może być mniejszy niż 14 dni", 14) && czyWartoscJestMniejszaLubRownaNiz("czas-na-zwrot", "czas-na-zwrot-blad", "Niedozwolona wartość", 2147483647) && walidacja;
	walidacja = wymaganePole("adres-zwrotu", "adres-zwrotu-blad", "Podaj adres do zwrotu") && walidacja;
	var zwroty= ["koszty-zwrotu-sprzedajacy", "koszty-zwrotu-kupujacy"];
	walidacja = sprawdzCzyRadioButtonJestWybrany(zwroty, "zwroty-napis", "zwroty-blad", "Podaj, kto ponosi koszty przesyłki zwrotnej") && walidacja;
	walidacja = wymaganePole("gwarancja", "gwarancja-blad", "Podaj okres gwarancji (jeżeli brak gwarancji wpisz 0)") && czyWartoscJestWiekszaLubRownaNiz("gwarancja", "gwarancja-blad", "Niepoprawny wpis", 0) && walidacja;
	return walidacja;
}

function wymaganePoleDostawa(checkbox, cena, czas)
{
	var walidacja;
	if (document.getElementById(checkbox).checked)
	{
		walidacja = wymaganePole(cena, "dostawa-blad", "Wypełnij pola dotyczące ceny dostawy i czasu realizacji zamówienia (jeżeli przesyłka jest darmowa wpisz 0)");
		walidacja = wymaganePole(czas, "dostawa-blad", "Wypełnij pola dotyczące ceny dostawy i czasu realizacji zamówienia (jeżeli przesyłka jest darmowa wpisz 0)") && walidacja;
		return walidacja;
	}
	else
	{
		return true;
	}
}

function wymaganePoleDostawa2(checkbox, czas)
{
	var walidacja;
	if (document.getElementById(checkbox).checked)
	{
		walidacja = wymaganePole(czas, "dostawa-blad", "Wypełnij pola dotyczące ceny dostawy i czasu realizacji zamówienia (jeżeli przesyłka jest darmowa wpisz 0)");
		return walidacja;
	}
	else
	{
		return true;
	}
}