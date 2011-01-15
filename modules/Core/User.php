<?php
namespace Core;

class User extends Object
{
    private $_username;
    private $_password;

    function __construct( $username, $password )
    {
            $this->_username = $username;
            $this->_password = $password;
            parent::__construct();
    }

    public function getPassword() {
            return $this->_password;
    }

    public function getUsername() {
            return $this->_username;
    }

    protected function setPassword($password) {
            $this->_password = $password;
    }

    protected function setUsername($username) {
            $this->_username = $username;
    }
}