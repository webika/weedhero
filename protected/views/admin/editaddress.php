<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'type' => 'vertical',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    ),
        ))
?>
<?php
$user = MMCustomer::model()->findByPk($model->customer_id);
if ($user == null) {
    throw new CHttpException(404, 'The requested page does not exist.');
    return false;
}
?>
<legend>
    <div style="float:left">Update Address - <?php echo $model->id; ?> for - <?php echo $user->c_mail; ?> </div>
    <?php
    Yii::app()->clientScript->registerScript(
            'apply-save actions', "jQuery('#apply_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','true');
                            });
                            jQuery('#save_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','false');
                            });");
    ?>
    <div align="right" style="margin-right: 10px">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'ok white', 'type' => 'primary', 'label' => 'Save Changes', 'htmlOptions' => array('name' => 'wpmm_menus', 'id' => 'save_submit',))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'ok white', 'type' => 'success', 'label' => 'Apply Changes', 'htmlOptions' => array('name' => 'wpmm_menus-apply', 'id' => 'apply_submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('url' => Yii::app()->createUrl('admin/viewcustomer',array('id'=>$model->customer_id)) , 'label' => 'Close', 'type' => 'danger', 'icon' => 'remove white')); ?>
    </div>
</legend>
<?php echo Chtml::hiddenField('ajax_validation', $model->id); ?>

<?php echo Chtml::hiddenField('action', 'admin'); ?>
<?php echo Chtml::hiddenField('model_name', 'MMAddress'); ?>
<?php echo Chtml::hiddenField('apply', 'false', array('id' => 'form_apply_ckeck')); ?>
<div class="mm-container">
    <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'General',
        'headerIcon' => 'icon-pencil',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div style="float:left; padding: 10px;">
        <?php echo $form->labelEx($model, 'address'); ?>
        <?php echo $form->textArea($model, 'address',array('style'=>'width:250px; height:82px;')); ?>
    </div>
    <div style="float:left; padding: 10px;">
        <?php echo $form->textFieldRow($model, 'city'); ?>
        <?php echo $form->textFieldRow($model, 'state'); ?>
    </div>
    <div style="float:left; padding: 10px;">
        <?php echo $form->textFieldRow($model, 'location'); ?>
    </div>
    <?php $this->endWidget() ?>
<div align="right" style="margin-right: 10px">
        <?php $this->widget('bootstrap.widgets.TbButton', array('url' => Yii::app()->createUrl('admin/deleteaddress',array('id'=>$model->id)), 'label' => 'Delete Address', 'type' => 'danger', 'icon' => 'remove white')); ?>
</div>
    <?php $this->endWidget() ?>
</div>