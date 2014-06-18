<?php
	require_once '../../backEnd/Entry.php';
	require_once '../../backEnd/Maintenance.php';	



	if($_POST["action"] == "add"){
		$model=$_POST['model'];
		$year=$_POST['year'];
		$odo=$_POST['odo'];
		$main=$_POST['maintenance'];

		addEntry($year,$model,$odo,$main);
	}else if($_POST["action"] == "list"){
		listEntry();
	}else if($_POST["action"] == "update"){
		$id = $_POST['id'];

		updateEntry($id);
	}else if($_POST["action"] == "updateAdd"){
		$id = $_POST['id'];
		$model=$_POST['model'];
		$year=$_POST['year'];
		$odo=$_POST['odo'];
		$main=$_POST['maintenance'];

		updateAddEntry($id,$year,$model,$odo,$main);
	}else if($_POST["action"] == "remove"){
		$id = $_POST['id'];

		removeEntry($id);
	}

	function addEntry($year=null,$model=null,$odo=null,$main=null){
		$maintenance = new Maintenance();
    	$entry = new Entry();

	    $mainId = $maintenance->getMaintenanceId($main);

	    if($entry->addEntry($year,$model,$odo,$mainId)){
	    	listEntry();
	    }

	    $entry->closeConnection();
	    $maintenance->closeConnection();
	}

	function listEntry(){
		$entry = new Entry();

		$result = $entry->listEntry();

		echo "<table border='1' id='entryTable'>
			<thead>
				<tr>
					<th>Year</th>
					<th>Make</th>
					<th>Model</th>
					<th>Type</th>
					<th>Odometer</th>						
					<th>Maintenance</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>";

		for($i=0;$i<sizeof($result);$i++){
			echo "<tr id='".$result[$i]['id']."'>";
			echo "<td class='year'>".$result[$i]['year']."</td>";
			echo "<td class='make'>".$result[$i]['make']."</td>";
			echo "<td class='model'>".$result[$i]['model']."</td>";
			echo "<td class='type'>".$result[$i]['type']."</td>";
			echo "<td class='odo'>".$result[$i]['odo']."</td>";
			echo "<td class='main'>".$result[$i]['name']."</td>";
			echo "<td class='edit'><button>E</button></td>";
			echo "<td class='delete'><button>X</button></td>";
			echo "</tr>";
		} 
		
		echo "</tbody></table>";

		$entry->closeConnection();
	}

	function updateEntry($id=null){
	    $entry = new Entry();

	    $result = $entry->getEntry($id);

    	echo json_encode($result);

    	$entry->closeConnection();
	}

	function updateAddEntry($id=null,$year=null,$model=null,$odo=null,$main=null){
		$maintenance = new Maintenance();
	    $entry = new Entry();

	    $mainId = $maintenance->getMaintenanceId($main);

	    if($entry->updateEntry($id,$year,$model,$odo,$mainId)){
	    	listEntry();
	    }

	    $entry->closeConnection();
	    $maintenance->closeConnection();
	}

	function removeEntry($id=null){
    	$entry = new Entry();

        if($entry->removeEntry($id)){
    		listEntry();
		}

    	$entry->closeConnection();
	}


?>
