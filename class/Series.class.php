<?php
class Series{
	private $connection;
	function __construct($mysqli){

		$this->connection = $mysqli;
	} 



function saveSeries ($seriesname) {
	
	$stmt = $this->connection->prepare("INSERT INTO user_series (seriesname) VALUES (?)");
	
	//kirjutab ette kus täpselt viga on 
	echo $this->connection->error;
	
	$stmt->bind_param("s", $seriesname);
	
	if($stmt->execute()) {
		echo "salvestamine õnnestus";
	} else {
	 	echo "ERROR ".$stmt->error;
	}
	
}
function getAllSeries() {
	
	
	$stmt = $this->connection->prepare("SELECT id, seriesname, FROM user_series");
	echo $this->connection->error;
	$stmt->bind_result($id, $seriesname);
	$stmt->execute();
	
	//tekitan massiivi
	$result = array();
	
	//while tingimus-tee seda kuni on rida andmeid
	//mis vastab select lausele
	// while järgne sulu sisu määrab kaua korratakse
	while($stmt->fetch()) {
		
		//tekitan objekti
		$series = new StdClass();
		$series->id = $id;
		$series->seriesname = $seriesname;
		
		//echo $plate."<br>";
		//igakord massiivi lisan juurde numbrimärgi
		array_push($result, $series);
		
	}
	
	$stmt->close();

	
	return $result;
}
}
?>