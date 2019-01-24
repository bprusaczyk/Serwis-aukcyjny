<?php
	class SposobDostawy
	{
		public $nazwa;
		public $cena;
		public $czas;
		
		function __construct($nazwa, $cena, $czas)
		{
			if ($czas == "")
			{
				$this->nazwa = null;
				$this->cena = null;
				$this->czas = null;
			}
			else
			{
				$this->nazwa = $nazwa;
				if ($cena == null)
				{
					$this->cena = "Nie dotyczy";
				}
				else
				{
					$this->cena = $cena;
				}
				$this->czas = $czas." dni roboczych";
			}
		}
	}
?>