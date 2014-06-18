<?php 
   require_once 'PDO_Config.php';
    
    /**
    * PDO that handles connection to the database and the SQL execution
    */
    class Car {

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
        * CAR TYPE
        *
        *******************************************************************************************/

        /**
        * Add a new type of car (ie : gas, diesel, electric,etc)
        * @param  $type        STRING  Provided type of car
        * @return $return_data BOOLEAN Returns true if inserted successfully, false otherwise
        */
        public function addCarType($type=null){
            $return_data = false;
            
            $type = strtolower($type);

            if(!is_null($type)){
                try{
                    $stmt = $this->conn->prepare("INSERT INTO CAR(type,id) VALUES(:TYPE,uuid());");

                    $stmt->bindValue(':TYPE',$type);    
                    
                    $stmt->execute();

                    $return_data = true;
                }catch(PDOException $e){
                    echo $e;
                }
            }
            return $return_data;
        }

        /**
        * Get the Id of the specfic car type (ie: gas, diesel, electric, etc)
        * @param  $type        STRING  Provided type of car
        * @return $return_data STRING  Returns id of provided type of car
        */
        public function getCarTypeId($type=null){
            $return_data = null;

            $type = strtolower($type);

            if(!is_null($type)){
                try{
                    $stmt = $this->conn->prepare("SELECT id FROM CAR WHERE TYPE=:TYPE;");
                    
                    $stmt->bindValue(':TYPE',$type);

                    $stmt->execute();

                    $rows = $stmt->rowCount();

                    //Found a row that meets the WHERE CLAUSE
                    if($rows == 1){
                        $return_data = $stmt->fetchAll();
                        $return_data = $return_data[0]['id'];
                    }
                }catch(PDOException $e){
                    echo $e;
                }
            }
            return $return_data;
        }

        /**
        * Get the car type (ie: gas, diesel, electric, etc)
        * @param  $id          STRING  Provided id of type of car
        * @return $return_data STRING  Returns type of car
        */
        public function getCarType($id=null){
            $return_data = null;

            $id = strtolower($id);

            if(!is_null($id)){
                try{
                    $stmt = $this->conn->prepare("SELECT type FROM CAR WHERE ID=:ID;");
                    
                    $stmt->bindValue(':ID',$id);

                    $stmt->execute();

                    $rows = $stmt->rowCount();

                    //Found a row that meets the WHERE CLAUSE
                    if($rows == 1){
                        $return_data = $stmt->fetchAll();
                        $return_data = $return_data[0]['type'];
                    }
                }catch(PDOException $e){
                    echo $e;
                }
            }
            return $return_data;
        }


        /**
        * Get all car types
        * @return $return_data ARRAY  Returns an array containing all car types and its ID
        */
        public function getAllCarType(){
            $return_data = null;
            try{
                $stmt = $this->conn->prepare("SELECT * FROM CAR;");

                $stmt->execute();
                $return_data = $stmt->fetchAll();
            }catch(PDOException $e){
                echo $e;
            }
            return $return_data;
        }

        /**
        * Remove the specific car type
        * @param  $id          STRING  Provided ID of type of car
        * @return $return_data BOOLEAN Returns true if removed successfully, false otherwise
        */
        public function removeCarType($id=null){
            $return_data=false;

            $id = strtolower($id);

            if(!is_null($id)){
                try{
                    $stmt = $this->conn->prepare("DELETE FROM CAR WHERE id = :ID;");

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
        * Update the car type
        * @param  $id          STRING  Provided ID of type of car
        * @param  $type        STRING  Provided type of car
        * @return $return_data BOOLEAN Returns true if updated successfully, false otherwise
        */
        public function updateCarType($id=null,$type=null){
            $return_data=false;

            $type = strtolower($type);
            $id = strtolower($id);

            if(!is_null($id) && !is_null($type)){
                try{
                    $stmt = $this->conn->prepare("UPDATE CAR SET type=:TYPE where id=:ID;");
                                 
                    $stmt->bindValue(':ID',$id);
                    $stmt->bindValue(':TYPE',$type);

                    $stmt->execute();                   
                    
                    $return_data = true;
                }catch(PDOException $e){
                    echo $e;  
                }
            }
            return $return_data;
        }

        /******************************************************************************************
        *
        * MAKE TYPE
        *
        *******************************************************************************************/
        
        /**
        * Add a new Car Make and Model
        * @param  $make        STRING   Provided make of car
        * @param  $model       STRING   Provided model of car
        * @param  $odo         STRING   Provided type of car (ie : gas, diesel, eletric, etc)
        * @return $return_data BOOLEAN  Returns true if added successfully, false otherwise
        */
        public function addCar($make=null,$model=null,$type=null){
            $return_data = false;

            $make = strtolower($make);
            $model = strtolower($model);
            $type = strtolower($type);

            //Check if the type of car provided is valid according to DB
            $typeId = getCarTypeId($type);

            if(!is_null($typeId) && !is_null($make) && !is_null($model)){
                try{
                    $stmt = $this->conn->prepare("INSERT INTO MAKE(make,model,type) VALUES(:MAKE,:MODEL,:TYPE);");
                    $stmt->bindValue(':MAKE',$make);
                    $stmt->bindValue(':MODEL',$model);
                    $stmt->bindValue(':TYPE',$typeId);

                    $stmt->execute();

                    $return_data = true;
                }catch(PDOException $e){
                    echo $e;
                }               
            }
            return $return_data;
        }

        /**
        * Get the car type of the specific model
        * @param  $model       STRING   Provided model of car
        * @return $return_data STRING   Returns the type of car for this model (ie: gas, diesel, electric)
        */
        public function getCarTypeByModel($model=null){
            $return_data = null;

            $model = strtolower($model);

            if(!is_null($model)){
                try{
                    $stmt = $this->conn->prepare("SELECT C.type FROM MAKE M, CAR C WHERE M.type=C.id AND M.model=:MODEL;");

                    $stmt->bindValue(':MODEL',$model);

                    $stmt->execute();

                   $rows = $stmt->rowCount();

                    //Found a row that meets the WHERE CLAUSE
                    if($rows == 1){
                        $return_data = $stmt->fetchAll();
                        $return_data = $return_data[0]['type'];
                    }
                }catch(PDOException $e){
                    echo $e;
                }
            }
            return $return_data;
        }

        /**
        * Get all car's make, model and its type
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getAllCarMakeAndModelAndType(){
            $return_data=null;
            try{
                $stmt = $this->conn->prepare("SELECT M.make, M.model, C.type FROM MAKE M, CAR C WHERE M.type=C.id;");

                $stmt->execute();
                $return_data = $stmt->fetchAll();
            }catch(PDOException $e){
                echo $e;    
            }     
        
            return $return_data;
        }

        /**
        * Get all make
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getAllCarMake(){
            $return_data=null;

            try{
                $stmt = $this->conn->prepare("SELECT DISTINCT make FROM MAKE;");

                $stmt->execute();
                $return_data = $stmt->fetchAll();
            }catch(PDOException $e){
                echo $e;    
            }     
        
            return $return_data;
        }
    
        /**
        * Get all models for a specific make
        * @return $return_data  ARRAY  An array of an array that contains the results
        */
        public function getAllCarModel($make=null){
            $return_data=null;

            $make = strtolower($make);

            if(!is_null($make)){
                try{
                    $stmt = $this->conn->prepare("SELECT model FROM MAKE WHERE make=:MAKE;");

                    $stmt->bindValue(':MAKE',$make);

                    $stmt->execute();
                    $return_data = $stmt->fetchAll();
                }catch(PDOException $e){
                    echo $e;    
                }     
            }
            return $return_data;
        }

        /**
        * Remove the specific car make
        * @param  $make        STRING  Provided make of car
        * @return $return_data BOOLEAN Returns true if removed successfully, false otherwise
        */
        public function removeCarMake($make=null){
            $return_data = false;

            $make = strtolower($make);

            if(!is_null($make)){
                try{
                    $stmt = $this->conn->prepare("DELETE FROM MAKE WHERE make = :MAKE;");

                    $stmt->bindValue(':MAKE',$make);

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
        * Remove the specific car model
        * @param  $model       STRING  Provided mode of car
        * @return $return_data BOOLEAN Returns true if removed successfully, false otherwise
        */
        public function removeCarModel($model=null){
            $return_data = false;

            $model = strtolower($model);

            if(!is_null($model)){
                try{
                    $stmt = $this->conn->prepare("DELETE FROM MAKE WHERE model = :MODEL;");

                    $stmt->bindValue(':MODEL',$model);

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
        * Update the car make
        * @param  $oldMake      STRING  Provided old make of car
        * @param  $newMake      STRING  Provided new make of car
        * @return $return_data  BOOLEAN Returns true if updated successfully, false otherwise
        */
        public function updateCarMake($oldMake=null,$newMake=null){
            $return_data = false;

            $oldMake = strtolower($oldMake);
            $newMake = strtolower($newMake);

            if(!is_null($oldMake) && !is_null($newMake) ){
                try{
                    $stmt = $this->conn->prepare("UPDATE MAKE SET make=:NEW_MAKE where make=:OLD_MAKE;");

                    $stmt->bindValue(':OLD_MAKE',$oldMake);
                    $stmt->bindValue(':NEW_MAKE',$newMake);

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
        * Update the car model
        * @param  $oldModel      STRING  Provided old model of car
        * @param  $newModel      STRING  Provided new model of car
        * @return $return_data   BOOLEAN Returns true if updated successfully, false otherwise
        */
        public function updateCarModel($oldModel=null,$newModel=null){
            $return_data = false;

            $oldModel = strtolower($oldModel);
            $newModel = strtolower($newModel);


            if(!is_null($oldModel) && !is_null($newModel) ){
                try{
                    $stmt = $this->conn->prepare("UPDATE MAKE SET model=:NEW_MODEL where model=:OLD_MODEL;");

                    $stmt->bindValue(':OLD_MODEL',$oldModel);
                    $stmt->bindValue(':NEW_MODEL',$newModel);

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
