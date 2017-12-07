<?php

	$dbc=mysqli_connect('localhost', 'root', '', 'facultyassociation');

	if (!$dbc) {

		die('Could not connect: '.mysql_error());

	}

?>