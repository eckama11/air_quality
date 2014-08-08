<?php

class Reading
    extends GetterSetter
{
    private $_timeInfo;
    private $_reading;

    /**
     * Constructs a new Sensor object.
     *
     * @param	DateTime	$timeInfo
     * @param	decimal		$reading
     */
    public function __construct(
            $timeInfo, $reading
        )
    {
        $this->_timeInfo = $timeInfo;
        $this->_reading = $reading;
    } // __construct
    
    protected function getTimeInfo() {
        return $this->_timeInfo;
    } // getTimeInfo

    protected function getReading() {
        return $this->_reading;
    } // getReading
    
    protected function setTimeInfo($newTimeInfo) {
        //isDateTime????
        if (empty($newTimeInfo))
            throw new Exception("TimeInfo cannot be empty");
        $this->_timeInfo = $newTimeInfo;
    } // setTimeInfo

	protected function setReading($newReading) {
		if (!is_numeric($newReading))
			throw new Exception("Reading must be a decimal.");
		$this->_reading = $newReading;
	} //setReading

    public function __toString() {
        return __CLASS__ ."(timeInfo=$this->timeInfo, reading=$this->reading)";
    } // __toString
    
    public function objectToArray(){
    	return Array($this->timeInfo, $this->reading);
    }
    
    public function toArray(){
    	$arr = Array();
    	$arr['timeInfo'] = new DateTime($this->timeInfo);
    	$arr['reading'] = (float)$this->reading;
    	
    	return $arr;
    }

} // class Readings