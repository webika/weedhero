<legend>Personalization</legend>
<div class="mm-container">
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Outher block',
        'headerIcon' => 'icon-th-large',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )) ?>
    <div style="float: left; padding: 10px;">
        <?php echo $form->colorpickerRow($model, 'out_color',array('prepend'=>'<i class="icon-tint"></i>')); ?>
        <?php  echo $form->toggleButtonRow($model, 'out_texture');  ?>
    </div>
    <div style="float: left; padding: 10px; width: auto" id="preview_body">
            <img id="bodytextureimage" src="<?php echo Yii::app()->baseUrl . "/" .MM_UPLOADS_URL . "/".MMSettingsForm::getParam('body_texture') ?>" class="mm_thumb">
    </div>
    <div style="float: left; padding: 10px;">
        <label for="texture_body">Choose texture file</label>
        <?php echo CHtml::fileField('texture_body','',array('id'=>'texture_body')); ?>
    </div>
    <?php $this->endWidget() ?>
     <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Inner block',
        'headerIcon' => 'icon-th-large',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )) ?>
    <div style="float: left; padding: 10px;">
        <?php echo $form->colorpickerRow($model, 'in_color',array('prepend'=>'<i class="icon-tint"></i>')); ?>
        <?php  echo $form->toggleButtonRow($model, 'in_texture');  ?>
    </div>
    <div style="float: left; padding: 10px; width: auto" id="preview_in">
            <img id="intextureimage" src="<?php echo Yii::app()->baseUrl . "/" .MM_UPLOADS_URL . "/".MMSettingsForm::getParam('inner_texture') ?>" class="mm_thumb">
    </div>
    <div style="float: left; padding: 10px;">
        <label for="inner_texture">Choose texture file</label>
        <?php echo CHtml::fileField('inner_texture','',array('id'=>'inner_texture')); ?>
    </div>
    <div style="float: left; padding: 10px;">
        <?php echo $form->textFieldRow($model, 'in_width', array('prepend' => '<i class="icon-asterisk"></i>')); ?>
        <?php echo $form->dropDownListRow($model, 'in_position', array('left' => 'Left', 'right' => 'Right', 'center' =>'Center')); ?>
    </div>
    <div style="float: left; padding: 10px;">
        <?php  echo $form->toggleButtonRow($model, 'in_shadow');  ?>
    </div>
    <?php $this->endWidget() ?>
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Logo',
        'headerIcon' => 'icon-bookmark',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )) ?>
    <div style="float: left; padding: 10px;">
        <?php echo $form->dropDownListRow($model, 'logo_position', array('left' => 'Left', 'right' => 'Right', 'center' =>'Center')); ?>
        <?php  echo $form->toggleButtonRow($model, 'enable_logo');  ?>
    </div>
    <div style="float: left; padding: 10px; width: auto" id="preview_logo">
            <img id="logoimage" src="<?php echo Yii::app()->baseUrl . "/" .MM_UPLOADS_URL . "/".MMSettingsForm::getParam('logo_image') ?>" class="mm_thumb">
    </div>
    <div style="float: left; padding: 10px;">
        <label for="inner_texture">Choose logo file</label>
        <?php echo CHtml::fileField('logo_image','',array('id'=>'logo_image')); ?>
    </div>
    <?php $this->endWidget() ?>
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Header',
        'headerIcon' => 'icon-arrow-up',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )) ?>
    <div style="float: left; padding: 10px;">
        <?php echo $form->textFieldRow($model, 'app_name', array('prepend' => '<i class="icon-bell"></i>')); ?>
        <?php echo $form->textAreaRow($model, 'header_text', array('class'=>'span8', 'rows'=>5)); ?>
    </div>
    <?php $this->endWidget() ?>
     <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Footer',
        'headerIcon' => 'icon-arrow-down',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )) ?>
    <div style="float: left; padding: 10px;">
        <?php  echo $form->toggleButtonRow($model, 'enable_footer');  ?>
    </div>
    <div style="float: left; padding: 10px;">
        <?php echo $form->textAreaRow($model, 'footer_text', array('class'=>'span8', 'rows'=>5)); ?>
    </div>
    <?php $this->endWidget() ?>
</div>
<script>
    jQuery(document).ready(function() {
                $('#texture_body').on('change', function()
                    {
                        $("#preview_body").html('');
                        $("#preview_body").html('<img src="<?php echo Yii::app()->baseUrl.'/images/icons/ajax-loader.gif' ?>" alt="Uploading...."/>');
                        $("#horizontalForm").ajaxForm(
                    {
                        target: '#preview_body',
                        url: '<?php echo Yii::app()->createUrl('admin/uploadpicturetexture') ?>',
                        type: 'POST'
                    }).submit();
                });
                $('#inner_texture').on('change', function()
                    {
                        $("#preview_in").html('');
                        $("#preview_in").html('<img src="<?php echo Yii::app()->baseUrl.'/images/icons/ajax-loader.gif' ?>" alt="Uploading...."/>');
                        $("#horizontalForm").ajaxForm(
                    {
                        target: '#preview_in',
                        url: '<?php echo Yii::app()->createUrl('admin/uploadpicturetexturein') ?>',
                        type: 'POST'
                    }).submit();
                });
                $('#logo_image').on('change', function()
                    {
                        $("#preview_logo").html('');
                        $("#preview_logo").html('<img src="<?php echo Yii::app()->baseUrl.'/images/icons/ajax-loader.gif' ?>" alt="Uploading...."/>');
                        $("#horizontalForm").ajaxForm(
                    {
                        target: '#preview_logo',
                        url: '<?php echo Yii::app()->createUrl('admin/uploadpicturelogo') ?>',
                        type: 'POST'
                    }).submit();
                });
    });
</script>