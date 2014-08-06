<?php

class Sensor
    extends GetterSetter
{
	private $_id;
    private $_impId;
    private $_timeInfo;
    private $_temperature;
    private $_humidity;
    private $_pressure;
    private $_altitude;
    private $_latitude;
    private $_longitude;
    private $_particles;

    /**
     * Constructs a new Sensor object.
     *
     * @param   int     	$id
     * @param	int			$impdId
     * @param	DateTime	$timeInfo
     * @param	decimal		$temperature
     * @param 	decimal		$humidity
     * @param 	decimal		$pressure
     * @param 	decimal		$altitude
     * @param 	decimal		$latitude
     * @param 	decimal		$longitude
     * @param 	decimal		$particles
     */
    public function __construct(
            $id, $impId, $timeInfo, $temperature, $humidity, $pressure, $altitude, $latitude, $longitude, $particles
        )
    {
        if (!is_numeric($id))
            throw new Exception("The \$id parameter must be an integer");
        $this->_id = (int) $id;
		$this->_impId = $impId;
        $this->_timeInfo = $timeInfo;
        $this->_temperature = $temperature;
        $this->_humidity = $humidity;
        $this->_pressure = $pressure;
        $this->_altitude = $altitude;
        $this->_latitude = $latitude;
        $this->_longitude = $longitude;
        $this->_particles = $particles;
    } // __construct
    
    protected function getId() {
        return $this->_id;
    } // getId

    protected function getImpId() {
        return $this->_impId;
    } // getImpId
    
    protected function setImpId($newImpId) {
        if (!is_numeric($newImpId))
            throw new Exception("The \$newImpId parameter must be an integer");
        $this->_impId = $newImpId;
    } // setImpId

    protected function getTimeInfo() {
        return $this->_timeInfo;
    } // getTimeInfo
    
    protected function setTimeInfo($newTimeInfo) {
        //isDateTime????
        if (empty($newTimeInfo))
            throw new Exception("TimeInfo cannot be empty");
        $this->_timeInfo = $newTimeInfo;
    } // setTimeInfo

	protected function getTemperature() {
        return $this->_temperature;
    } // getTemperature

	protected function setTemperature($newTemperature) {
		if (!is_numeric($newTemperature))
			throw new Exception("Temperature must be a decimal.");
		$this->_temperature = $newTemperature;
	} //setTemperature

	protected function getHumidity() {
        return $this->_humidity;
    } // getHumidity
    
	protected function setHumidity($newHumidity) {
		if (!is_numeric($newHumidity))
			throw new Exception("Humidity must be a decimal.");
		$this->_humidity = $newHumidity;
	} //setHumidity
	
	protected function getPressure() {
        return $this->_pressure;
    } // getPressure
    
	protected function setPressure($newPressure) {
		if (!is_numeric($newPressure))
			throw new Exception("Pressure must be a decimal.");
		$this->_pressure = $newPressure;
	} //setPressure
	
	protected function getAltitude() {
        return $this->_altitude;
    } // getAltitude
    
	protected function setAltitude($newAltitude) {
		if (!is_numeric($newAltitude))
			throw new Exception("Altitude must be a decimal.");
		$this->_altitude = $newAltitude;
	} //setAltitude
	
	protected function getLatitude() {
        return $this->_latitude;
    } // getLatitude
    
	protected function setLatitude($newLatitude) {
		if (!is_numeric($newLatitude))
			throw new Exception("Latitude must be a decimal.");
		$this->_latitude = $newLatitude;
	} //setLatitude
	
	protected function getLongitude() {
        return $this->_longitude;
    } // getLongitude
    
	protected function setLongitude($newLongitude) {
		if (!is_numeric($newLongitude))
			throw new Exception("Longitude must be a decimal.");
		$this->_longitude = $newLongitude;
	} //setLongitude
	
	protected function getParticles() {
        return $this->_particles;
    } // getParticles
    
	protected function setParticles($newParticles) {
		if (!is_numeric($newParticles))
			throw new Exception("Particles must be a decimal");
		$this->_particles = $newParticles;
	} //setParticles
	
    public function __toString() {
        return __CLASS__ ."(id=$this->id, impId=$this->impId, timeInfo=$this->timeInfo, 
        	temperature=$this->temperature, humidity=$this->humidity, pressure=$this->pressure, 
        	altitude=$this->altitude, latitude=$this->latitude, longitude=$this->longitude,
        	particles=$this->particles)";
    } // __toString
    
    public function toArray(){
    	$arr = Array();
    	$arr['id'] = $this->id;
    	$arr['impId'] = $this->impId;
    	$arr['timeInfo'] = $this->timeInfo;
    	$arr['temperature'] = $this->temperature;
    	$arr['humidity'] = $this->humidity;
    	$arr['pressure'] = $this->pressure;
    	$arr['altitude'] = $this->altitude;
    	$arr['latitude'] = $this->latitude;
    	$arr['longitude'] = $this->longitude;
    	$arr['particles'] = $this->particles;
    	
    	return $arr;
    }

} // class User
