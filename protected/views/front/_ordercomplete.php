<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name">Order Complete</p>
</div>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-right" class="noimage"<?php if (!Yii::app()->mobileDetect->isMobile()) echo ' style="padding-top: 5px; padding-bottom: 0px; line-height: 15px;"'?>>
        <b>Your order is complete !</b>
    </div>
</div>
<?php
$blockH=200;
if(!empty($order->trans_id)){
  $blockH=220;
}
?>
<div id="wpmm-item-details-more" style="height: <?php echo $blockH; ?>px; line-height: normal">
    <div style="text-align: center">
        <?php $theme=MMSettingsForm::getParam('theme'); ?>
        <img src="<?php echo Yii::app()->baseUrl.'/themes/'.$theme.'/images/OK.png'; ?>" border="0" width="48" height="48" alt="OK" style="margin: 0; padding: 0;"/>
        <div class="wpmm-forn-thnx" style="font-size: 48px;">Thank you !</div>
    </div>
    <div class="wpmm-thnx-info">
        <span>Order id: <?php echo $order->id; ?></span> <br>
        <?php
            if(!empty($order->trans_id)){
              echo '<span>Transaction id: '.$order->trans_id.'</span> <br>';
            }
        ?>
        <span>Please check your email !</span> <br>
    </div>
</div>
<div id="wpmm-item-details-footer">
    <a href="#" class="wpmm-green-btn" id="wpmm-order-finish">Done</a>
</div>
<script>
    if (!mobile) {
        jQuery('#wpmm-item-details').draggable({ handle: "#wpmm-item-details-header" });
    }
    refresh_order(false);
    jQuery('#wpmm-order-finish').on('click', function() {
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
        refresh_order(false);
        return false;
    });
</script>
