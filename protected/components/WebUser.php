<?php
class WebUser extends CWebUser {

    function getRole() {
        if (MMSettingsForm::getParam('admin_login') === Yii::app()->user->name) {
            return 'admin';
        }
    }

}