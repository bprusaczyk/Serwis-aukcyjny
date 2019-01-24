<h3>
	Filtry
</h3>
<form class="filtr" method="post" action="<?php echo $_SESSION["adresZastosujWFiltrze"]; ?>">
	<input type="hidden" value="<?php echo $_SESSION["adresWFiltrze"]; ?>" name="powrot"/>
	<div class="form-group">
		<div>
			<label>
				<input name="filtr" type="radio" value="wszystkie" <?php if (isset($_SESSION["filtrZamowienia"]["filtr"]) && $_SESSION["filtrZamowienia"]["filtr"] == "wszystkie") echo "checked"; ?>>Wszystkie
			</label>
		</div>
		<div>
			<label>
				<input name="filtr" type="radio" value="nieprzeczytane" <?php if (isset($_SESSION["filtrZamowienia"]["filtr"]) && $_SESSION["filtrZamowienia"]["filtr"] == "nieprzeczytane") echo "checked"; ?>>Nieprzeczytane
			</label>
		</div>
		<div>
			<label>
				<input name="filtr" type="radio" value="przeczytane" <?php if (isset($_SESSION["filtrZamowienia"]["filtr"]) && $_SESSION["filtrZamowienia"]["filtr"] == "przeczytane") echo "checked"; ?>>Przeczytane
			</label>
		</div>
		<div>
			<label>
				<input name="filtr" type="radio" value="do realizacji" <?php if (isset($_SESSION["filtrZamowienia"]["filtr"]) && $_SESSION["filtrZamowienia"]["filtr"] == "do realizacji") echo "checked"; ?>>Do realizacji
			</label>
		</div>
	</div>
	<button type="submit" class="btn btn-success btn-block">
		Zastosuj
	</button>
</form>
<form class="filtr" method="post" action="<?php echo $_SESSION["adresWyczyscWFiltrze"]; ?>">
	<input type="hidden" value="<?php echo $_SESSION["adresWFiltrze"]; ?>" name="powrot"/>
	<button type="submit" class="btn btn-deafault btn-block">
		Wyczyść
	</button>
</form>
