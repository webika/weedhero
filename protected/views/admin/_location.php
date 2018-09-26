<legend>Location</legend>
<div class="mm-container">

    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Vendor information',
        'headerIcon' => 'icon-shopping-cart',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )) ?>

    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'vendor_name', array('prepend' => '<i class="icon-info-sign"></i>'));
        echo $form->textFieldRow($model, 'vendor_email', array('prepend' => '<i class="icon-envelope"></i>'));
        ?>
    </div>

    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'vendor_city', array('prepend' => '<i class="icon-road"></i>'));
        echo $form->textFieldRow($model, 'vendor_state', array('prepend' => '<i class="icon-plane"></i>'));
        ?>
    </div>

    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textAreaRow($model, 'vendor_street', array('class' => 'span8', 'style' => "width:250px; height:90px;"));
        ?>
    </div>

    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'vendor_phone', array('prepend' => '<i class="icon-info-sign"></i>'));
        echo $form->textFieldRow($model, 'vendor_fax', array('prepend' => '<i class="icon-info-sign"></i>'));
        ?>
    </div>

    <?php $this->endWidget() ?>
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Zip-codes settings',
        'headerIcon' => 'icon-map-marker',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    )) ?>

    <div style="padding: 10px">
                        <table border="0" width="100%">
                            <tr>
                                <td valign="bottom"><?php echo $form->dropDownListRow($model, 'zip_type', array('0' => 'By self', '1' => 'By list', '2' => 'Both')); ?></td>
                                <td valign="bottom" width="200">
                                    <?php echo '<label>Zip code: </label>' . CHtml::activeTextField($model, "zipcode", array('id' => 'addzipcode')) ?>
                                </td>
                                <td valign="bottom" width="120">
                                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                                        'label' => 'Add Zipcode',
                                        'url' => '',
                                        'id' => 'zipsubmit',
                                        'htmlOptions' => array('style' => 'margin: 0 0 10px 5px;'),
                                    )) ?>
                                </td>
                            </tr>
                        </table>
                        <?php
                        Yii::app()->clientScript->registerScript(
                            'add-zipcode-action',
                            "jQuery('#zipsubmit').click(function() {
                                jQuery.ajax({
                                    'type':'POST',
                                    'data':{
                                        'action' : 'admin',
                                        'addzipcode' : jQuery('#addzipcode').val(),
                                        '" . Yii::app()->request->csrfTokenName . "':'" . Yii::app()->request->csrfToken . "'
                                    },
                                    'success':function( data ) {
                                        // handle return data
                                        jQuery('#zipcodetablecontainer').empty();
                                        jQuery('#zipcodetablecontainer').append(data);
                                        jQuery('#addzipcode').val('');
                                    },
                                    'error':function(data){  },
                                    'url':'" . Yii::app()->createUrl('admin/addzipcode') . "',
                                    'cache':false
                                });
                            });");
                        ?>
        <hr>
        <table border="0" s>
            <tr>
                <td width="250" valign="top">
                    <?php
                    echo $form->textFieldRow($model, 'zip_self', array('prepend' => '<i class="icon-chevron-down"></i>'));
                    ?>
                </td>
                <td width="100%" style="padding-left: 20px;">
                    <label>Allowed zip-codes</label>
                    <div id="zipcodetablecontainer">
                    <?php $this->renderPartial('_ziptable', array('ziplist' => unserialize($model->zip_list))); ?>
                    </div>
                </td>
            </tr>
        </table>
        </div>
<?php $this->endWidget() ?>
</div>