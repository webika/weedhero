<?php
class AdminLogin extends CWidget {

    public function run() {

        $adminForm = new MMAdminForm;
        if (isset($_POST['MMAdminForm'])) {
            $adminForm->attributes = $_POST['MMAdminForm'];
            if ($adminForm->validate()) {
                if (Yii::app()->user->login($adminForm->identity, 24 * 60 * 60)) {
                    Yii::app()->controller->refresh();
                }
            }
        }
        $this->render('AdminLogin', array('adminForm' => $adminForm));
    }

}