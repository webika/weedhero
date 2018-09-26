<?php
class MMAdminForm extends CFormModel {

    public $login;
    public $pass;
    public $identity;

    public function rules() {
        return array(
            array('login, pass', 'required', 'message' => 'Required field'),
            array('pass', 'check'),
        );
    }

    public function attributeLabels() {
        return array(
            'login' => 'Login',
            'pass' => 'Password',
        );
    }

    public function check() {
        $identity = new AdminIdentity($this->login, $this->pass);
        if ($identity->authenticate()) {
            $this->identity = $identity;
        } else {
            $this->addError('pass', 'Wrong password');
        }
    }

}