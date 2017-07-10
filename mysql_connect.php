<?php
$dbc=mysqli_connect('localhost','root','12345','universitydata');

if (!$dbc) {
 die('Could not connect: '.mysql_error());
}

?>