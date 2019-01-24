function walidacjaRejestracji()
{
	var walidacja = true;
	var pustyLogin = wymaganePole("login", "login-blad", "Wpisz login")
	walidacja = pustyLogin;
	if (pustyLogin)
	{
		if (!/^[A-Za-z0-9]+$/.test(document.getElementById("login").value))
		{
			wyswietlKomunikatOBledzie("login", "login-blad", "Login może zawierać tylko litery alfabetu łacińskiego i cyfry");
			walidacja = false;
		}
		else
		{
			usunKomunikatOBledzie("login", "login-blad");
		}
	}
	var pustyEmail = wymaganePole("email", "email-blad", "Wpisz swój adres e-mail");
	walidacja = pustyEmail && walidacja;
	if(pustyEmail)
	{
		if(!(/^\S+@\S+$/.test(document.getElementById("email").value)))
		{
			wyswietlKomunikatOBledzie("email", "email-blad", "Wprowadzony adres e-mail ma niepoprawny format");
			walidacja = false;
		}
		else
		{
			usunKomunikatOBledzie("email", "email-blad");
		}
	}
	walidacja = kontrolaHasla() && walidacja;
	walidacja = kontrolaPowtorzeniaHasla() && walidacja;
	if(!document.getElementById("regulamin").checked)
	{
		document.getElementById("akceptuje-regulamin").classList.add("walidacja-blad");
		walidacja = false;
	}
	else
	{
		document.getElementById("akceptuje-regulamin").classList.remove("walidacja-blad");
	}
	return walidacja;
}

function kontrolaHasla()
{
	var haslo = document.getElementById("haslo1").value;
	if(haslo.length < 8)
	{
		wyswietlKomunikatOBledzie("haslo1", "haslo1-blad", "Hasło powinno zawierać co najmniej 8 znaków");
		return false;
	}
	else
	{
		usunKomunikatOBledzie("haslo1", "haslo1-blad");
	}
	if(!/[a-zęóąśłżźćń]/.test(haslo) || !/[A-ZĘÓĄŚŁŻŹĆŃ]/.test(haslo) || !/[0-9]/.test(haslo))
	{
		wyswietlKomunikatOBledzie("haslo1", "haslo1-blad", 
				"Hasło jest za słabe (silne hasło powinno zawierać co najmniej jedną małą literę, jedną dużą literę i jedną cyfrę)");
		return false;
	}
	else
	{
		usunKomunikatOBledzie("haslo1", "haslo1-blad");
	}
	return true;
}

function kontrolaPowtorzeniaHasla()
{
	if(document.getElementById("haslo1").value != document.getElementById("haslo2").value)
	{
		wyswietlKomunikatOBledzie("haslo2", "haslo2-blad", "Hasła nie zgadzają się ");
		return false;
	}
	else
	{
		usunKomunikatOBledzie("haslo2", "haslo2-blad");
	}
	return true;
}