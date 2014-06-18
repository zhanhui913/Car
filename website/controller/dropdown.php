<?php
	require_once '../../backEnd/Car.php';
	require_once '../../backEnd/Maintenance.php';


	if($_GET["action"]=="findModel"){
		$make=$_GET['make'];
		$source=$_GET['source'];
		
		if($source=="addMain"){
			$dropDown = "dropdownAddModel";
		}else if($source=="editMain"){
			$dropDown = "dropdownEditModel";
		}

	    $car = new Car();

	    $result = $car->getAllCarModel($make);
	    $car->closeConnection();

		echo "<select name='model'  id='".$dropDown."' onchange='getMaintenance(this.value,\"".$source."\")'>";
		echo "<option>Select a Model</option>";
		for($i=0;$i<sizeof($result);$i++){
	 		echo "<option value=\"".$result[$i]['model']."\">".$result[$i]['model']."</option>";

		} 
		echo "</select>";

		$car->closeConnection();
		
	}else if($_GET["action"]=="findMaintenance"){
		$model=$_GET['model'];
		$source = $_GET['source'];
		
		if($source=="addMain"){
			$dropdown = "dropdownAddMain";
		}else if($source=="editMain"){
			$dropdown = "dropdownEditMain";
		}

	    $main = new Maintenance();

	    $result = $main->getMaintenanceListByCarType($model);
	    $main->closeConnection();

		echo "<select name='maintenance'  id='".$dropdown."' >";
		echo "<option>Select a Maintenance</option>";
		for($i=0;$i<sizeof($result);$i++){
	 		echo "<option value=\"".$result[$i]['name']."\">".$result[$i]['name']."</option>";
		} 
		echo "</select>";

		$main->closeConnection();
	}
?>