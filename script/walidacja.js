function wymaganePole(pole, poleBlad, komunikat)
{
	var pom = document.getElementById(pole).value;
	if(pom == "")
	{
		wyswietlKomunikatOBledzie(pole, poleBlad, komunikat);
		return false;
	}
	else
	{
		usunKomunikatOBledzie(pole, poleBlad);
		return true;
	}
}

function czyWartoscJestMniejszaLubRownaNiz(pole, poleBlad, komunikat, minLiczba)
{
	var liczba = Number(document.getElementById(pole).value);
	if (liczba <= Number(minLiczba))
	{
		usunKomunikatOBledzie(pole, poleBlad);
		return true;
	}
	else
	{
		wyswietlKomunikatOBledzie(pole, poleBlad, komunikat);
		return false;
	}
}

function czyWartoscJestWiekszaNiz(pole, poleBlad, komunikat, minLiczba)
{
	var liczba = Number(document.getElementById(pole).value);
	if (liczba > Number(minLiczba))
	{
		usunKomunikatOBledzie(pole, poleBlad);
		return true;
	}
	else
	{
		wyswietlKomunikatOBledzie(pole, poleBlad, komunikat);
		return false;
	}
}

function czyWartoscJestWiekszaLubRownaNiz(pole, poleBlad, komunikat, minLiczba)
{
	var liczba = Number(document.getElementById(pole).value);
	if (liczba >= Number(minLiczba))
	{
		usunKomunikatOBledzie(pole, poleBlad);
		return true;
	}
	else
	{
		wyswietlKomunikatOBledzie(pole, poleBlad, komunikat);
		return false;
	}
}

function kontrolaEMaila(pole, poleBlad)
{
	if (!(/^\S+@\S+$/.test(document.getElementById(pole).value)))
	{
		wyswietlKomunikatOBledzie(pole, poleBlad, "Wprowadzony adres e-mail ma niepoprawny format");
		return false;
	}
	else
	{
		usunKomunikatOBledzie(pole, poleBlad);
		return true;
	}
}

function kontrolaHasel(pole, poleBlad)
{
	var haslo = document.getElementById(pole).value;
	if(haslo.length < 8)
	{
		wyswietlKomunikatOBledzie(pole, poleBlad, "Hasło powinno zawierać co najmniej 8 znaków");
		return false;
	}
	else
	{
		usunKomunikatOBledzie(pole, poleBlad);
	}
	if(!/[a-zęóąśłżźćń]/.test(haslo) || !/[A-ZĘÓĄŚŁŻŹĆŃ]/.test(haslo) || !/[0-9]/.test(haslo))
	{
		wyswietlKomunikatOBledzie(pole, poleBlad, 
				"Hasło jest za słabe (silne hasło powinno zawierać co najmniej jedną małą literę, jedną dużą literę i jedną cyfrę)");
		return false;
	}
	else
	{
		usunKomunikatOBledzie(pole, poleBlad);
	}
	return true;
}

function kontrolujPowtorzenieHasla(haslo1, haslo2, hasloBlad)
{
	if(document.getElementById(haslo1).value != document.getElementById(haslo2).value)
	{
		wyswietlKomunikatOBledzie(haslo2, hasloBlad, "Hasła nie zgadzają się ");
		return false;
	}
	else
	{
		usunKomunikatOBledzie(haslo2, hasloBlad);
	}
	return true;
}

function sprawdzCzyRadioButtonJestWybrany(radio, pole, poleBlad, komunikat)
{
	for (var i = 0; i < radio.length; i++)
	{
		if (document.getElementById(radio[i]).checked)
		{
			usunKomunikatOBledzie(null, poleBlad);
			document.getElementById(pole).classList.remove("walidacja-blad");
			return true;
		}
	}
	wyswietlKomunikatOBledzie(null, poleBlad, komunikat);
	document.getElementById(pole).scrollIntoView();
	return false;
}

function wyswietlKomunikatOBledzie(pole, poleBlad, komunikat)
{
	document.getElementById(poleBlad).innerHTML = komunikat;
	try
	{
		document.getElementById(pole).classList.add("is-invalid");
		document.getElementById(pole).scrollIntoView();
	}
	catch (e)
	{
		
	}
}

function usunKomunikatOBledzie(pole, poleBlad)
{
	document.getElementById(poleBlad).innerHTML = "";
	try
	{
		document.getElementById(pole).classList.remove("is-invalid");
	}
	catch (e)
	{
		
	}
}