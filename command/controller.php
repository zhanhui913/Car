<?php

	require_once '../backEnd/Car.php';
	require_once '../backEnd/Entry.php';
	require_once '../backEnd/Maintenance.php';

    // Stop the script giving time out errors.
    set_time_limit(0);

    // This opens standard in ready for interactive input.
    define('STDIN',fopen("php://stdin","r"));

    echo "Welcome to Zhan's Car Maintenance Tracker.\n";
    echo "To use this system, just type either '1', '2', '3', '4', or 'x' and then ENTER to select that option.\n";

    // Main event loop to capture top level command.
    while(!0){
        
        // Print out main menu.
        echo "\n------------------------------------------------\n";
        echo "Select an option.\n";
        echo "    1) Add an entry\n";
        echo "    2) List all entries\n";
        echo "    3) Update an entry\n";
		echo "    4) Remove an entry\n";
        echo "    x) Exit\n";
		echo "------------------------------------------------\n";

        // Decide what menu option to select based on input.
        switch(trim(fgets(STDIN,256))){
            case 1:
                addMenu();
                break;
                
            case 2:
            	listEntry();
                break;

            case 3:
            	updateEntry();
                break;

            case 4:
            	removeEntry();
                break;                

            case "x":
                exit();
                
            default: 
                break;
        }

    }



     function addMenu(){
     	$car = new Car();
    	$main = new Maintenance();


    	while(!0){
        // Print out sub ADD menu.
        echo "\n------------------------------------------------\n";
        echo "Select an option.\n";
        echo "    1) List all car's make, model and its type\n";
        echo "    2) List all maintenance \n";
        echo "    3) List all maintenance available for type of car\n";
		echo "    4) Add('Model','Year','Odometer','Maintenance')\n";
        echo "    x) Go back\n";
		echo "------------------------------------------------\n";

    		switch(trim(fgets(STDIN,256))){
    			case 1:
                    $result = $car->getAllCarMakeAndModelAndType();
                    echo "MAKE, MODEL, TYPE\n";
                    echo "-----------------\n";
                    for($i=0;$i<sizeof($result);$i++){
                    	echo $result[$i]['make'].", ".$result[$i]['model'].", ".$result[$i]['type']."\n";
                    }
                    break;
                    
                case 2:
                	$result = $main->getAllMaintenanceList();
                	echo "MAINTENANCE NAME, MAINTENANCE ID\n";
                	echo "--------------------------------\n";
                    for($i=0;$i<sizeof($result);$i++){
                    	echo $result[$i]['name'].", ".$result[$i]['id']."\n";
                    }
                    break;

                case 3:
                	$result = $main->getAllMaintenanceListAndType();
                	echo "CAR TYPE, MAINTENANCE NAME, MAINTENANCE ID\n";
                	echo "------------------------------------------\n";
                    for($i=0;$i<sizeof($result);$i++){
                    	echo $result[$i]['type'].", ".$result[$i]['name'].", ".$result[$i]['id']."\n";
                    }
                    break;

                case 4:
                	addCar();
                    break;                

                case "x":
                    $car->closeConnection();
                    $main->closeConnection();
                    return;
                    
                default: 
                    break;
    		}
    	}
    }

    function addCar(){
    	$entry = new Entry();
        $main = new Maintenance();
    	
    	echo "Input a Model : ";
		$model = trim(fgets(STDIN,256));
    	
    	echo "Input a Year : ";
		$year = trim(fgets(STDIN,256));
    	
    	echo "Input a Odometer : ";
		$odo = trim(fgets(STDIN,256));
    	
    	echo "Input the Maintenance Name : ";
		$maintenance = trim(fgets(STDIN,256));

        $mainId = $main->getMaintenanceId($maintenance);

		if($entry->addEntry($year,$model,$odo,$mainId33v)){
			echo "\nSUCCESSFULLY INSERTED\n";
		}else{
			echo "\nFAILED TO INSERT\n";
		}

        $main->closeConnection();
		$entry->closeConnection();
		return;
    }

    function listEntry(){
        $entry = new Entry();

        $result = $entry->listEntry();

        echo "ID, YEAR, MAKE, MODEL, TYPE, ODOMETER, MAINTENANCE\n";
        echo "--------------------------------------------------\n";
        for($i=0;$i<sizeof($result);$i++){
            echo $result[$i]['id'].", ".$result[$i]['year'].", ".$result[$i]['make'].", ".$result[$i]['model'].", ".$result[$i]['type'].", ".$result[$i]['odo'].", ".$result[$i]['name']."\n";
        }

        $entry->closeConnection();
        return;
    }

    function updateEntry(){
        $entry = new Entry();
        $main = new Maintenance();

        echo "Input the entry ID : ";
        $id = trim(fgets(STDIN,256));

        echo "Input the new model : ";
        $model = trim(fgets(STDIN,256));

        echo "Input the new year : ";
        $year = trim(fgets(STDIN,256));

        echo "Input the new odometer : ";
        $odo = trim(fgets(STDIN,256));

        echo "Input the new maintenance name : ";
        $maintenance = trim(fgets(STDIN,256));

        $mainId = $main->getMaintenanceId($maintenance);

        if($entry->updateEntry($id,$year,$model,$odo,$mainId)){
            echo "\nUPDATE SUCCESSFUL\n";
        }else{
            echo "\nUPDATE FAILED\n";
        }

        $main->closeConnection();
        $entry->closeConnection();
        return;
    }

    function removeEntry(){
        $entry = new Entry();

        echo "Input the entry ID you wish to remove : ";
        $id = trim(fgets(STDIN,256));

        if($entry->removeEntry($id)){
            echo "\nREMOVED SUCCESSFULLY\n";
        }else{
            echo "\nREMOVED FAILED\n";
        }
    }


    // Close standard in.
    fclose(STDIN);

?>