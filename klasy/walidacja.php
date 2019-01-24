<?php
	class Walidacja
	{
		public static function dodajKlaseIsInvalid($zmienna)
		{
			 if (isset($_SESSION[$zmienna]))
			 {	 
				return " is-invalid";
			 }
			 else
			 {
				 return "";
			 }
		}
		
		public static function wypiszStaraWartosc($zmienna)
		{
			if (isset($_SESSION[$zmienna]))
			{
				echo htmlentities($_SESSION[$zmienna]);
				unset($_SESSION[$zmienna]);
			}
		}
		
		public static function wyswietlKomunikatOBledzie($zmienna)
		{
			if (isset($_SESSION[$zmienna]))
			{
				echo $_SESSION[$zmienna];
				unset($_SESSION[$zmienna]);
			}
		}
		
		public static function zwrocStaraWartosc($zmienna)
		{
			if (isset($_SESSION[$zmienna]))
			{
				$pom = "value=\"".$_SESSION[$zmienna]."\"";
				unset($_SESSION[$zmienna]);
				return $pom;
			}
			else
			{
				return "";
			}
		}
		
		public static function zwrocStaraWartoscRadioButtonow($zmienna, $wartosc)
		{
			if (isset($_SESSION[$zmienna]) && $_SESSION[$zmienna] == $wartosc)
			{
				unset($_SESSION[$zmienna]);
				return "checked";
			}
			else
			{
				return "";
			}
		}
		
		public static function zwrocStaraWartoscRozwijanejListy($zmienna, $wartosc)
		{
			if (!isset($_SESSION[$zmienna]))
			{
				return "";
			}
			if ($_SESSION[$zmienna] == $wartosc)
			{
				unset($_SESSION[$zmienna]);
				return "selected";
			}
			else
			{
				return "";
			}
		}
	}
?>