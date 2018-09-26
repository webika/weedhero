<?php
if (Yii::app()->user->role !== 'admin') {


    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login_form',
            ));
    $params = MMSettingsForm::getParams();
    if ($params['enable_logo']) {
        echo '<img src="'.Yii::app()->baseUrl . "/" .MM_UPLOADS_URL . "/".$params['logo_image'].'" alt="'.$params['vendor_name'].'" id="login-logo" />';
    } ?>

    <div class="line">
        <?php echo $form->label($adminForm, 'login'); ?>
        <?php echo $form->textField($adminForm, 'login'); ?>
        <?php echo $form->error($adminForm, 'login'); ?>
    </div>

    <div class="line">
        <?php echo $form->label($adminForm, 'pass'); ?>
        <?php echo $form->passwordField($adminForm, 'pass'); ?>
        <?php echo $form->error($adminForm, 'pass'); ?>
    </div>

    <div class="line">
        <?php echo CHtml::submitButton('Enter', array('id' => 'login_button', 'class' => 'btn')); ?>
    </div>

    <?php
    $this->endWidget();

}
?>