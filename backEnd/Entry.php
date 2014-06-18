<?php 
   require_once 'PDO_Config.php';
    
    /**
    * PDO that handles connection to the database and the SQL execution
    */
    class Entry {

        /**
        * The connection to the database
        */
        public $conn = null;

        /**
         * Connects to the database the moment we call this file
         */
        public function __construct(){
            try {
                $this->conn= new \PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
                $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                //echo 'connected';
                //$this->conn->set_limit_time(60);
            }
            catch (\PDOException $e) {
                die('Failed to Connect' . $e->getMessage());
            }
            return $this->conn;
        }

        /**
        * Closes the connection
        */
        public function closeConnection(){
            try{
                $this->conn = null;
            }catch(\PDOExceoption $e){
                die('Failed to close connection'. $e.getMessage());
            }
        }

        /******************************************************************************************
        *
        * ENTRY TYPE
        *
        *******************************************************************************************/

        /**
        * Adds a new entry 
        * @param  $year        INTEGER  Provided year of car
        * @param  $model       STRING   Provided model of car
        * @param  $odo         INTEGER  Provided odometer of car
        * @param  $maintenance STRING   Provided maintenance needed for car
        * @return $return_data BOOLEAN  Returns true if added successfully, false otherwise
        */
        public function addEntry($year =null, $model=null, $odo=null, $maintenance=null ){
            $return_data=false;

            $model = strtolower($model);
            $maintenance = strtolower($maintenance);

            if(!is_null($year) && !is_null($model) && !is_null($odo) && !is_null($maintenance)){
                if (is_numeric($odo) && is_numeric($year)){
                    
                    //Retrieve the make and type of car based on the model provided
                    $result = $this->getMakeAndType($model); 

                    $make=null;
                    $type=null;
                    if(sizeof($result)==1){
                        $make = $result[0]['make'];
                        $type = $result[0]['type'];                        
                    }


                    //Check if the type of car is eligible for this particular maintenance
                    if($this->checkMaintenanceEligible($type,$maintenance)){
                        try{
                            $stmt = $this->conn->prepare("INSERT INTO ENTRY(id,make,model,year,odo,type,maintenance)
                                VALUES(uuid(),:MAKE,:MODEL,:YEAR,:ODO,:TYPE,:MAIN)");

                            $stmt->bindValue(':MAKE',$make);
                            $stmt->bindValue(':MODEL',$model);
                            $stmt->bindValue(':YEAR',$year);
                            $stmt->bindValue(':ODO',$odo);
                            $stmt->bindValue(':TYPE',$type);
                            $stmt->bindValue(':MAIN',$maintenance);

                            $stmt->execute();                   
                            
                            $return_data = true;
                        }catch(PDOException $e){
                            echo $e;
                            
                        }                       
                    }else{
                        echo "This car is not eligible for this maintenance\n";
                    }
                }else{
                    echo "Both odometer and year needs to be integer\n";
                }
            }else{
                echo "Cannot add entry because one of the fields are empty.\n";
            }

            return $return_data;
        }


        /**
        * Update an existing entry 
        * @param  $id          STRING   The specific entry to update
        * @param  $year        INTEGER  Updated year of car
        * @param  $model       STRING   Updated model of car
        * @param  $odo         INTEGER  Updated odometer of car
        * @param  $maintenance STRING   Updated maintenance needed for car
        * @return $return_data BOOLEAN  Returns true if updated successfully, false otherwise
        */
        public function updateEntry($id=null,$year=null, $model=null,$odo=null,$maintenance=null){
            $return_data=false;

            $model = strtolower($model);
            $maintenance = strtolower($maintenance);

            if(!is_null($id) && !is_null($year) && !is_null($model)  && !is_null($odo) && !is_null($maintenance)){
                if (is_numeric($odo) && is_numeric($year)){
                    //Retrieve the make and type of car based on the model provided
                    $result = $this->getMakeAndType($model); 

                    $make=null;
                    $type=null;
                    if(sizeof($result)==1){
                        $make = $result[0]['make'];
                        $type = $result[0]['type'];                        
                    }

                    //Check if the type of car is eligible for this particular maintenance
                    if($this->checkMaintenanceEligible($type,$maintenance)){
                        try{
                            $stmt = $this->conn->prepare("UPDATE ENTRY SET make=:MAKE,model=:MODEL,year=:YEAR,odo=:ODO,type=:TYPE,maintenance=:MAIN
                                 WHERE id=:ID");

                            $stmt->bindValue(':MAKE',$make);
                            $stmt->bindValue(':MODEL',$model);
                            $stmt->bindValue(':YEAR',$year);
                            $stmt->bindValue(':ODO',$odo);
                            $stmt->bindValue(':TYPE',$type);
                            $stmt->bindValue(':MAIN',$maintenance);
                            $stmt->bindValue(':ID',$id);

                            $stmt->execute();                   
                            
                            $rows = $stmt->rowCount();

                            if($rows == 1){
                                $return_data = true;
                            }
                        }catch(PDOException $e){
                            echo $e;
                            
                        }
                    }else{
                        echo "This car is not eligible for this maintenance\n";
                    }
                }else{
                    echo "Both odometer and year needs to be integer\n";
                }   
            }else{
                echo "Cannot update entry because one of the fields are empty.\n";
            }
            return $return_data;
        }


       /**
        * Removing an entry
        * @param  $id          STRING   The specific entry to remove
        * @return $return_data BOOLEAN  Returns true if removed successfully, false otherwise
        */
        public function removeEntry($id=null){
            $return_data=false;

            $id = strtolower($id);

            if(!is_null($id)){
                try{
                    $stmt = $this->conn->prepare("DELETE FROM ENTRY WHERE id = :ID;");


                    $stmt->bindValue(':ID',$id);

                    $stmt->execute();                   
                    
                    $rows = $stmt->rowCount();

                    if($rows == 1){
                        $return_data = true;
                    }
                }catch(PDOException $e){
                    echo $e;  
                }
                
            }else{
                echo "Need to provide the entry ID in order to remove it\n";
            }
            return $return_data;
        }

        /**
        * Get all entries
        * @return $return_data  ARRAY  An array that contain all entries
        */
        public function listEntry(){
            $return_data=null;

            try{
                $stmt = $this->conn->prepare("SELECT E.id, E.year, E.make, E.model,C.type, E.odo,M.name FROM ENTRY E, CAR C, MAINTENANCE M
                    WHERE C.id=E.type AND E.maintenance=M.id ORDER BY E.make;");

                $stmt->execute();
                $return_data = $stmt->fetchAll();
            }catch(PDOException $e){
                echo $e;    
            }     
        
            return $return_data;
        }

        /**
        * Get the make and type of car based on the model of car
        * @param  $model       STRING Provided model of car
        * @return $return_data ARRAY  Returns an array containing the make and type of car 
        */
        public function getMakeAndType($model=null){

            $model = strtolower($model);

            $return_data=null;
            if(!is_null($model)){
                try{
                    $stmt = $this->conn->prepare("SELECT make,type FROM MAKE WHERE model=:MODEL;");

                    $stmt->bindValue(':MODEL',$model);
                    
                    $stmt->execute();

                    $return_data = $stmt->fetchAll();

                }catch(PDOException $e){
                    echo $e;    
                }
            }     
        
            return $return_data;
        }

        /**
        * Check if this type of car is eligible for the specific maintenance
        * @param  $type        STRING  Provided type ID of car
        * @param  $maintenance STRING  Provided maintenance ID needed for car
        * @return $return_data BOOLEAN Returns true if this specific type of car is eligible for the maintenance, false otherwise
        */        
        public function checkMaintenanceEligible($type=null,$maintenance=null){
            $return_data = false;

            $type = strtolower($type);
            $maintenance = strtolower($maintenance);

            if(!is_null($type) && !is_null($maintenance)){      
                try{
                    $stmt = $this->conn->prepare("SELECT * FROM ELIGIBLE E, MAINTENANCE M WHERE E.car_id=:TYPE and E.maintenance_id=M.id and M.id=:MAIN;");

                    $stmt->bindValue(':TYPE',$type);
                    $stmt->bindValue(':MAIN',$maintenance);

                    $stmt->execute();
                    
                    $rows = $stmt->rowCount();

                    if($rows == 1){
                        $return_data = true;
                    }
                }catch(PDOException $e){
                    echo $e;
                }
            }
            return $return_data;
        }

        /**
        * Get a specific entry based on ID
        * @param  $id          STRING  Provided entry ID
        * @return $return_data ARRAY   Returns the entry ID, year, make, model, type, odometer and maintenance of the entry ID
        */  
        public function getEntry($id=null){
            $return_data=null;

            $id = strtolower($id);

            if(!is_null($id)){
                try{
                    $stmt = $this->conn->prepare("SELECT E.id, E.year, E.make, E.model,C.type, E.odo,M.name as main FROM ENTRY E, CAR C, MAINTENANCE M
                        WHERE C.id=E.type AND E.maintenance=M.id AND E.id=:ID;");

                    $stmt->bindValue(':ID',$id);

                    $stmt->execute();
                    $return_data = $stmt->fetchAll();
                }catch(PDOException $e){
                    echo $e;    
                }     
            }
            return $return_data[0];  
        }

    }
?>
