<?php $payment_type=array('credit_card'=>'Credit Card','cash'=>'Cash'); ?>
<?php $total=0; ?>
<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name">Checkout information</p>
</div>
<?php $order = Yii::app()->session['mm_order']; ?>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-right" class="noimage"<?php if (!Yii::app()->mobileDetect->isMobile()) echo ' style="padding-top: 5px; padding-bottom: 0px; line-height: 15px;"'?>>
        Please confirm your order
    </div>
</div>
<div id="wpmm-item-details-more"<?php if (!Yii::app()->mobileDetect->isMobile()) echo ' style="height: 300px"'?>>
    <?php
    $location = MMLocation::model()->findByPk($order['location']);
    if($location != NULL)
     echo 'Location : <b>'.$location->name.'</b><br>';
    if (Yii::app()->user->isGuest) {
        echo 'Your user name is: <b>' . $order['newuserform']['c_mail'] . '</b><br>';
    } else {
        echo 'Your user name is: <b>' . Yii::app()->user->name . '</b><br>';
    }
    if($order['cart_form']['delivery-type'] == 'Delivery'){
    echo 'Your choosen delivery type is: <b>' . $order['cart_form']['delivery-type'] . '</b> to address: <br>'; } else {
    echo 'Your choosen delivery type is: <b>' . $order['cart_form']['delivery-type'].'<br>';
    }
    if ($order['cart_form']['delivery-type'] == 'Delivery') {
        if (!Yii::app()->user->isGuest) {
            $addrs = MMAddress::model()->findByPk($order['cart_form']['address']);
            if ($addrs == NULL) {
                throw new CHttpException(404, 'The requested page does not exist.');
                return;
            }
        }
        if ($addrs != NULL) {
            echo '<b>';
            echo $addrs->address . ',  ';
            echo $addrs->city . ', ';
            if (!empty($addrs->state))
                echo $addrs->state . '  ';
            echo $addrs->location . '  ';
            echo '</b><br>';
        }
    }
    echo 'You paying by: <b>'.$payment_type[$order['cart_form']['payment_type']].'</b><br>';
    if($order['cart_form']['delivery-time'] == 'asap'){
        echo 'You want the order to be ready <b>ASAP</b><br>';
    } else if($order['cart_form']['delivery-time'] == 'today'){
        echo 'You want the order to be ready <b>Today by: '.$order['cart_form']['order-time'].'</b><br>';
    } else {
        echo 'You want the order to be ready <b>On: '.$order['cart_form']['order-time'].'</b><br>';
    }
    if(!empty($order['cart_form']['promo_code'])){
        echo 'You using promo code <b>: '.$order['cart_form']['promo_code'].'</b><br>';
    }
    if(!empty($order['cart_form']['delivery-notes'])){
        echo '<b>Delivery notes: </b>'.$order['cart_form']['delivery-notes'].'<br>';
    }
    ?>
    <hr class="wpmm-form-hr">
    <table border="0" width="100%">
        <tr style="border-bottom: 2px solid black">
            <td style="text-align: center"><b>Qty.</b></td>
            <td style="text-align: center"><b>Item</b></td>
            <td style="text-align: center" width="60"><b>Price</b></td>
        </tr>
        <?php
        $summ = 0;
        $tips = 0;
        $promodisk = 0;
        $discount = MMSettingsForm::getParam('discount');
        $taxrate = MMSettingsForm::getParam('tax_rate');
        $deliveryprice = MMSettingsForm::getParam('delivery_charge');
        ?>
        <?php
        $slicearray = array(3=>'left',1=>'full',2=>'right');
        foreach ($order['items'] as $order_item) {
            echo '<tr>';
            $item_price = 0;
            $item = Helpers::getItemById($order_item['id']);
            $item_price+=(float) $item->price;
            $attr_string = '';
            if (count($order_item['attributes'])) {
                foreach ($order_item['attributes'] as $attr_id => $attr_price) {
                    $attr = Helpers::getAttrById($attr_id);
                    if($attr->group->type == 2){
                        if($attr_price['size'] == 2 || $attr_price['size'] == 3) $attr->price=$attr->price/2;
                        $attr_string.=$attr->name . ' ( '.$slicearray[$attr_price['size']].' ) $' . $attr->price . " | ";
                        $item_price+=(float) $attr->price;
                    } else {
                        $attr_string.=$attr->name . ' $' . $attr->price . " | ";
                        $item_price+=(float) $attr->price;
                    }
                }
            }
            $attr_string=substr($attr_string,0,-2);
            if($item->type == 1) $item->name.=' ( '.Helpers::getPizzaCut($order_item['cut']).' ) ';
            $item_price = (float) $item_price * (float) $order_item['quantity'];
            echo '<td style="text-align: center" valign="top">' . $order_item['quantity'] . '</td>';
            echo '<td valign="top"><b>' . $item->name . '</b>';
            if (!empty($attr_string))
                echo '<i>( ' . $attr_string . ' )</i>';
            if (!empty($order_item['instructions']))
                echo '<br><b>Instructions:</b> ' . $order_item['instructions'];
            echo '</td>';
            echo '<td style="text-align: center" valign="top"><b> $' . number_format((float) $item_price, 2) . '</b></td>';
            echo '</tr>';
            $summ+=(float) $item_price;
        }
        $discount = ((float) $discount * (float) $summ) / 100;
        if (!empty($order['cart_form']['promo_code'])) {
            $code =  MMCoupons::model()->find('code ="'.$order['cart_form']['promo_code'].'"');
            $code->amount;
            if($code->type == 0){
                $promodisk=$code->amount;
            } else {
                $promodisk=((float) $code->amount * (float) ($summ-$discount)) / 100;
            }
        }
        $taxrate = ((float) $taxrate * ((float) $summ - (float) $discount - (float) $promodisk)) / 100;
        if ($order['cart_form']['payment-tip-type'] == 'percent') {
            $tips = ((float) $order['cart_form']['payment-tip-value'] * (float) $summ) / 100;
        } else {
            $tips = (float) $order['cart_form']['payment-tip-value'];
        }
        $total = $summ - $discount - $promodisk + $taxrate + $tips;
        ?>
        <tr>
            <td></td>
            <td style="text-align: right"><b>Food & Bev Total:</b>	</td>
            <td style="text-align: center">$<?php echo number_format((float) $summ, 2); ?></td>
        </tr>
