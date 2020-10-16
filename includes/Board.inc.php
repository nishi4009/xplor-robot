<?php

declare(strict_types = 1);
include 'class-autoload.php';

$board = new Board((int)5, (int)5);

try{
	$robot = new Robot($board);
	$simulator = new Simulator($robot);

	if(isset($_POST['submit'])){
		$testcase = $_POST['Test'];
		echo $simulator->run($testcase);		
	}
}	
catch(TypeError $e){
	echo " Error!: " . $e->getMessage();
}


?>