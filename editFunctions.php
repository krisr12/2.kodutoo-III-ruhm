<?php
 
 	require_once("../../../kristel/config.php");
 	
 	$database = "if16_krisroos_3";
	
    //ei ole sisseloginud, suunan login lehele
	if(!isset ($_SESSION["userId"])) {
		header("Location: minu lehek�lg.php");
		exit();
	}
 	
 	function getSingleNoteData($edit_id){
     
 		//echo "id on ".$edit_id;
 		
 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
 		
 		$stmt = $mysqli->prepare("SELECT color, profession, location, money, note FROM colornotes WHERE id=?");
 
 		$stmt->bind_param("i", $edit_id);
 		$stmt->bind_result( $color, $profession, $location, $money, $note);
 		$stmt->execute();
 		
 		//tekitan objekti
 		$n = new Stdclass();
 		
 		//saime �he rea andmeid
 		if($stmt->fetch()){
 			// saan siin alles kasutada bind_result muutujaid
 			$n->note = $note;
 			$n->color = $color;
			$n->profession = $profession;
			$n->location = $location;
			$n->money = $money;
 			
 			
 		}else{
 			// ei saanud rida andmeid k�tte
 			// sellist id'd ei ole olemas
 			// see rida v�ib olla kustutatud
 			header("Location: data.php");
 			exit();
 		}
 		
 		$stmt->close();
 		$mysqli->close();
 		
 		return $n;
 		
 	}
 
 
 	function updateNote($id, $color, $profession, $location, $money, $note){
 		
 		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
 		
 		$stmt = $mysqli->prepare("UPDATE colornotes SET color=?, profession=?, location=?, money=?, note=? WHERE id=?");
 		$stmt->bind_param("isssis", $id, $color, $profession, $location, $money, $note);
 		
 		// kas �nnestus salvestada
 		if($stmt->execute()){
 			// �nnestus
 			echo "salvestus �nnestus!";
 		}
 		
 		$stmt->close();
 		$mysqli->close();
 		
 	}
 	
 	
 ?> 