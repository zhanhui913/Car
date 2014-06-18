<?php 
   require_once 'PDO_Config.php';
    
    /**
    * PDO that handles connection to the database and the SQL execution
    */
    class Maintenance {

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
        * MAINTENANCE TYPE
        *
        *******************************************************************************************/

        /**
        * Add a new maintenance
        * @param  $name        STRING  Provided name of maintenance
        * @return $return_data BOOLEAN Returns true if removed successfully, false otherwise
        */
        public function addMaintenance($name=null){
            $return_data = false;

            $name = strtolower($name);

            if(!is_null($name)){
                try{
                    $stmt = $this->conn->prepare("INSERT INTO MAINTENANCE(name,id) VALUES(:NAME,uuid());");

                    $stmt->bindValue(':NAME',$name);

                    $stmt->execute();                   
                            
                    $return_data = true;
                }catch(PDOException $e){
                    echo $e;
                }
            }
            return $return_data;
        }

        /**
        * Get maintenance name by id
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getMaintenanceName($id=null){
            $return_data=null;

            $id = strtolower($id);

            if(!is_null($id)){
                try{
                    $stmt = $this->conn->prepare("SELECT name FROM MAINTENANCE WHERE id=:ID;");

                    $stmt->bindValue(':ID',$id);
                    $stmt->execute();
                    $return_data = $stmt->fetchAll();

                    $rows = $stmt->rowCount();
                    if($rows==1){
                        $return_data = $return_data[0]['name'];
                    }
                }catch(PDOException $e){
                    echo $e;    
                }     
            }
            return $return_data;
        }        


        /**
        * Get maintenance id by name
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getMaintenanceId($name=null){
            $return_data=null;

            $name = strtolower($name);

            try{
                $stmt = $this->conn->prepare("SELECT id FROM MAINTENANCE WHERE name=:NAME;");

                $stmt->bindValue(':NAME',$name);
                $stmt->execute();
                $return_data = $stmt->fetchAll();
                
                $rows = $stmt->rowCount();
                if($rows == 1){
                    $return_data = $return_data[0]['id'];
                }
            }catch(PDOException $e){
                echo $e;    
            }     
        
            return $return_data;
        }  

        /**
        * Get all maintenance and its ID
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getAllMaintenanceList(){
            $return_data=null;

            try{
                $stmt = $this->conn->prepare("SELECT name, id FROM MAINTENANCE;");

                $stmt->execute();
                $return_data = $stmt->fetchAll();
            }catch(PDOException $e){
                echo $e;    
            }     
            return $return_data;
        }

        /**
        * Get all maintenance and its ID along with which type of car its available for 
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getAllMaintenanceListAndType(){
            $return_data=null;

            try{
                $stmt = $this->conn->prepare("SELECT C.type, M.name, M.id FROM MAINTENANCE M, ELIGIBLE E, CAR C WHERE E.car_id=C.id AND E.maintenance_id=M.id ORDER BY C.type;");

                $stmt->execute();
                $return_data = $stmt->fetchAll();
            }catch(PDOException $e){
                echo $e;    
            }     
            return $return_data;
        }


        /**
        * Remove the specific maintenance
        * @param  $id          STRING  Provided ID of maintenance
        * @return $return_data BOOLEAN Returns true if removed successfully, false otherwise
        */
        public function removeMaintenance($id=null){
            $return_data=false;

            $id = strtolower($id);

            if(!is_null($id)){
                try{
                    $stmt = $this->conn->prepare("DELETE FROM MAINTENANCE WHERE id = :ID;");

                    $stmt->bindValue(':ID',$id);

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
        * Update the maintenace
        * @param  $id          STRING  Provided ID of maintenance
        * @param  $name        STRING  Provided name of maintenance
        * @return $return_data BOOLEAN Returns true if updated successfully, false otherwise
        */
        public function updateMaintenance($id=null,$name=null){
            $return_data=false;

            $id = strtolower($id);
            $name = strtolower($name);

            if(!is_null($id) && !is_null($name)){
                try{
                    $stmt = $this->conn->prepare("UPDATE MAINTENANCE SET name=:NAME where id=:ID;");
                                 
                    $stmt->bindValue(':ID',$id);
                    $stmt->bindValue(':NAME',$name);

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

        /******************************************************************************************
        *
        * ELIGIBLE TYPE
        *
        *******************************************************************************************/

       /**
        * Add a new eligibility for the type of maintenance available for type of cars
        * @param  $carId          STRING  Provided car ID
        * @param  $maintenanceId  STRING  Provided maintenance ID
        * @return $return_data    BOOLEAN Returns true if inserted successfully, false otherwise
        */
        public function addEligibility($carId=null,$maintenanceId=null){
            $return_data = false;

            $carId = strtolower($carId);
            $maintenanceId = strtolower($maintenanceId);

            if(!is_null($carTypeId) && !is_null($maintenanceId)){
                try{
                    $stmt = $this->conn->prepare("INSERT INTO ELIGIBLE(car_id,maintenance_id) VALUES(:CAR,:MAIN);");

                    $stmt->bindValue(':CAR',$carId);
                    $stmt->bindValue(':MAIN',$maintenanceId);     

                    $stmt->execute();
                    $return_data = true;
                }catch(PDOException $e){
                    echo $e;
                }
            }
            return $return_data;
        }

        /**
        * Get all maintenance that the specific model is eligible for.
        * @param $model         STRING Provided model of the car
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getMaintenanceListByCarType($model=null){
            $return_data=null;

            $model = strtolower($model);

            if(!is_null($model)){
                try{
                    $stmt = $this->conn->prepare("SELECT M.name FROM MAINTENANCE M, MAKE ME, ELIGIBLE E WHERE ME.model=:MODEL AND ME.type=E.car_id and E.maintenance_id=M.id;");

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
        * Remove a specific eligibility
        * @param  $carId          STRING  Provided car ID
        * @param  $maintenanceId  STRING  Provided maintenance ID
        * @return $return_data    BOOLEAN Returns true if removed successfully, false otherwise
        */
        public function removeEligibility($carId=null,$maintenanceId=null){
            $return_data=false;

            $carId = strtolower($carId);
            $maintenanceId = strtolower($maintenanceId);

            if(!is_null($carId) && !is_null($maintenanceId)){
                try{
                    $stmt = $this->conn->prepare("DELETE FROM ELIGIBLE WHERE car_id = :CAR AND maintenance_id=:MAIN;");

                    $stmt->bindValue(':CAR',$carId);
                    $stmt->bindValue(':MAIN',$maintenanceId);

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
        * Update the eligibility
        * @param  $newCarId          STRING  Provided new car ID
        * @param  $newMaintenanceId  STRING  Provided new maintenance ID
        * @param  $oldCarId          STRING  Provided old car ID
        * @param  $oldMaintenanceId  STRING  Provided old maintenance ID
        * @return $return_data       BOOLEAN Returns true if updated successfully, false otherwise
        */
        public function updateEligibility($newCarId=null,$oldCarId=null,$newMainId=null,$oldMainId=null){
            $return_data=false;

            $newCarId = strtolower($newCarId);
            $oldCarId = strtolower($oldCarId);
            $newMainId = strtolower($newMainId);
            $oldMainId = strtolower($oldMainId);

            if(!is_null($newCarId) && !is_null($oldCarId) && !is_null($newMainId) && !is_null($oldMainId)){
                try{
                    $stmt = $this->conn->prepare("UPDATE ELIGIBLE SET car_id=:NEW_CAR, maintenance_id=:NEW_MAIN WHERE car_id=:OLD_CAR AND maintenance_id=:OLD_MAIN;");
                                 
                    $stmt->bindValue(':NEW_CAR',$newCarId);
                    $stmt->bindValue(':NEW_MAIN',$newMainId);
                    $stmt->bindValue(':OLD_CAR',$oldCarId);
                    $stmt->bindValue(':OLD_MAIN',$oldMainId);

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
    }
?>
