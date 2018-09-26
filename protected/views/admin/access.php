<div class="mm-container">
<?php   $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'horizontalForm',
    'type' => 'vertical',
    'enableClientValidation' => true,
    'htmlOptions' => array('name' => 'MenuMakerSettigsForm', 'enctype' => 'multipart/form-data')
        )
); ?>
    <legend>
    <div style="float:left">Access</div>
    <div align="right" style="margin-right: 10px">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'ok white', 'type' => 'primary', 'label' => 'Save Changes', 'htmlOptions' => array('name' => 'wpmm_menus', 'id' => 'save_submit',))); ?>
    </div>
</legend>
<?php
    $this->widget('bootstrap.widgets.TbAlert', array(
    'block'=>true, // display a larger alert block?
    'fade'=>true, // use transitions?
    'closeText'=>'×', // close link text - if set to false, no close link is displayed
    'alerts'=>array( // configurations per alert type
	    'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
    ),
));
    ?>
<?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Access',
        'headerIcon' => 'icon-wrench',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'admin_login', array('prepend' => '<i class="icon-cog"></i>'));
        echo $form->textFieldRow($model, 'admin_email', array('prepend' => '<i class="icon-globe"></i>'));
        ?>
    </div>
    <div style="float: left; padding: 10px;">
        <?php
        echo $form->passwordFieldRow($model, 'admin_hash', array('prepend' => '<i class="icon-arrow-right"></i>'));
        echo $form->passwordFieldRow($model, 'admin_salt', array('prepend' => '<i class="icon-screenshot"></i>'));
        ?>
    </div>
    <div style="float: left; padding: 10px;">
    <?php $this->widget('bootstrap.widgets.TbButton', array('url' => '#', 'label' => 'Reset Password', 'icon' => 'user','id'=>'wpmm-admin-reset-pass')); ?>
    <?php $this->endWidget() ?>
    </div>
<?php $this->endWidget() ?>
</div>
<script>
jQuery('#wpmm-admin-reset-pass').on('click', function(){
    if(confirm('Reset password for <?php echo $model->admin_email ?>! Continue ?')){
        var details = jQuery('#wpmm-item-details');
        jQuery.ajax({
            url: '<?php echo Yii::app()->createUrl('admin/resetpasswordadmin') ?>',
            data: {
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            error: function(){
                alert('Your request failed!');
            },
            success: function(data) {
                if (data) {
                    alert('Password reset was succsesful !');
                } else {
                  alert('Your request failed!');
                }
            }
        });
            return false;
        } else {
           return false;
        }
    });
        </script>
