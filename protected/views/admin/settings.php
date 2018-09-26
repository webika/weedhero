<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'horizontalForm',
    'type' => 'vertical',
    'enableClientValidation' => true,
    'htmlOptions' => array('name' => 'MenuMakerSettigsForm', 'enctype' => 'multipart/form-data')
        )
);
$this->widget('bootstrap.widgets.TbTabs', array(
    'id' => 'mm-settings',
    'type' => 'tabs',
    'tabs' => array(
        array(
            'label' => 'General',
            'content' =>
            $this->renderPartial(
                '_general', array(
                'model' => $model,
                'form' => $form,
                'worktime' => $worktime,
                    ), true
            ),
            'active' => true
        ),
        /*array(
            'label' => 'Location',
            'content' =>
            $this->renderPartial(
                    '_location', array(
                'model' => $model,
                'form' => $form,
                    ), true
            )
        ),*/
        array(
            'label' => 'Personalization',
            'content' =>
            $this->renderPartial(
                    '_personalization', array(
                'model' => $model,
                'form' => $form,
                    ), true
            )
        ),
        array(
            'label' => 'Theme',
            'content' =>
            $this->renderPartial(
                    '_themes', array(
                'model' => $model,
                'form' => $form,
                    ), true
            )
        ),
        array(
            'label' => 'License',
            'content' =>
            $this->renderPartial(
                    '_license', array(
                'model' => $model,
                'form' => $form,
                    ), true
            )
        ),
    )
))
?>

<div class="form-actions">

<?php
echo
Chtml::hiddenField('action', 'admin'),
CHtml::ajaxSubmitButton(
        'Save Changes', Yii::app()->createUrl('admin/savesettings'), array(
    'success' => 'function(data){
        jQuery(".ajax_loader_settings").fadeOut();
        jQuery.ajax({
            url: "'.Yii::app()->createUrl('admin/savesettings').'",
            data: {
                "action": "admin",
                "refresh_key": true,
                "'.Yii::app()->request->csrfTokenName.'":"'.Yii::app()->request->csrfToken.'"
            },
            type: "POST",
            dataType: "html",
            success: function(data) {
                jQuery(".ajax_loader_settings").fadeOut();
                if (data) {
                    jQuery("#key-info").html(data);
                }
            }
        });
    }',
    'error' => 'function(data){alert(data);}'
        ), array(
    'id' => 'settings-submit',
    'name' => 'settings-submit',
    'class' => 'btn',
    'onClick' => 'jQuery(".ajax_loader_settings").fadeIn();')
),
 CHtml::image(
        Yii::app()->baseUrl . "/images/icons/ajax-loader.gif", "", array(
    "style" => "height:16px; display:none",
    'class' => 'ajax_loader_settings'
        )
)
?>
</div>
<div class="ajax_test"></div>

<?php $this->endWidget() ?>