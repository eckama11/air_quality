<?php
require_once("GetterSetter.php");

class LoginSession
    extends GetterSetter
{

    private $_sessionId;
    private $_authenticatedUser;

    /**
     * Constructs a new LoginSession object.
     *
     * @param   string   $sessionId             The unique database session ID assigned to the LoginSession.
     * @param   User	 $authenticatedUser  	The user authenticated to this session.
     */
    public function __construct($sessionId, User $authenticatedUser) {
        $this->_sessionId = $sessionId;
         
        $this->_authenticatedUser = $authenticatedUser;
        
    } // __construct

    protected function getSessionId() {
        return $this->_sessionId;
    } // getId

    protected function getAuthenticatedUser() {
        return $this->_authenticatedUser;
    } // getAuthenticatedUser

    public function __toString() {
        return __CLASS__ ."(sessionId=$this->sessionId, authenticatedUser=$this->authenticatedUser)";
    } // __toString

} // class LoginSession