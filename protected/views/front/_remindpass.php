<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name">Forgot your password ?</p>
</div>
<?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'remindpass-form',
                ));
        ?>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-right" class="noimage" style="padding-top: 5px; padding-bottom: 5px; line-height: 15px;">
        Please enter your email address and we will email you new password.
        <?php if (!empty($errors)){
          echo '<div class="errorSummary">';
          echo '<p>'.$errors.'</p>';
          echo '</div>';
        }
        ?>
            <?php if(!empty($info)){
                echo '<div class="wpmm-form-info">';
                echo '<ul><li>'.$info.'</li></ul>';
                echo '</div>';
            } ?>
    </div>
</div>
<div id="wpmm-item-details-more"<?php if (!Yii::app()->mobileDetect->isMobile()) echo ' style="height: 130px"'?>>
    <div style="padding-left: 50px">
        <div style="float:left; clear: both">
        <?php echo Chtml::label('Please enter user e-mail:', 'reset_email')?><br>
        <?php echo CHtml::textField('reset_email'); ?>
    </div>
        <div style="float:left; margin-left: 20px; margin-top: 20px;">
         <a href="#" class="wpmm-orange-btn" id="wpmm-password-reset">Push to Reset</a>
        </div><br style="clear: both">

        </div>
    <p>In order to reset your password You must enter a valid e-mail registered on our service.<br><b>Thank you for using our service !</b></p>
<?php $this->endWidget(); ?>
    </div>
<div id="wpmm-item-details-footer">
    <a href="#" id="wpmm-item-details-close">Close</a>
</div>
<script>
    if (!mobile) {
        jQuery('#wpmm-item-details').draggable({ handle: "#wpmm-item-details-header" });
    }
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
    jQuery('#wpmm-password-reset').on('click', function() {
        var username = jQuery('#reset_email').val();

        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'forgot_pass' : username,
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            success: function(data) {
                if(data != 0){
                    jQuery('#wpmm-item-details').html(data);
                } else {
                    data = data.slice(0, -1);
                    alert(data);
                    jQuery('#wpmm-item-details-close').click();
                }
            }
        });
        return false;
    });
</script>
