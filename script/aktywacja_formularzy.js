function aktywujKontrolki(warunekTekst, warunekKontrolka, idKontrolki)
{
	var test = document.getElementById(warunekKontrolka).value;
	if (test == warunekTekst)
	{
		document.getElementById(idKontrolki).setAttribute("disabled", true);
	}
	else
	{
		document.getElementById(idKontrolki).removeAttribute("disabled");
	}
}

function aktywujKontrolkiCheckbox(warunek, warunekKontrolka, idKontrolki)
{
	var test = document.getElementById(warunekKontrolka).checked;
	if (test == warunek)
	{
		document.getElementById(idKontrolki).setAttribute("disabled", true);
	}
	else
	{
		document.getElementById(idKontrolki).removeAttribute("disabled");
	}
}