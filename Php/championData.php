<?php
	//Create mongo db
	$mongCon = new Mongo();
	//Database variables
	$hosts = 'localhost';
	$port = 27017;

	$dbname = $mongCon->selectDB('lolstattools');
	$collection = $dbname->homepage;
	$json = $collection->findOne(array("_id" => "championData"));
	$regions = json_encode($json);
	echo $regions;
?>