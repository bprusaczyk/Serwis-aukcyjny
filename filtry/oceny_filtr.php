<h3>
	Sortuj
</h3>
<form class="filtr" method="post" action="<?php echo $_SESSION["adresZastosujWFiltrze"]; ?>">
	<input type="hidden" value="<?php echo $_SESSION["adresWFiltrze"]; ?>" name="powrot"/>
	<div class="form-group">
		<select name="sortowanie-porzadek" class="form-control">
			<option <?php if (isset($_SESSION["filtryOceny"]["sortowanie"]) && $_SESSION["filtryOceny"]["sortowanie"] == "rosnąco") echo "selected"; ?>>
				rosnąco
			</option>
			<option <?php if (isset($_SESSION["filtryOceny"]["sortowanie"]) && $_SESSION["filtryOceny"]["sortowanie"] == "malejąco") echo "selected"; ?>>
				malejąco
			</option>
		</select>
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