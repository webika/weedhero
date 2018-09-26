<?php
class AdminIdentity extends CUserIdentity {

    public function authenticate() {
        $params = MMSettingsForm::getParams();
        $pass = crypt($this->password, $params['admin_salt']);
        $pass_hash = $params['admin_hash'];
        if ($this->username !== $params['admin_login'] || crypt($this->password, $params['admin_salt']) !== $params['admin_hash']) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->setState('role', 'admin');
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }
}