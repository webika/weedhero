<?php
class UserIdentity extends CUserIdentity {

    private $_id;

    public function authenticate() {
        $username = strtolower($this->username);
        $user = MMCustomer::model()->find('LOWER(c_mail)=?', array($username));
        if ($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$user->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $user->id;
            $this->username = $user->c_mail;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->_id;
    }

}