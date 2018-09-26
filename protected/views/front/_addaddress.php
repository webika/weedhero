<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name">Add new address</p>
</div>
<?php
        $typeedit=0;
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'address-form',
                ));
        ?>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-right" class="noimage"<?php if (!Yii::app()->mobileDetect->isMobile()) echo ' style="padding-top: 5px; padding-bottom: 0px; line-height: 15px;"'?>>
        <b>Please fill out following fields!</b>
        <?php echo $form->errorSummary($model); ?>
    </div>
</div>
<div id="wpmm-item-details-more">
    <div style="padding-left: 20px">
        <div style="float: left; padding: 5px;">
            <?php echo $form->labelEx($model, 'address'); ?><br>
            <?php echo $form->textArea($model, 'address', Yii::app()->mobileDetect->isMobile() ? array() : array('style' => 'width:440px; height:80px;') ); ?><br>
        </div><br style="clear: both">
        <div style="float: left; padding: 5px;">
            <?php echo $form->labelEx($model, 'city'); ?><br>
            <?php echo $form->textField($model, 'city') ?><br>
        </div>
        <div style="float: left; padding: 5px;">
            <?php echo $form->labelEx($model, 'state'); ?><br>
            <?php echo $form->textField($model, 'state') ?><br>
        </div><br style="clear: both">
        <div style="float: left; padding: 5px;">
            <?php echo $form->labelEx($model, 'location'); ?><br>
            <?php echo $form->textField($model, 'location') ?><br>
        </div>
        <?php if($editmode != 'new'){
            echo $form->hiddenField($model,'id');
            $typeedit=$model->id;
        } ?>
<?php $this->endWidget(); ?>
    </div>
</div>
<div id="wpmm-item-details-footer">
    <a href="#" id="wpmm-item-details-close">Close</a>
    <?php if($editmode == 'new'){ ?>
        <a href="#" class="wpmm-green-btn" id="wpmm-address-add">Add</a>
    <?php } else { ?>
        <a href="#" class="wpmm-green-btn" id="wpmm-address-add">Save</a>
        <a href="#" class="wpmm-orange-btn" id="wpmm-address-delete">Delete</a>
    <?php } ?>
</div>
<script>
    jQuery('#wpmm-item-details-close').on('click', function() {
        jQuery('#wpmm-item-details').hide();
        if (mobile) {
            jQuery('#wpmm-footer').show();
            if (jQuery("#wpmm-footer a.ui-btn-active").parent('li').hasClass('ui-block-a')) {
                jQuery('#wpmm-form').hide();
                jQuery('#wpmm .ui-collapsible-set').show();
            } else if (jQuery("#wpmm-footer a.ui-btn-active").parent('li').hasClass('ui-block-b')) {
                jQuery('#wpmm .ui-collapsible-set').hide();
                jQuery('#wpmm-form').show();
            }
        }
        return false;
    });
    jQuery('#wpmm-item-details').draggable({ handle: "#wpmm-item-details-header" });
    jQuery('#wpmm-address-delete').on('click', function() {
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'add_address_delete' : '<?php echo $typeedit ?>',
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            success: function(data) {
                if(data != 0){
                    jQuery('#wpmm-item-details').html(data);
                } else {
                    jQuery('#wpmm-item-details-close').click();
                    refresh_order();
                }
            }
        });
        return false;
    });
    jQuery('#wpmm-address-add').on('click', function() {
        var form = {};
        jQuery("#wpmm-item-details-more input, #wpmm-item-details-more textarea").each(function(){
            var namestr =  jQuery(this).attr("name");
            if(namestr != 'WPMM_CSRF_TOKEN'){
            namestr = namestr.match(/\[(.*?)\]/);
            form[namestr[1]] = jQuery(this).val();
            }
        });
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'add_address' : '<?php echo $typeedit ?>',
                'MMAddress': form,
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            success: function(data) {
                if(data != 0){
                    jQuery('#wpmm-item-details').html(data);
                } else {
                    jQuery('#wpmm-item-details-close').click();
                    refresh_order();
                }
            }
        });
        return false;
    });
</script>
