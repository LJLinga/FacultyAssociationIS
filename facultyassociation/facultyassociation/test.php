<html>

	<body>

		<?php 

		if (isset($_POST['submit'])) {

			
			if (!empty($_POST['childstatus'])) {

			foreach ($_POST['childstatus'] as $childstatus) {

				echo $childstatus;

			}

			}

		}

		?>

		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

			<div id="childform">
				<div class="childfields">

				<b>Childrens's information</b><p>

				Status of Child: <br>
				<select name="childstatus[]">

					<option value="1">Normal</option>
					<option value="0">Deceased</option>

				</select> <p>

				First name: <input type="text" name="childfirst[]">
				&nbsp; Last name: <input type="text" name="childlast[]">
				&nbsp; Middle name: <input type="text" name="childmiddle[]"> <p>

				Sex: 
				<input type="radio" name="childsex[]" value="0">Male &nbsp;
				<input type="radio" name="childsex[]" value="1">Female <p>

				--- Enter your child's birthdate below --- <p>

				Month: 
				<select name="childmonth[]">

					<option value="">Select Month</option>
					<option value=""> ----- </option>
					<option value="1">January</option>
					<option value="2">February</option>
					<option value="3">March</option>
					<option value="4">April</option>
					<option value="5">May</option>
					<option value="6">June</option>
					<option value="7">July</option>
					<option value="8">August</option>
					<option value="9">September</option>
					<option value="10">October</option>
					<option value="11">November</option>
					<option value="12">December</option>

				</select>

				&nbsp; Day: 
				<select name="childday[]">

					<option value="">Select Day</option>
					<option value=""> ----- </option>

					<?php for($x = 1; $x <= 31; $x++) { ?>

						<option value="<?php echo $x; ?>"><?php echo $x; ?></option>

					<?php } ?>

				</select>

				&nbsp; Year: 
				<select name="childyear[]">

					<option value="">Select Year</option>
					<option value=""> ----- </option>

					<?php for($y = 2017; $y >= 1900; $y--) { ?>

						<option value="<?php echo $y; ?>"><?php echo $y; ?></option>

					<?php } ?>

				</select>

				<p>

				</div>
			</div>

				<button name="addchild" class="addchild">Add child</button> &nbsp;
				<button name="removechild" class="removechild">Remove previously added field</button>

				<input type="submit" name="submit" value="Submit Application">

		</form>

		<script type="text/javascript" src="jquery-3.1.1.min.js"></script>
		<script>

			$(document).ready(function(){

				var iS = 1;
				var iC = 1;
				var sibfields = $('.siblingfields:first');
				var childfields = $('.childfields:first');

				/* adding child and sibling fields */

				$('body').on('click', '.addsibling', function() {

					iS++;

					var section = sibfields.clone().find(':input').each(function() {

						var newId = this.id + iS;

						this.id = newId;

					}).end().appendTo('#siblingform');

					return false;

				});

				$('body').on('click', '.addchild', function() {

					iC++;

					var section2 = childfields.clone().find(':input').each(function() {

						var newId2 = this.id + iC;

						this.id = newId2;

					}).end().appendTo('#childform');

					return false;

				});

				/* removing child and sibling fields */

				$('#siblingform').on('click', '.removesibling', function() {

					$this.parent().fadeOut(300, function() {

						$(this).parent().parent().empty();
						return false;

					});

					return false;

				});

				$('#childform').on('click', '.removechild', function() {

					$this.parent().fadeOut(300, function() {

						$(this).parent().parent().empty();
						return false;

					});

					return false;

				});

			});

		</script>

	</body>

</html>