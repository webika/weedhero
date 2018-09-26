<?php
$order=Yii::app()->session['mm_order'];
$errors=$order['errors'];
$mobile = Yii::app()->mobileDetect->isMobile();
if(!empty($order['card_form']['orderamount'])){
    $orderammount=$order['card_form']['orderamount'];
}
?>
<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name">Checkout information</p>
</div>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-right" class="noimage"<?php if (!Yii::app()->mobileDetect->isMobile()) echo ' style="padding-top: 5px; padding-bottom: 0px; line-height: 15px;"'?>>
        <?php
        $paypal = MMSettingsForm::getParam('enable_payments_paypal');
        $authirize = MMSettingsForm::getParam('enable_payments');
        if($paypal && $authirize){ ?>
        Choose your payment method:
            <?php if (!empty($order['card_form']['payment_vendor'])) { ?>
                <ul>
                    <li style="display: inline"><input type="radio" name="payment_vendor" id="wpmm-vendor-paypal" value="PayPal" <?php if ($order['card_form']['payment_vendor'] == 'PayPal') echo 'checked="checked"'; ?>><label style="float: none" for="wpmm-vendor-paypal">PayPal</label></li>
                    <li style="display: inline"><input type="radio" name="payment_vendor" id="wpmm-vendor-authorize" value="Authorize.net" <?php if ($order['card_form']['payment_vendor'] == 'Authorize.net') echo 'checked="checked"'; ?>><label style="float: none" for="wpmm-vendor-authorize">Authorize.net</label></li>
                </ul>
            <?php } else {
                $order['card_form']['payment_vendor']='Authorize.net';
                ?>
                <ul>
                    <li style="display: inline"><input type="radio" name="payment_vendor" id="wpmm-vendor-paypal" value="PayPal" ><label style="float: none" for="wpmm-vendor-paypal">PayPal</label></li>
                    <li style="display: inline"><input type="radio" name="payment_vendor" id="wpmm-vendor-authorize" value="Authorize.net" checked="checked"><label style="float: none" for="wpmm-vendor-authorize">Authorize.net</label></li>
                </ul>
            <?php } ?>
        <?php } else {
            echo 'Your payment method is: ';
            if(!$paypal){
                echo '<b>Authorize.net</b>';
                $order['card_form']['payment_vendor']='Authorize.net';
                }
            else{
                echo '<b>PayPal</b>';
                $order['card_form']['payment_vendor']='PayPal';
                }
            echo '<br>';
            } ?>
        Please enter your billing information
        <?php if (!empty($errors['card_form'])){
          echo '<div class="errorSummary">';
          echo '<p>'.$errors['card_form'].'</p>';
          echo '</div>';
        }
        ?>
    </div>
</div>
<div id="wpmm-item-details-more"<?php if (!Yii::app()->mobileDetect->isMobile()) echo ' style="height: 240px"'?>>
    <div id="wpmm-auth-form" <?php if (!empty($order['card_form']['payment_vendor']) && $order['card_form']['payment_vendor'] == 'PayPal') echo 'style="display: none"'; ?>>
<div id="wpmm-auth-image"></div>
    <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'remindpass-form',
                ));
        ?>
    <div style="padding-left: 25px">
        <div style="float:left; margin-right: 15px;">
            <?php echo Chtml::label('Credit card number:', 'card_number')?><br>
            <?php echo CHtml::textField('card_number',$order['card_form']['card_number'],array('maxlength'=>'16')); ?><br>
        </div>
        <div style="float:left;">
            <?php echo Chtml::label('Card expiration date:', 'exp_date')?><br>
            <?php echo CHtml::textField('exp_date',$order['card_form']['exp_date'],array('maxlength'=>'7')); ?><br>
        </div>
        <br style="clear: both">
    <?php $this->endWidget(); ?>
