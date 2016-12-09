<?php 
	
	require("../functions.php");
	
	require("../class/Car.class.php");
	$Car = new Car($mysqli);
	
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	$msg = "";
	if(isset($_SESSION["message"])){
		$msg = $_SESSION["message"];
		
		//kui ühe näitame siis kustuta ära, et pärast refreshi ei näitaks
		unset($_SESSION["message"]);
	}
	
	
	if ( isset($_POST["plate"]) && 
		isset($_POST["plate"]) && 
		!empty($_POST["color"]) && 
		!empty($_POST["color"])
	  ) {
		  
		$Car->save($Helper->cleanInput($_POST["plate"]), $Helper->cleanInput($_POST["color"]));
		
	}
	//sorteerib
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	}else {
		//kui ei ole määratud siis vaikimisi id ja ASC

		$sort = "id";
		$direction = "ascending";
	}
	


	//saan kõik auto andmed
	if(isset($_GET["q"])){
		$q = $Helper->cleanInput($_GET["q"]);

	$carData = $Car->get($q, $sort, $direction);

	} else {
		$q= "";
		$carData = $Car->get($q, $sort, $direction);
	}

	//echo "<pre>";
	//var_dump($carData);
	//echo "</pre>";
?>

	<?php require("../header.php"); ?>

<div class= "container">
	<h1>Data</h1>
	<?=$msg;?>
	<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?>!</a>
	<a href="?logout=1">Logi välja</a>
	</p>


		<br><br>
<h2>Salvesta oma lemmikseriaalid</h2>
		<form method="POST">
		<input name="seriesname" placeholder="Seriaali nimi" type="text">
		<input type="submit" value="Salvesta">
		<br>

<h2>Leia infot seriaali kohta </h2>
		<form method="POST">

		<form name="series">
			<select name="menu" onChange="window.document.location.href=this.options[this.selectedIndex].value;" 
			<option selected="selected">Vali üks</option>
				<option value="http://www.imdb.com/title/tt0475784/episodes?ref_=tt_ov_epl">Westworld</option>
				<option value="http://www.imdb.com/title/tt1844624/episodes?ref_=tt_ov_epl">American Horror Story</option>
				<option value="http://www.imdb.com/title/tt1520211/episodes?ref_=tt_ov_epl">The Walking Dead</option>
				<option value="http://www.imdb.com/title/tt1826940/episodes?ref_=tt_ov_epl">New Girl</option>
				<option value="http://www.imdb.com/title/tt1442437/episodes?ref_=tt_ov_epl">Modern Family</option>
				<option value="http://www.imdb.com/title/tt0944947/episodes?ref_=tt_ov_epl">Game Of Thrones</option>
				<option value="http://www.imdb.com/title/tt2306299/episodes?season=4">Vikings</option>
				<option value="http://www.imdb.com/title/tt2707408/episodes?ref_=tt_ov_epl">Narcos</option>
				<option value="http://www.imdb.com/title/tt4158110/episodes?season=2">Mr.Robot</option>
			</select>
		</form>	
		<br><br>

<h2>Salvesta auto</h2>
	<form method="POST">
	
	<label>Auto nr</label><br>
	<input name="plate" type="text">
	<br><br>
	
	<label>Auto värv</label><br>
	<input type="color" name="color" >
	<br><br>
	
	<input type="submit" value="Salvesta">
	
	
	</form>
	
<h2>Autod</h2>
	
	<form>
		<input type="search" name="q" value="<?=$q;?>">
		<input type="submit" value="Otsi">
	</form>
</div>
<?php 
	
	$direction = "ascending";
	if(isset($_GET["direction"])){
		if ($_GET["direction"] =="ascending"){
			$direction = "descending";
		}

	}

	$html = "<table class='table table-striped table table-hover'>";
	
	$html .= "<tr>";

	$html .="<th><a href='?q=".$q."&sort=id&direction=".$direction."'>id</a></th>";	

	$html .="<th><a href='?q=".$q."&sort=plate&direction=".$direction."'>plate</a></th>";	

	$html .="<th><a href='?q=".$q."&sort=color&direction=".$direction."'>color</a></th>";	
		$html .= "<th>id</th>";
		$html .= "<th>plate</th>";
		$html .= "<th>color</th>";
	$html .= "</tr>";
	
	//iga liikme kohta massiivis
	foreach($carData as $c){
		// iga auto on $c
		//echo $c->plate."<br>";
		
		$html .= "<tr>";
			$html .= "<td>".$c->id."</td>";
			$html .= "<td>".$c->plate."</td>";
			$html .= "<td style='background-color:".$c->color."'>".$c->color."</td>";
			$html .= "<td><a class = 'btn btn-default' href='edit.php?id=".$c->id."'>edit.php?id=".$c->id."</a></td>";
			
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
	
	
	$listHtml = "<br><br>";
	
	foreach($carData as $c){
		
		
		$listHtml .= "<h1 style='color:".$c->color."'>".$c->plate."</h1>";
		$listHtml .= "<p>color = ".$c->color."</p>";
	}
	
	echo $listHtml;
	

?>
