<?php
	include 'Snappy.php';
	use Snappy\query;
	$var =  new query('localhost', 'root', '', 'db_wac');
	$var->help('get');	
?>