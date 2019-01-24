function walidacjaWyborSposobuDostawy(numer)
{
	var walidacja;
	walidacja = wymaganePole("sposob-dostawy-"+numer, "sposob-dostawy-blad-"+numer, "Wybierz sposób dostawy");
	return walidacja;
}