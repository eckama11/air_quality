<?php

class DBInterface {
    private $dbh;

    /**
     * Constructs a new DBInterface instance with the specified connection parameters.
     * @param   String $dbServer    The name/IP of the MySQL server instance to connect to.
     * @param   String $dbName      The name of the initial database for the connection.
     * @param   String $dbUsername  The username to use for authentication.
     * @param   String $dbPassword  The password to use for authentication.
     */
    public function __construct( $dbServer, $dbName, $dbUsername, $dbPassword ) {
        $dsn = "mysql:host=$dbServer;dbname=$dbName";
        $this->dbh = new PDO($dsn, $dbUsername, $dbPassword);
    } // __construct

    public function formatErrorMessage($stmt, $message) {
        list($sqlState, $driverErrorCode, $driverErrorMessage) = $stmt->errorInfo();
        return $message .": [$sqlState] $driverErrorCode: $driverErrorMessage";
    } // formatSqlErrorMessage($pdoErrorInfo)

    /**
     * Reads a LoginSession object from the database.
     * @param   int $sessionId  The session ID of the LoginSession record to retrieve.
     * @return  LoginSession    The LoginSession instance for the specified session ID, if one exists.
     */
    public function readLoginSession( $sessionId ) {
        static $stmt;
        if ($stmt == null)
            $stmt = $this->dbh->prepare(
                    "SELECT sessionId, authenticatedUser ".
                        "FROM loginSession ".
                        "WHERE sessionId=?"
                );

        $stmt->execute(Array($sessionId));
        $res = $stmt->fetchObject();
        if ($res === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to retrieve specified session from database"));

        return new LoginSession($res->sessionId, $this->readUser($res->authenticatedUser));
    } // readLoginSession

    /**
     * Updates a LoginSession object in the database.
     * @param   LoginSession $session  The session to update.
     * @return  LoginSession    The LoginSession which was passed in.
     */
    public function writeLoginSession( LoginSession $session ) {
        static $stmt;
        if ($stmt == null)
            $stmt = $this->dbh->prepare(
                    "UPDATE loginSession ".
                        "SET authenticatedUser=:authenticatedUser ".
                        "WHERE sessionID=:sessionId"
                );

        $success = $stmt->execute(Array(
            ':sessionId' => $session->sessionId,
            ':authenticatedUser' => $session->authenticatedUser
        ));

        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to update specified session in database"));

        return $session;
    } // writeLoginSession

   /**
     * Authenticates an User and creates a LoginSession.
     *
     * @param   String  $username   The username of the User to authenticate.
     * @param   String  $password   The password to use for authentication.
     *
     * @return  LoginSession    A new LoginSession instance for the authenticated User.
     */
    public function createLoginSession( $username, $password ) {
        // Authenticate the User based on username/password
        static $loginStmt;
        static $insertStmt;
        if (is_null($loginStmt)) {
        	$sth = $this->dbh->prepare(
        		"SELECT password ".
				"FROM user ".
				"WHERE username=:username ".
				);

			$sth->bindParam(':username', $username);

			$sth->execute();

			$user = $sth->fetch(PDO::FETCH_OBJ);

			// Hashing the password with its hash as the salt returns the same hash
			if ( crypt($password, $user->hash) === $user->hash ) {
				$loginStmt = $this->dbh->prepare(
                  "SELECT id ".
                    "FROM user ".
                    "WHERE username=:username ".
                        "AND password=:password "
                );
			}

            $insertStmt = $this->dbh->prepare(
                    "INSERT INTO loginSession ( ".
                            "sessionId, authenticatedUser ".
                        ") VALUES ( ".
                            ":sessionId, :authenticatedUser ".
                        ")"
                );
        }


        $success = $loginStmt->execute(Array(
                ':username' => $username,
                ':password' => $password
            ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($loginStmt, "Unable to query database to authenticate User"));

        $row = $loginStmt->fetchObject();
        if (!$row)
            throw new Exception("Unable to authenticate User, incorrect username or password");

        $authenticatedUser = $row->id;

        // Generate a new session ID
        // This may be somewhat predictable, but should be strong enough for purposes of the demo
        $sessionId = md5(uniqid(microtime()) . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
/*----------*/
        $rv = new LoginSession( $sessionId, $this->readUser($authenticatedUser) );
                //if(true)
                //      throw new Exception($this->formatErrorMessage($loginStmt, "Here"));
        // Create the loginSession record
        $success = $insertStmt->execute(Array(
                ':sessionId' => $sessionId,
                ':authenticatedUser' => $authenticatedUser
            ));
        if (!$success)
            throw new Exception($this->formatErrorMessage($insertStmt, "Unable to create session record in database"));

        return $rv;
    } // createLoginSession

    /**
     * Removes a login session from the database.
     * @param   LoginSession    $session    The session to destroy.
     */
    public function destroyLoginSession( LoginSession $session ) {
        static $stmt;
        if ($stmt == null)
            $stmt = $this->dbh->prepare(
                    "DELETE FROM loginSession ".
                        "WHERE sessionId = ?"
                );

        $success = $stmt->execute(Array( $session->sessionId ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to destroy session record"));
    } // destroyLoginSession


    /**
     * Tests whether a specific username is in currently assigned to an User or not.
     *
     * @param   String  $username   The username to test for.
     *
     * @return  Boolean    True if the username is assigned to an existing User, false if not.
     */
    public function isUsernameInUse( $username ) {
        static $stmt;
        if ($stmt == null) {
            $stmt = $this->dbh->prepare(
                  "SELECT id ".
                    "FROM user ".
                    "WHERE username=:username"
                );
        }

        $success = $stmt->execute(Array(
                ':username' => $username
            ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for username"));

        $row = $stmt->fetchObject();
        return ($row !== false);
    } // isUsernameInUse

    /**
     * Reads a User from the database.
     * @param   int $id The ID of the User to retrieve.
     * @return  User    An instance of User.
     */
    public function readUser( $id ) {
        if (!is_numeric($id))
            throw new Exception("Parameter \$id must be an integer");
        $id = (int) $id;

        static $stmt;
        if (is_null($stmt)){
            $sql =  "SELECT id, username, password, email, deviceId FROM user WHERE id = ?";
            $stmt = $this->dbh->prepare($sql);
        }

        $success = $stmt->execute(Array( $id ));
        if (!$success)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for User record"));

        $row = $stmt->fetchObject();

        if ($row === false)
            throw new Exception("No such User: $id");
        if (is_null($row->deviceId))
            throw new Exception("No device being passed.");
        return new User(
                $row->id,
                $row->username,
                $row->password,
                $row->email,
                $row->device
            );
            if(true)
                throw new Exception($this->formatErrorMessage($loginStmt, "Here"));
    } // readUser

    /**
     * Reads a list of all Users from the database.
     * @param   Boolean $activeFlag Whether to retrieve active Users (true) or inactive Users (false)
     * @return  Array[User] Array of User instances.
     */
    public function readUsers() {
        static $stmt;
        if ($stmt == null)
            $stmt = $this->dbh->prepare(
                    "SELECT id, username, password, email, deviceId ".
                        "FROM user ".
                        "ORDER BY username"
                );

        $success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for User records"));

        $rv = Array();
        while ($row = $stmt->fetchObject()) {
            $rv[] = new User(
                    $row->id,
                    $row->username,
                    $row->password,
                    $row->email,
                    $row->device
                );
        } // while

        return $rv;
    } // readUsers

        /**
     * Updates an User to the database.
     * @param   User    $user   The User to write.  If the id property is 0, a new
     *                                  record will be created, otherwise an existing record matching
     *                                  the id will be updated.
     * @return  User    A new User instance (with the new id if a new record was created).
     */
     public function updateUser( User $user) {
        static $stmtUpdate;
        if (is_null($stmtUpdate) || empty($stmtUpdate)) {
                $sql = 'UPDATE user SET username = :username, password=:password, email=:email, deviceId=:device WHERE id=:id';
                $stmtUpdate = $this->dbh->prepare($sql);

            if (!$stmtUpdate)
                throw new Exception($this->formatErrorMessage($stmtUpdate, "Unable to prepare user update"));
                }

                $params = Array(
                        ':username' => $user->username,
                ':password' => $user->password,
                ':email' => $user->email,
                ':device' => $user->device,
                ':id' => $user->id
            );

        $success = $stmtUpdate->execute($params);

        if (!$success)
            throw new Exception($this->formatErrorMessage($stmtUpdate, "Unable to store user record in database"));

        return new User(
                $user->id,
                $user->username,
                $user->password,
                $user->email,
                $user->device
            );
     }

    /**
     * Writes an User to the database.
     * @param   User    $user   The User to write.  If the id property is 0, a new
     *                                  record will be created, otherwise an existing record matching
     *                                  the id will be updated.
     * @return  User    A new User instance (with the new id if a new record was created).
     */
    public function writeUser( User $user ) {
        static $stmtInsert;
        static $stmtUpdate;
        if ($stmtInsert == null) {
            $stmtInsert = $this->dbh->prepare(
                    "INSERT INTO user ( ".
                            "username, password, email, deviceId".
                        ") VALUES ( ".
                            ":username, :password, :email, :device".
                        ")"
                );

            if (!$stmtInsert)
                throw new Exception($this->formatErrorMessage(null, "Unable to prepare user insert"));
                }

        $params = Array(
                        ':username' => $user->username,
                ':password' => $user->password,
                ':email' => $user->email,
                ':device' => $user->device
            );

        if ($user->id == 0) {
            $stmt = $stmtInsert;
        } else {
            $params[':id'] = $user->id;
            $stmt = $stmtUpdate;
        }

        $success = $stmt->execute($params);

        if ($success == false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to store user record in database"));

        if ($user->id == 0)
            $newId = $this->dbh->lastInsertId();
        else
            $newId = $user->id;

        return new User(
                $newId,
                $user->username,
                $user->password,
                $user->email,
                $user->device
            );
    } // writeUser

        /**
     * Writes a Sensor to the database.
     * @param   Sensor    $sensor   The sensor to write.  If the id property is 0, a new
     *                                  record will be created, otherwise an existing record matching
     *                                  the id will be updated.
     * @return  Sensor    A new Sensor instance (with the new id if a new record was created).
     */
    public function writeSensor( Sensor $sensor ) {
        static $stmtInsert;
        if ($stmtInsert == null) {
            $stmtInsert = $this->dbh->prepare(
                    "INSERT INTO sensors ( ".
                            "impId,timeInfo,temperature,humidity,pressure,altitude,latitude,longitude,particles".
                        ") VALUES ( ".
                            ":impId, :timeInfo, :temperature, :humidity, :pressure, :altitude, :latitude, :longitude, :particles".
                        ")"
                );

            if (!$stmtInsert)
                throw new Exception($this->formatErrorMessage(null, "Unable to prepare sensor insert"));

        }

        $params = Array(
                        ':impId' => $sensor->impId,
                ':timeInfo' => $sensor->timeInfo,
                ':temperature' => $sensor->temperature,
                ':humidity' => $sensor->humidity,
                ':pressure' => $sensor->pressure,
                ':altitude' => $sensor->altitude,
                ':latitude' => $sensor->latitude,
                ':longitude' => $sensor->longitude,
                ':particles' => $sensor->particles
            );

        $stmt = $stmtInsert;

        $success = $stmt->execute($params);

        if ($success == false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to store reading record in database"));

        if ($sensor->id == 0)
            $newId = $this->dbh->lastInsertId();
        else
            $newId = $sensor->id;

        return new Sensor(
                $newId,
                $sensor->impId,
                $sensor->timeInfo,
                $sensor->temperature,
                $sensor->humidity,
                $sensor->pressure,
                $sensor->altitude,
                $sensor->latitude,
                $sensor->longitude,
                $sensor->particles
            );
    } // writeSensor

	/**
     * Reads a list of all Sensor Readings from the database.
     * @return  Array[Sensor] Array of Sensor instances.
     */
     // THIS WILL NEED TO TAKE IN A SENSOR or USER TO OUTPUT JUST ONE SENSOR'S DATA or USER'S DATA
     public function readSensors() {
        static $stmt;
        if ($stmt == null)
        	$sql = "SELECT id, impId, DATE_FORMAT(timeInfo,'%H:%i:%s') as 'timeInfo', ".
        		"temperature, humidity, pressure, altitude, latitude, longitude, particles FROM sensors WHERE DATE(timeInfo) = DATE(NOW())";
            $stmt = $this->dbh->prepare($sql);
             
		$success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for User records"));

        $rv = Array();
        while ($row = $stmt->fetchObject()) {
            $rv[] = new Sensor(
            		$row->id,
					$row->impId,
					$row->timeInfo,
					$row->temperature,
					$row->humidity,
					$row->pressure,
					$row->altitude,
					$row->latitude,
					$row->longitude,
					$row->particles
                );
        } // while

        return $rv;
    } // readSensors
    
    /**
     * Reads a list of all Sensor Readings from the database.
     * @return  Array[Times, Temp] Array of times and temperatures read from today.
     */
     // THIS WILL NEED TO TAKE IN A SENSOR or USER TO OUTPUT JUST ONE SENSOR'S DATA or USER'S DATA
     // WILL NEED TO TAKE IN DATE TO SHOW now it shows current date
    public function readTemp() {
        static $stmt;
        if ($stmt == null)
        	$sql = "SELECT DATE_FORMAT(timeInfo,'%r') as 'timeInfo', temperature FROM sensors WHERE DATE(timeInfo) = DATE(NOW())";
            $stmt = $this->dbh->prepare($sql);
             
		$success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for temperature Reading records"));

        $rv = Array();
        $rv[] = array('Time', 'Temperature');
        while ($row = $stmt->fetchObject()) {
            $rv[]  = array(
                        $row->timeInfo,
                        (double)$row->temperature
                    );
        } // while

        return $rv;
    } // readTemp
    
    /**
     * Reads a list of all Sensor Readings from the database.
     * @return  Array[Times, Humidity] Array of times and temperatures read from today.
     */
     // THIS WILL NEED TO TAKE IN A SENSOR or USER TO OUTPUT JUST ONE SENSOR'S DATA or USER'S DATA
     // WILL NEED TO TAKE IN DATE TO SHOW now it shows current date
    public function readHumidity() {
        static $stmt;
        if ($stmt == null)
        	$sql = "SELECT DATE_FORMAT(timeInfo,'%H:%i:%s') as 'timeInfo', humidity FROM sensors WHERE DATE(timeInfo) = DATE(NOW())";
            $stmt = $this->dbh->prepare($sql);
             
		$success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for humidity Reading records"));

        $rv = Array();
        while ($row = $stmt->fetchObject()) {
            $rv[] = new Reading(
            		$row->timeInfo,
					$row->humidity
                );
        } // while

        return $rv;
    } // readHumidity
    
    /**
     * Reads a list of all Sensor Readings from the database.
     * @return  Array[Times, Pressure] Array of times and temperatures read from today.
     */
     // THIS WILL NEED TO TAKE IN A SENSOR or USER TO OUTPUT JUST ONE SENSOR'S DATA or USER'S DATA
     // WILL NEED TO TAKE IN DATE TO SHOW now it shows current date
    public function readPressure() {
        static $stmt;
        if ($stmt == null)
        	$sql = "SELECT DATE_FORMAT(timeInfo,'%H:%i:%s') as 'timeInfo', pressure FROM sensors WHERE DATE(timeInfo) = DATE(NOW())";
            $stmt = $this->dbh->prepare($sql);
             
		$success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for pressure Reading records"));

        $rv = Array();
        while ($row = $stmt->fetchObject()) {
            $rv[] = new Reading(
            		$row->timeInfo,
					$row->pressure
                );
        } // while

        return $rv;
    } // readPressure
    
    /**
     * Reads a list of all Sensor Readings from the database.
     * @return  Array[Times, Altitude] Array of times and temperatures read from today.
     */
     // THIS WILL NEED TO TAKE IN A SENSOR or USER TO OUTPUT JUST ONE SENSOR'S DATA or USER'S DATA
     // WILL NEED TO TAKE IN DATE TO SHOW now it shows current date
     public function readAltitude() {
        static $stmt;
        if ($stmt == null)
        	$sql = "SELECT DATE_FORMAT(timeInfo,'%H:%i:%s') as 'timeInfo', altitude FROM sensors WHERE DATE(timeInfo) = DATE(NOW())";
            $stmt = $this->dbh->prepare($sql);
             
		$success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for Altitude Reading records"));

        $rv = Array();
        while ($row = $stmt->fetchObject()) {
            $rv[] = new Reading(
            		$row->timeInfo,
					$row->altitude
                );
        } // while

        return $rv;
    } // readAltitude
    
    /**
     * Reads a list of all Sensor Readings from the database.
     * @return  Array[Times, Particles] Array of times and temperatures read from today.
     */
     // THIS WILL NEED TO TAKE IN A SENSOR or USER TO OUTPUT JUST ONE SENSOR'S DATA or USER'S DATA
     // WILL NEED TO TAKE IN DATE TO SHOW now it shows current date
     public function readParticles() {
        static $stmt;
        if ($stmt == null)
        	$sql = "SELECT DATE_FORMAT(timeInfo,'%H:%i:%s') as 'timeInfo', particles FROM sensors WHERE DATE(timeInfo) = DATE(NOW())";
            $stmt = $this->dbh->prepare($sql);
             
		$success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for particle Reading records"));

        $rv = Array();
        while ($row = $stmt->fetchObject()) {
            $rv[] = new Reading(
            		$row->timeInfo,
					$row->particles
                );
        } // while

        return $rv;
    } // readAltitude
    
    /**
     * Reads a list of all Sensor Readings from the database.
     * @return  Array[Times, Latitude, Longitude] Array of times and temperatures read from today.
     //THIS WILL NEED A DIFFERENT CLASS THAN READING SINCE IT HAS 2 OUTPUTS
     // THIS WILL NEED TO TAKE IN A SENSOR or USER TO OUTPUT JUST ONE SENSOR'S DATA or USER'S DATA
     // WILL NEED TO TAKE IN DATE TO SHOW now it shows current date
    public function readLatitudeLongitude() {
        static $stmt;
        if ($stmt == null)
        	$sql = "SELECT DATE_FORMAT(timeInfo,'%H:%i:%s') as 'timeInfo', latitude, longitude FROM sensors WHERE DATE(timeInfo) = DATE(NOW())";
            $stmt = $this->dbh->prepare($sql);
             
		$success = $stmt->execute(Array( ));
        if ($success === false)
            throw new Exception($this->formatErrorMessage($stmt, "Unable to query database for Reading records"));

        $rv = Array();
        while ($row = $stmt->fetchObject()) {
            $rv[] = new Reading(
            		$row->timeInfo,
					$row->reading
                );
        } // while

        return $rv;
    } // readLatitudeLongitude
    */
    
    
} // DBInterface
