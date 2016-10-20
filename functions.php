<?php 
	require("../../../kristel/config.php");
	// functions.php
	// et saab kasutada $_SESSION muutujaid
	// k�igis failides mis on selle failiga seotud
	session_start();
	
	
	$database = "if16_krisroos_3";
	
	//var_dump($GLOBALS);
	
	function signup($email, $password) {
		
		$mysqli = new mysqli(
		
		$GLOBALS["serverHost"], 
		$GLOBALS["serverUsername"],  
		$GLOBALS["serverPassword"],  
		$GLOBALS["database"]
		
		);

		$stmt = $mysqli->prepare("INSERT INTO Kasutajad_sample (email, password) VALUES (?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ss", $email, $password );

		if ( $stmt->execute() ) {
			echo "salvestamine �nnestus";	
		} else {	
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	function saveNote($note, $color) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],  $GLOBALS["serverPassword"],  $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO colornotes (note, color) VALUES ('$note', '$color')");
		echo $mysqli->error;
		//bind-param konrollib, et ei saaks jama sisestada l�nka
		$stmt->bind_param("ss", $note, $color );
		if ( $stmt->execute() ) {
			echo "salvestamine �nnestus";	
		} else {	
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	function getAllNotes (){
		
		$mysqli = new mysqli (
		
		$GLOBALS["serverHost"], 
		$GLOBALS["serverUsername"],  
		$GLOBALS["serverPassword"],  
		$GLOBALS["database"]
		
		);
		
		$stmt = $mysqli ->prepare("
		SELECT id, note, color
		FROM colornotes"
		);
		
		$stmt->bind_result($id, $note, $color);
		$stmt->execute();
		$result = array();
		
		// ts�kkel t��tab seni, kuni saab uue rea AB-i base_add_user
		//nii mitu korda palju SELECT
		while ($stmt -> fetch()) {
			//echo $note."<br>";
			
			$object = new StdClass();
			$object->id= $id;
			$object->note = $note;
			$object->noteColor = $color;
			
			
			array_push($result, $object);
		}
		return $result;
	}
	
	
	
	function login($email, $password) {
		
		$notice = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],  $GLOBALS["serverPassword"],  $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("
		
			SELECT id, email, password, created
			FROM Kasutajad_sample
			WHERE email = ?
		
		");
		// asendan ?
		$stmt->bind_param("s", $email);
		
		// m��ran muutujad reale mis k�tte saan
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		
		$stmt->execute();
		
		// ainult SLECTI'i puhul
		if ($stmt->fetch()) {
			
			// v�hemalt �ks rida tuli
			// kasutaja sisselogimise parool r�siks
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				// �nnestus 
				echo "Kasutaja ".$id." logis sisse";
				
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDatabase;
				
				header("Location: data.php");
				exit();
				
			} else {
				$notice = "Vale parool!";
			}
			
		} else {
			// ei leitud �htegi rida
			$notice = "Sellist emaili ei ole!";
		}
		
		return $notice;
	}
	
	function cleanInput ($input){
		//enne oon " tere tulemast "
		$input = trim($input);
		//p�rast selle koodi sisestamist "tere tulemast"
		$input = stripslashes($input); //paneb tagurpidi kaldkriisud �ieti
		//"<"
		$input = htmlspecialchars ($input);
		// "&lt;"
		
		return $input;
	}
	
	
	
	
	/*function sum($x, $y) {
		
		$answer = $x+$y;
		
		return $answer;
	}
	
	function hello($firstname, $lastname) {
		
		return 
		"Tere tulemast "
		.$firstname
		." "
		.$lastname
		."!";
		
	}
	
	echo sum(123123789523,1239862345);
	echo "<br>";
	echo sum(1,2);
	echo "<br>";
	
	$firstname = "Kristel";
	
	echo hello($firstname, "K.");
	*/
?>