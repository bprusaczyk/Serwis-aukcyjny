<h3>
	Filtry
</h3>
<form class="filtr" method="post" action="<?php echo $_SESSION["adresZastosujWFiltrze"]; ?>">
	<input type="hidden" value="<?php echo $_SESSION["adresWFiltrze"]; ?>" name="powrot"/>
	<div class="form-group">
		<div>
			<label>
				<input name="filtr" type="radio" value="wszystkie" <?php if (isset($_SESSION["filtryLicytacjeWygrane"]["filtr"]) && $_SESSION["filtryLicytacjeWygrane"]["filtr"] == "wszystkie") echo "checked"; ?>>Wszystkie
			</label>
		</div>
		<div>
			<label>
				<input name="filtr" type="radio" value="potwierdzone" <?php if (isset($_SESSION["filtryLicytacjeWygrane"]["filtr"]) && $_SESSION["filtryLicytacjeWygrane"]["filtr"] == "potwierdzone") echo "checked"; ?>>Potwierdzone
			</label>
		</div>
		<div>
			<label>
				<input name="filtr" type="radio" value="niepotwierdzone" <?php if (isset($_SESSION["filtryLicytacjeWygrane"]["filtr"]) && $_SESSION["filtryLicytacjeWygrane"]["filtr"] == "niepotwierdzone") echo "checked"; ?>>Niepotwierdzone
			</label>
		</div>
		<div>
			<label>
				<input name="filtr" type="radio" value="nieocenione" <?php if (isset($_SESSION["filtryLicytacjeWygrane"]["filtr"]) && $_SESSION["filtryLicytacjeWygrane"]["filtr"] == "nieocenione") echo "checked"; ?>>Nieocenione
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