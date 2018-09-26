<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'user-form',
    'type'=>'vertical',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
      'validateOnSubmit' => true,
      'validateOnChange' => false,
    ),
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)) ?>
<legend>
    <div style="float:left"><?php if($iscreate == false) { ?> Update Category - <?php echo $model->name; } else {?>Create Category<?php } ?></div>
    <?php
                        Yii::app()->clientScript->registerScript(
                            'apply-save actions',
                            "jQuery('#apply_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','true');
                            });
                            jQuery('#save_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','false');
                            });");
                        ?>
    <div align="right" style="margin-right: 10px">
        <?php  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','icon'=>'ok white','type'=>'primary' ,'label'=>'Save Changes', 'htmlOptions'=>array('name'=>'wpmm-categories','id'=>'save_submit',))); ?>
        <?php  $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','icon'=>'ok white','type'=>'success' ,'label'=>'Apply Changes', 'htmlOptions'=>array('name'=>'wpmm-categories-apply','id'=>'apply_submit'))); ?>
    <?php $this->widget('bootstrap.widgets.TbButton',array('url'=>Yii::app()->createUrl('admin/categories'),'label' => 'Close','type' => 'danger','icon'=>'remove white')); ?>
    </div>
</legend>
        <?php if (!$iscreate) {
                 echo Chtml::hiddenField('ajax_validation', $model->id );
            } else {
                echo Chtml::hiddenField('ajax_validation', '0' );
                } ?>
        <?php echo Chtml::hiddenField('action', 'admin'); ?>
        <?php echo Chtml::hiddenField('model_name', 'MMCategory'); ?>
        <?php echo Chtml::hiddenField('apply', 'false',array('id'=>'form_apply_ckeck')); ?>
<div class="mm-container">
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
	'title' => 'General',
	'headerIcon' => 'icon-pencil',
	'htmlOptions' => array('class'=>'bootstrap-widget-table')
));?>
    <div class="inrfrm">
    <table border="0" cellpadding="5" width="100%"><tr>
            <td valign="top" align="left"><?php echo $form->textFieldRow($model, 'name'); ?></td>
            <?php
            if(!empty($model->image)){ ?><td valign="middle">
                <?php echo CHtml::activeHiddenField($model, 'image'); ?>
            <?php $this->widget('bootstrap.widgets.TbButton', array(
                    'label'=>'View Image',
                    'type'=>'success',
                    'icon'=>'picture white',
                    'id' => 'imadialog',
                    'htmlOptions'=>array(
                            'data-toggle'=>'modal',
                            'data-target'=>'#myModal',
                            'style' => 'margin-top:10px;',
                    ),
            )); ?>
                <?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'myModal')); ?>

            <div class="modal-header">
                <a class="close" data-dismiss="modal">x</a>
            <h4>Menu image</h4>
            </div>

                <div class="modal-body">
                    <div align="center">
                    <img style="height: 400px;" src="<?php echo Yii::app()->baseUrl . "/" .MM_UPLOADS_URL . "/".$model->image; ?>">
                    </div>
                </div>

                <div class="modal-footer">
                        <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'type'=>'primary',
                        'label'=>'OK',
                        'url'=>'',
                        'htmlOptions'=>array('data-dismiss'=>'modal'),
                )); ?>
                        <?php $this->widget('bootstrap.widgets.TbButton', array(
                        'label'=>'Delete',
                        'type'=>'danger',
                        'url'=>'',
                        'htmlOptions'=>array(
                            'onClick'=>"
                            jQuery.ajax({'type':'POST','success':function( data ) {
                                            jQuery('#MMCategory_image').val(''); jQuery('#myModal').modal('hide');jQuery('#imadialog').addClass('disabled');
                                            jQuery('#imadialog').bind('click', false);
                                                },'url':'".Yii::app()->createUrl('admin/delpicturecategory',array('id'=>$model->id))."',
                                                    'cache':false,
                                                    'data': {'".Yii::app()->request->csrfTokenName."':'".Yii::app()->request->csrfToken."'},
                                                    });
                                           ",
                            ),
                )); ?>
                </div>
        <?php $this->endWidget(); ?>
                </td>
    <?php }  ?>
        </tr>
        <tr>
           <td valign="top"><?php echo $form->textAreaRow($model, 'description', array('class'=>'span8', 'rows'=>8, 'style'=>"width:100%;")); ?></td>
           <td valign="top" width="210">
               <?php echo CHtml::label('Assosiate with Menus', 'Menu_menus');?>
               <div class="checkboxlist">
                    <?php echo Helpers::activeCheckBoxListMany($model,
                    'menus',CHtml::listData(MMMenu::model()->findAll(array('order'=>'id')), 'id', 'name'), array('attributeitem'=>'id')); ?>
               </div>
           </td>
        </tr>
    </table>
    <table border="0" cellpadding="5"><tr>
            <td valign="top">
                <?php  echo CHtml::beginForm();   ?>
                <?php echo $form->fileFieldRow($upload, 'file'); ?>
                <?php CHtml::endForm(); ?>
            </td>
            <td valign="top">
                <?php echo $form->radioButtonListInlineRow($model, 'published', array('1' => 'YES', '0'  => 'NO',)); ?>
            </td>
        </tr>
    </table>
        </div>
     <?php $this->endWidget();?>
<?php $this->endWidget(); ?>

</div>