<?php if ($discount > 0) { ?>
            <tr>
                <td></td>
                <td style="text-align: right"><b>Discount:</b>	</td>
                <td style="text-align: center"> -$<?php echo number_format((float) $discount, 2); ?></td>
            </tr>
<?php } ?>
<?php if ($promodisk > 0) {  ?>
            <tr>
                <td></td>
                <td style="text-align: right"><b>Promo Code:</b>	</td>
                <td style="text-align: center"> -$<?php echo number_format((float) $promodisk, 2); ?></td>
            </tr>
<?php } ?>
        <tr>
            <td></td>
            <td style="text-align: right"><b>Sales Tax:</b> </td>
            <td style="text-align: center"> $<?php echo number_format((float) $taxrate, 2); ?></td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: right"><b>Total with tax:</b> </td>
            <td style="text-align: center"> $<?php echo number_format((float) $taxrate + (float) $summ - (float) $discount - (float) $promodisk, 2); ?></td>
        </tr>
<?php if ($order['cart_form']['delivery-type'] == 'Delivery') { ?>
            <tr>
                <td></td>
                <td style="text-align: right"><b>Delivery charge:</b> </td>
                <td style="text-align: center"> $<?php echo number_format((float) $deliveryprice, 2); ?></td>
            </tr>
<?php } else {
    $deliveryprice = 0;
}
$total+=(float)$deliveryprice;
        if((float)$tips > 0){?>
        <tr>
            <td></td>
            <td style="text-align: right"><b>Tips:</b> </td>
            <td style="text-align: center"> $<?php echo number_format((float) $tips, 2); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td style="text-align: right"><b>Total:</b> </td>
            <td style="text-align: center"> $<?php echo number_format((float) $total, 2); ?></td>
        </tr>
    </table>
</div>
</div>
<div id="wpmm-item-details-footer">
    <a href="#" id="wpmm-item-details-close">Cancel</a>
    <?php if($order['cart_form']['payment_type'] == 'credit_card') { ?>
        <a href="#" class="wpmm-green-btn" id="wpmm-order-continue">Continue</a>
    <?php } else { ?>
        <a href="#" class="wpmm-green-btn" id="wpmm-order-confirm">Confirm</a>
    <?php } ?>
</div>
<script>
    if (!mobile) {
        jQuery('#wpmm-item-details-more').mCustomScrollbar();
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
    <?php if($order['cart_form']['payment_type'] == 'credit_card') { ?>
    jQuery('#wpmm-order-continue').on('click', function() {
        jQuery('#wpmm-item-details').hide();
        jQuery('#wpmm-item-loading').css({'top':'50%','left':'50%','margin-left':'-'+24+'px'}).show();
        continueOrder('<?php echo number_format((float) $total, 2,'.',''); ?>');
        return false;
    });
    <?php } else { ?>
    jQuery('#wpmm-order-confirm').on('click', function() {
        jQuery('#wpmm-item-details').hide();
        jQuery('#wpmm-item-loading').css({'top':'50%','left':'50%','margin-left':'-'+24+'px'}).show();
        checkoutOrder();
        return false;
    });
    <?php } ?>
</script>