</div>
        </div>
    <div id="wpmm-paypal-form" <?php if (!empty($order['card_form']['payment_vendor']) && $order['card_form']['payment_vendor'] == 'Authorize.net') echo 'style="display: none"'; ?>>
        <div style="text-align: center">
            <img src="<?php echo 'images/paypal.png'; ?>" width="199" height="73" border="0" alt="credit_card" style="margin-top: 5px;margin-bottom: 5px;"/><br>
        </div>
       <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'remindpass-form',
                ));
        ?>
    <div style="padding-left: 25px">
        <div style="float:left; margin-right: 15px;">
            <?php echo Chtml::label('First name:', 'p_first_name')?><br>
            <?php echo CHtml::textField('p_first_name',$order['card_form']['p_first_name']); ?><br>
        </div>
        <div style="float:left; <?php if($mobile) echo 'margin-right: 15px;'?>">
            <?php echo Chtml::label('Last name:', 'p_last_name')?><br>
            <?php echo CHtml::textField('p_last_name',$order['card_form']['p_last_name']); ?><br>
        </div>
        <div style="float:left; margin-right: 15px;">
            <?php echo Chtml::label('Billing Address:', 'p_billing_address')?><br>
            <?php echo CHtml::textField('p_billing_address',$order['card_form']['p_billing_address']); ?><br>
        </div>
        <div style="float:left; <?php if($mobile) echo 'margin-right: 15px;'?>">
            <?php echo Chtml::label('Billing Country:', 'p_billing_country')?><br>
            <?php echo CHtml::dropDownList('p_billing_country', '', Helpers::get_country_code(), array('style'=>'width:210px','empty' => 'Please select country...', 'id' => 'p_billing_country', 'options' => isset($order['card_form']) ? array($order['card_form']['p_billing_country'] => array('selected' => true)) : array())); ?><br>
        </div>
        <div style="float:left; margin-right: 15px;">
            <?php echo Chtml::label('Billing City:', 'p_billing_city')?><br>
            <?php echo CHtml::textField('p_billing_city',$order['card_form']['p_billing_city']); ?><br>
        </div>
        <div style="float:left; <?php if($mobile) echo 'margin-right: 15px;'?>">
            <?php echo Chtml::label('Billing State:', 'p_billing_state')?><br>
            <?php echo CHtml::textField('p_billing_state',$order['card_form']['p_billing_state']); ?><br>
        </div>
        <div style="float:left; margin-right: 15px;">
            <?php echo Chtml::label('Billing Zip:', 'p_billing_zip')?><br>
            <?php echo CHtml::textField('p_billing_zip',$order['card_form']['p_billing_zip']); ?><br>
        </div>
        <br style="clear: both">
        <div style="float:left; margin-right: 15px;">
            <?php echo Chtml::label('Card Number:', 'p_card_number')?><br>
            <?php echo CHtml::textField('p_card_number',$order['card_form']['p_card_number']); ?><br>
        </div>
        <div style="float:left; margin-right: 5px;">
            <?php echo Chtml::label('Exp. Month:', 'p_expiration_month')?><br>
            <?php echo CHtml::textField('p_expiration_month',$order['card_form']['p_expiration_month'], array('style'=>'width:90px;')); ?><br>
        </div>
        <div style="float:left; <?php if($mobile) echo 'margin-right: 15px;'?>">
            <?php echo Chtml::label('Exp. Year:', 'p_expiration_year')?><br>
            <?php echo CHtml::textField('p_expiration_year',$order['card_form']['p_expiration_year'], array('style'=>'width:90px;')); ?><br>
        </div>
        <div style="float:left; margin-right: 15px;">
            <?php echo Chtml::label('CVC code:', 'p_cv_code')?><br>
            <?php echo CHtml::textField('p_cv_code',$order['card_form']['p_cv_code']); ?><br>
        </div>
        <div style="float:left; <?php if($mobile) echo 'margin-right: 15px;'?>">
            <?php echo Chtml::label('Credit type:', 'p_credit_type')?><br>
            <?php echo CHtml::dropDownList('p_credit_type', '', Helpers::get_credit_supported(), array('style'=>'width:210px','empty' => 'Please select card type...', 'id' => 'p_credit_type', 'options' => isset($order['card_form']) ? array($order['card_form']['p_credit_type'] => array('selected' => true)) : array())); ?><br>
        </div>
        <br style="clear: both">
    <?php $this->endWidget(); ?>
    </div>
    </div>
    </div>
<div id="wpmm-item-details-footer">
    <div style="float: left">
            <?php echo Chtml::label('Total: $'.$orderammount, '')?>
            <?php echo CHtml::hiddenField('wpmm-orderamount', number_format((float) $orderammount, 2)); ?>
       <?php if( !($paypal && $authirize)){ ?>
             <input type="radio" name="payment_vendor" value="<?php echo $order['card_form']['payment_vendor']; ?>" checked="checked" style="display: none"><?php } ?>
    </div>
    <a href="#" id="wpmm-item-details-close">Cancel</a>
        <a href="#" class="wpmm-green-btn" id="wpmm-order-confirm">Confirm</a>
</div>
<script>
    if (!mobile) {
        jQuery('#wpmm-item-details-more').mCustomScrollbar();
        jQuery(document).trigger('resize');
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
    jQuery('#wpmm-item-details-content-right input').on('click', function() {
        if(jQuery(this).val() == 'PayPal'){
            jQuery('#wpmm-auth-form').hide();
            jQuery('#wpmm-paypal-form').show();
            jQuery('#wpmm-item-details-more').mCustomScrollbar('update');
        } else {
            jQuery('#wpmm-paypal-form').hide();
            jQuery('#wpmm-auth-form').show();
            jQuery('#wpmm-item-details-more').mCustomScrollbar('update');
        }
    });
    jQuery('#wpmm-order-confirm').on('click', function() {
        jQuery('#wpmm-item-details').hide();
        jQuery('#wpmm-item-loading').css({'top':'50%','left':'50%','margin-left':'-'+24+'px'}).show();
        checkoutOrderCard();
        return false;
    });
</script>
