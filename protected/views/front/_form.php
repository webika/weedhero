<?php
$order = Helpers::getSessionOrder();
$minimumorder = MMSettingsForm::getParam('min_order');
if (count($order['items'])) {
    if (Helpers::getOrderSubtotal() < $minimumorder)
        $order['errors']['items']['minimumorder'] = 'Minimum order: <strong>$' . number_format((float) $minimumorder, 2) . '</strong>';
    else
        unset($order['errors']['items']['minimumorder']);
} else {
    unset($order['errors']['items']['minimumorder']);
}
if ($order)
    Helpers::setSessionOrder($order);
if (isset($order['errors']))
    $errors = $order['errors'];
if (isset($order['cart_form']))
    $cartform = $order['cart_form'];
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'front-form',
        ));
$info = array();
$discount = MMSettingsForm::getParam('discount');
$taxrate = MMSettingsForm::getParam('tax_rate');
if(MMSettingsForm::getParam('enable_payments') || MMSettingsForm::getParam('enable_payments_paypal')){
$enable_payments = true;
} else $enable_payments = false;
$delivery = $order['cart_form']['delivery-type'] == 'Delivery';
$hours_popup = Helpers::GetHoursPopUp($order['location']);
$deliverycharge = MMSettingsForm::getParam('delivery_charge');
$enable_delivery = MMSettingsForm::getParam('enable_delivery');
if ($discount > 0)
    $info['items']['discount'] = Helpers::getDiscountInfo();
if ($taxrate > 0)
    $info['items']['tax'] = Helpers::getTaxInfo();
if ($order['cart_form']['payment-tip-value'] > 0)
    $info['items']['tip'] = Helpers::getTipInfo();
if ($deliverycharge > 0)
    $info['items']['delivery'] = Helpers::getDeliveryInfo();
?>
<div id="wpmm-form-container-scroll">
    <div id="wpmm-form-summary">
        <div class="wpmm-form-header">
            <p>Summary</p>
        </div>
        <hr class="wpmm-form-hr">
        <?php
        if (count($this->order['items'])) {
            if (isset($errors)) {
               unset($errors['items']['min']);
            }
            if ($order['cart_form']['delivery-time'] == 'date' || $order['cart_form']['delivery-time'] == 'today') {
                if (!empty($order['cart_form']['order-time']))
                    if (!Helpers::validateWorkingHours($order['cart_form']['order-time'],$order['location']))
                        $errors['delivery']['work_time'] = Helpers::getWorkingHours($order['cart_form']['order-time'],$order['location']);
                    else
                        unset($errors['delivery']['work_time']);
            } else {
                if (isset($errors)) {
                    unset($errors['delivery']['work_time']);
                 }
            }
        };
        if (isset($errors['items'])) {
            Helpers::renderErrors($errors['items']);
        };
        if (count($this->order['items'])) :
            unset($info['items']);
            if ($discount > 0)
                $info['items']['discount'] = Helpers::getDiscountInfo(true);
            if ($taxrate > 0)
                $info['items']['tax'] = Helpers::getTaxInfo(true);
            if ($order['cart_form']['payment-tip-value'] > 0)
                $info['items']['tip'] = Helpers::getTipInfo(true);
            if ($delivery)
                $info['items']['delivery'] = Helpers::getDeliveryInfo();
            ?>
            <?php
            foreach ($this->order['items'] as $key => $order_item) :
                $item = Helpers::getItemById($order_item['id']);
                if (!Helpers::validateItemTime($item->id)) {
                    $order['errors'][$key] = 'Not available at this time.';
                    Helpers::renderErrors(array($order['errors'][$key]));
                }
                ?>
                <table class="wpmm-form-item-table" data-key="<?php echo $key ?>" data-id="<?php echo $item->id ?>">
                    <tr class="wpmm-form-item-buttons">
                        <td>
                            <a href="#" class="wpmm-form-item-edit" title="Update" /></div>
                            <a href="#" class="wpmm-form-item-delete" title="Remove" /></div>
                        </td>
                    </tr>
                    <tr class="wpmm-form-item">
                        <td class="wpmm-form-item-quantity"><?php echo $order_item['quantity'] . ' ×' ?></td>
                        <td class="wpmm-form-item-name"><?php echo $item->name ?></td>
                        <td class="wpmm-form-item-price"><?php echo '$' . Helpers::itemCountTotal($key) ?></td>
                    </tr>
                </table>
                <div class="wpmm-form-item-extra" data-key="<?php echo $key ?>">
                        <?php if (is_array($order_item['attributes']) && count($order_item['attributes'])) : ?>
                        <table class="wpmm-form-attr-table">
                            <?php foreach ($order_item['attributes'] as $attr_id => $attr_price) :
                                $attr = Helpers::getAttrById($attr_id);
                                ?>
                                <tr>
                                    <?php
                                    $halfprice = 1;
                                    if(!empty($attr_price['size'])){
                                        if($attr_price['size'] == 3){ $attr->name.=' (left)'; $halfprice = 2; } elseif ($attr_price['size'] == 2){ $attr->name.=' (right)'; $halfprice = 2;} else $attr->name.=' (full)';
                                    }
                                    ?>
                                    <td class="wpmm-form-attr-name"><?php echo $attr->name ?></td>
                                    <td class="wpmm-form-attr-price">
                                        <?php
                                        if (substr($attr_price[0], 0, 1) === '-')
                                            echo '- $' . number_format(((float) substr($attr_price['price'], 1))/$halfprice, 2);
                                        else
                                            echo '+ $' . number_format(((float) $attr_price['price'])/$halfprice, 2);
                                        ?>
                                    </td>
                                </tr>
                        <?php endforeach ?>
                        </table>
        <?php endif ?>
                        <?php if (!empty($order_item['instructions'])) : ?>
                        <strong class="wpmm-form-item-instructions-header">Special instructions:</strong>
                        <div class="wpmm-form-item-instructions">
                        <?php echo nl2br($order_item['instructions']) ?>
                        </div>
                <?php endif ?>
                </div>
            <?php
            endforeach;
            Helpers::setSessionOrder($order);
            if(isset($info['items'])){
                    Helpers::renderInfo($info['items']);
                }
            ?>
            <div id="wpmm-form-total">
                Order total: <span class="wpmm-form-total-value">$<?php echo Helpers::getOrderTotal() ?></span>
            </div>
        <?php
        else :
            if (isset($info['items'])) {
                Helpers::renderInfo($info['items']);
            }
        endif
        ?>
        <a href="#" class="wpmm-forn-dev-toggle">Delivery notes</a>
        <div class="wpmm-form-delivery-info">
            <textarea placeholder="Additional comments about your order..." name="delivery-notes"><?php if (!empty($cartform['delivery-notes'])) echo $cartform['delivery-notes']; ?></textarea>
        </div>
        <?php if(MMCoupons::model()->exists('active = 1')) {?>
        <div <?php if(!Yii::app()->mobileDetect->isMobile()) echo 'style="margin-left: 15px;"'; else echo 'style="padding-left: 5px; padding-right: 5px"'; ?>>
        <label for="promo_code">Promo code</label><br>
            <?php echo Chtml::textField('promo_code',$order['cart_form']['promo_code']) ?><br>
        </div>
        <?php } ?>
        <hr class="wpmm-form-hr">
    </div>
    <div id="wpmm-form-delivery">
        <div class="wpmm-form-header">
            <p><?php if($enable_delivery) echo 'Delivery'; else echo 'Info'; ?></p>
        </div>
        <hr class="wpmm-form-hr">
<?php
if (isset($errors['delivery'])) {
    Helpers::renderErrors($errors['delivery']);
}
?>
        <div style="margin: 5px;">
        <label for="wpmm-location">Select location</label><br>
        <?php if(Yii::app()->mobileDetect->isMobile()){
            echo CHtml::dropDownList('wpmm-location', $order['location'], Helpers::getLocationList(false),array('class'=>'wpmm-location-dropdown', 'data-native-menu'=>'false'));
        } else {
            echo CHtml::dropDownList('wpmm-location', $order['location'], Helpers::getLocationList(false),array('class'=>'wpmm-location-dropdown'));
        }
        ?>
        </div>
        <a class="wpmm-green-btn" id="wpmm-form-delivery-info" href="#" title="<?php echo $hours_popup; ?>"><?php if($enable_delivery) echo 'Location Delivery Info'; else echo 'Location Info'; ?></a>
        <hr class="wpmm-form-hr">
            <?php
            if (isset($errors['user'])) {
                Helpers::renderErrors($errors['user']);
            }
            ?>
        <div style="margin-left: 5px">
            <?php if(Yii::app()->user->isGuest || Yii::app()->user->role == 'admin'){ ?>
        <ul id="wpmm-user-tabs">
            <li class="active"><a href='#wpmm-user-tab1'>New User</a></li>
            <li><a href='#wpmm-user-tab2'>Login</a></li>
        </ul>
        <ul id="wpmm-user-tabs-content">
            <li class="active" id="wpmm-user-tab1">
                <div align="center">
                    <label for="cust_name">Customer first name</label><br>
                    <?php echo Chtml::textField('cust_name',$order['newuserform']['name']) ?><br>
                    <label for="cust_surname">Customer last name</label><br>
                    <?php echo Chtml::textField('cust_surname',$order['newuserform']['surname']) ?><br>
                    <label for="cust_phone" class="required">Contact phone <span class="required">*</span></label><br>
                    <?php echo Chtml::textField('cust_phone',$order['newuserform']['phone']) ?><br>
                    <label for="cust_c_mail" class="required">Contact e-mail <span class="required">*</span></label><br>
                    <?php echo Chtml::textField('cust_c_mail',$order['newuserform']['c_mail']) ?><br>
                </div>
            </li>
            <li id="wpmm-user-tab2">
                <?php echo Chtml::label('Login Name', 'login_name') ?><br>
                <?php echo Chtml::textField('login_name') ?><br>
                <?php echo Chtml::label('Login Password', 'login_pass') ?><br>
                <?php echo Chtml::passwordField('login_pass') ?><br>
                        <a id="wpmm-form-password-reset" href="#">Forgot your password ?</a>
                        <a class="wpmm-green-btn" id="wpmm-form-login" href="#">Login</a>
                    </li>
                </ul>
<?php } else { ?>
                <ul id="wpmm-user-tabs">
                    <li class="active"><a href='#wpmm-user-tab1'>User</a></li>
                </ul>
                <ul id="wpmm-user-tabs-content">
                    <li class="active" id="wpmm-user-tab1">
                        <div align="center">
                            <b>Welcome back:</b><br>
                            <span class="wpmm-cool-span">
                <?php echo Yii::app()->user->name ?>
                            </span>
                            <a class="wpmm-green-btn" id="wpmm-form-logout" href="#">Log Out</a>
                            <br>
                            <a class="wpmm-green-btn" id="wpmm-form-reorder" href="#">View previous orders</a>
                        </div>
                    </li>
                </ul>
            <?php } ?>
        </div>
        <?php if($enable_delivery) { ?>
        <div align="center">
            <?php
                $address_list = Helpers::GetAddressList();
                echo CHtml::label('Select Address', 'wpmm-address-dropdown');
                if(Yii::app()->mobileDetect->isMobile()){
                    echo CHtml::dropDownList('address', '', $address_list, array('empty' => 'Add new address...', 'data-native-menu'=>'false' ,'id' => 'wpmm-address-dropdown', 'options' => isset($cartform) ? array($cartform['address'] => array('selected' => true)) : array()));
                } else {
                    echo CHtml::dropDownList('address', '', $address_list, array('empty' => 'Add new address...', 'id' => 'wpmm-address-dropdown', 'options' => isset($cartform) ? array($cartform['address'] => array('selected' => true)) : array()));
                }
                ?>
            <?php
            if(!Yii::app()->mobileDetect->isMobile()){
                if (count($address_list)) {
                    echo '<a class="wpmm-orange-btn" id="wpmm-add-address" href="#">Edit</a>';
                } else {
                    echo '<a class="wpmm-green-btn" id="wpmm-add-address" href="#">Add</a>';
                }
            } else {
                if (count($address_list)) {
                    echo '<a class="wpmm-orange-btn" id="wpmm-add-address" href="#">Edit</a>';
                    echo '<a class="wpmm-green-btn wpmm-add-address-mobile" id="wpmm-add-address" href="#">Add</a>';
                } else {
                   echo '<a class="wpmm-green-btn" id="wpmm-add-address" href="#">Add</a>';
                }
            }
            ?>
        </div>
<?php if (!empty($cartform['delivery-type'])) { ?>
            <ul>
                <li><input type="radio" name="delivery-type" id="wpmm-delivery-delivery" value="Delivery" <?php if ($cartform['delivery-type'] == 'Delivery') echo 'checked="checked"'; ?>><label for="wpmm-delivery-delivery">Delivery</label></li>
                <li><input type="radio" name="delivery-type" id="wpmm-delivery-pickup" value="Pickup" <?php if ($cartform['delivery-type'] == 'Pickup') echo 'checked="checked"'; ?>><label for="wpmm-delivery-pickup">Pickup</label></li>
            </ul>
<?php } else { ?>
            <ul>
                <li><input type="radio" name="delivery-type" id="wpmm-delivery-delivery" value="Delivery" checked="checked" ><label for="wpmm-delivery-delivery">Delivery</label></li>
                <li><input type="radio" name="delivery-type" id="wpmm-delivery-pickup" value="Pickup"><label for="wpmm-delivery-pickup">Pickup</label></li>
            </ul>
        <?php } ?>
        <?php } else { ?>
            <ul>
                <li><input type="radio" name="delivery-type" id="wpmm-delivery-pickup" value="Pickup" checked="checked"><label for="wpmm-delivery-pickup">Pickup</label></li>
            </ul>
        <?php } ?>
        <hr class="wpmm-form-hr">
    </div>
    <div id="wpmm-form-time">
        <div class="wpmm-form-header">
            <p>Preferred time</p>
        </div>
        <?php
        if (isset($errors['time'])) {
            Helpers::renderErrors($errors['time']);
        }
        ?>
        <hr class="wpmm-form-hr">
<?php if (!empty($cartform['delivery-time'])) { ?>
            <ul>
                <li>
                    <input type="radio" id="wpmm-asap" name="delivery-time" value="asap" <?php if ($cartform['delivery-time'] == 'asap') echo 'checked="checked"'; ?>>
                    <label for="wpmm-asap">ASAP</label>
                </li>
                <li>
                    <input type="radio" id="wpmm-today" name="delivery-time" value="today" <?php if ($cartform['delivery-time'] == 'today') echo 'checked="checked"'; ?>>
                    <label for="wpmm-today">Today</label>
                </li>
                <li>
                    <input type="radio" id="wpmm-date" name="delivery-time" value="date" <?php if ($cartform['delivery-time'] == 'date') echo 'checked="checked"'; ?>>
                    <label for="wpmm-date">Choose date:</label>
                    <?php if (!empty($cartform['order-time'])) { ?>
                        <input type="text" id="wpmm-form-today-time" name="order-time" value="<?php
                               if ($cartform['delivery-time'] == 'today')
                                   echo $cartform['order-time'];
                               ?>" <?php if ($cartform['delivery-time'] == 'today') echo 'style="display:block"'; ?>>
                        <input type="text" id="wpmm-form-date-time" name="order-time" value="<?php
                        if ($cartform['delivery-time'] == 'date')
                            echo $cartform['order-time'];
                        ?>" <?php if ($cartform['delivery-time'] == 'date') echo 'style="display:block"'; ?>>
            <?php } else { ?>
                        <input type="text" id="wpmm-form-today-time" name="order-time" <?php if ($cartform['delivery-time'] == 'today') echo 'style="display:block"'; ?>>
                        <input type="text" id="wpmm-form-date-time" name="order-time" <?php if ($cartform['delivery-time'] == 'date') echo 'style="display:block"'; ?>>
    <?php } ?>
                </li>
            </ul>
<?php } else { ?>
            <ul>
                <li>
                    <input type="radio" id="wpmm-asap" name="delivery-time" value="asap" checked="checked">
                    <label for="wpmm-asap">ASAP</label>
                </li>
                <li>
                    <input type="radio" id="wpmm-today" name="delivery-time" value="today">
                    <label for="wpmm-today">Today</label>
                </li>
                <li>
                    <input type="radio" id="wpmm-date" name="delivery-time" value="date">
                    <label for="wpmm-date">Choose date:</label>
                    <input type="text" id="wpmm-form-today-time" name="order-time">
                    <input type="text" id="wpmm-form-date-time" name="order-time">
                </li>
            </ul>
<?php } ?>
        <hr class="wpmm-form-hr">
    </div>
    <div id="wpmm-form-payment">
        <div class="wpmm-form-header">
            <p>Payment type</p>
        </div>
        <hr class="wpmm-form-hr">
        <?php
        if (isset($errors['payment'])) {
            Helpers::renderErrors($errors['payment']);
        }
        ?>
        <?php if ($enable_payments == 0) $cartform['payment_type'] = 'cash'; ?>
            <?php if (!empty($cartform['payment_type'])) { ?>
            <ul>
    <?php if ($enable_payments) { ?> <li><input type="radio" name="payment-type" id="wpmm-credit-card"  value="credit_card" <?php if ($cartform['payment_type'] == 'credit_card') echo 'checked="checked"'; ?>><label for="wpmm-credit-card">Credit card</label></li> <?php } ?>
                <li><input type="radio" name="payment-type" id="wpmm-cash" value="cash" <?php if ($cartform['payment_type'] == 'cash') echo 'checked="checked"'; ?>><label for="wpmm-cash">Cash</label></li>
            </ul>
            <?php } else { ?>
            <ul>
    <?php if ($enable_payments) { ?>
                    <li>
                        <input type="radio" name="payment-type" id="wpmm-credit-card" value="credit_card" checked="checked">
                        <label for="wpmm-credit-card">Credit card</label>
                    </li>
            <?php } ?>
                <li>
                    <input type="radio" name="payment-type" id="wpmm-cash" value="cash">
                    <label for="wpmm-cash">Cash</label>
                </li>
            </ul>
<?php } ?>
        <hr class="wpmm-form-hr">
    </div>
    <div id="wpmm-form-tip">
        <div class="wpmm-form-header">
            <p>Tip amount</p>
        </div>
        <hr class="wpmm-form-hr">
<?php
if (isset($errors['tips'])) {
    Helpers::renderErrors($errors['tips']);
}
?>
<?php if (!empty($cartform['payment-tip-value'])) { ?>
            <ul>
                <li>
                    <input type="radio" name="payment-tip-type" id="wpmm-tip-percent" value="percent"
    <?php if ($cartform['payment-tip-type'] === 'percent') echo 'checked="checked"'; ?>>
                    <label for="wpmm-tip-percent">Percentage, %</label>
                </li>
                <li>
                    <input type="radio" name="payment-tip-type" id="wpmm-tip-amount" value="amount"
            <?php if ($cartform['payment-tip-type'] === 'amount') echo 'checked="checked"'; ?>>
                    <label for="wpmm-tip-amount">Specific amount, $</label>
                </li>
                <li style="padding-left: 15px;"><input type="text" id="wpmm-tip-value" name="payment-tip-value" value="<?php echo $cartform['payment-tip-value'] ?>">
                </li>
            </ul>
<?php } else { ?>
            <ul>
                <li>
                    <input type="radio" id="wpmm-tip-percent" name="payment-tip-type" value="percent" checked="checked">
                    <label for="wpmm-tip-percent">Percentage, %</label>
                </li>
                <li>
                    <input type="radio" id="wpmm-tip-amount" name="payment-tip-type" value="amount">
                    <label for="wpmm-tip-amount">Specific amount, $</label>
                </li>
                <li style="padding-left: 15px;">
                    <input type="text" id="wpmm-tip-value" name="payment-tip-value" value="0">
                </li>
            </ul>
<?php } ?>
        <hr class="wpmm-form-hr">
    </div>
</div>
<div id="wpmm-cart-control" align="center">
    <a href="#" id="wpmm-form-checkout">Checkout</a>
    <a href="#" id="wpmm-form-delete" title="Delete order">Clear</a>
</div>
<?php $this->endWidget() ?>
<script>
    var mobile = jQuery('html').hasClass('ui-mobile');
    jQuery(document).on('pageinit', function(){
        fixTimeInputs();
    });
    function fixTimeInputs() {
        if (jQuery('#wpmm-date:checked').length) {
            jQuery('#wpmm-form-date-time').parent().show();
        }
        if (jQuery('#wpmm-today:checked').length) {
            jQuery('#wpmm-form-today-time').parent().show();
        }
    }
    function fixHeight() {
        if (jQuery("#wpmm-form").hasClass("inside-left") || jQuery("#wpmm-form").hasClass("inside-right")) {
            var effect = '<?php echo MMSettingsForm::getParam('tabs_effect') ?>';
            jQuery("#wpmm").css('minHeight', jQuery('#wpmm-form').outerHeight());
        }
    }
    jQuery('#wpmm-form-delivery-info').tooltip({
        content: function() {
            return $(this).attr('title');
        }
    });
    fixHeight();
    jQuery('.wpmm-form-item-edit').on('click', function() {
        var details = jQuery('#wpmm-item-details');
        var id = jQuery(this).attr('data-id');
        var key = jQuery(this).parents('.wpmm-form-item-table').attr('data-key');
        if (mobile) {
            var itemdetails = {'mobile': true, 'key': key};
        } else {
            var itemdetails = {'key': key};
        }
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'itemdetails': itemdetails,
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            beforeSend: function() {
                details.hide();
                jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();

            },
            error: function() {
                jQuery('#wpmm-item-loading').css({'background-color': 'red'});
                jQuery('#wpmm-item-loading').hide(50);
            },
            success: function(data) {
                if (mobile) {
                    jQuery('#wpmm-form, #wpmm .ui-collapsible-set').hide();
                }
                details.show();
                if (data) {
                    jQuery('#wpmm-item-loading').hide();
                    details.html(data);
                    details.attr('data-id', id);
                    if (mobile) {
                        jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                    } else {
                        details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                    }
                    refresh_item_total();
                }
            }
        });
        return false;
    });

    jQuery('.wpmm-form-item-delete').on('click', function() {
        var order_item_key = jQuery(this).parents('.wpmm-form-item-table').attr('data-key');
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'deleteitem': order_item_key,
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            success: function(data) {
                if (data) {
                    refresh_order();
                }
            }
        });
        return false;
    });
    jQuery('#wpmm-form-login').on('click', function() {
        var data = {
            'username': jQuery('#login_name').val(),
            'password': jQuery('#login_pass').val()
        };
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'login_customer': data,
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            success: function(data) {
                if (data != 0) {
                    data = data.slice(0, -1);
                    alert(data);
                }
                refresh_order();
            }
        });
        return false;
    });

    jQuery('#wpmm-form-logout').on('click', function() {
        if (confirm('Your current order will be deleated! Continue ?')) {
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'logout_customer': 'data',
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    refresh_order(false);
                }
            });
            return false;
        } else {
            return false;
        }
    });

    jQuery('#wpmm-user-tabs a').on('click', function() {
        var attribute_id = jQuery(this).attr('href');
        jQuery('#wpmm-user-tabs a').each(function() {
            jQuery(this).parent().removeClass();
        });
        jQuery(this).parent().attr('class', 'active');
        jQuery('#wpmm-user-tabs-content .active').removeClass();
        jQuery(attribute_id).attr('class', 'active');
        fixHeight();
        if (!mobile) {
            jQuery("#wpmm-form-container-scroll").mCustomScrollbar('update');
        }
        return false;
    });
    jQuery('#wpmm-form-password-reset').on('click', function() {
        var details = jQuery('#wpmm-item-details');
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'forgot_pass': 'notemailkey',
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            beforeSend: function() {
                details.hide();
                jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();
            },
            error: function() {
                jQuery('#wpmm-item-loading').css({'background-color': 'red'});
                jQuery('#wpmm-item-loading').hide(50);
            },
            success: function(data) {
                if (data) {
                    jQuery('#wpmm-item-loading').hide();
                    details.show();
                    details.html(data);
                    if (mobile) {
                        jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                    } else {
                        details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                    }
                }
            }
        });
        return false;
    });

    jQuery('#wpmm-address-dropdown').change(function() {
    <?php if(!Yii::app()->mobileDetect->isMobile()){ ?>
        var ddlValue = jQuery("#wpmm-address-dropdown option:selected").val();
        jQuery('#wpmm-add-address').remove();
        if (ddlValue) {
            jQuery(this).after(function() {
                return '<a class="wpmm-orange-btn" id="wpmm-add-address" href="#">Edit</a>';
            });
        } else {
            jQuery(this).after(function() {
                return '<a class="wpmm-green-btn" id="wpmm-add-address" href="#">Add</a>';
            });
        }
        <?php } ?>
        AddressControlsInit();
    });

    jQuery('#wpmm-form-delivery-info').click(function() {
        var details = jQuery('#wpmm-item-details');
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'vendor_info': <?php if(!empty($order['location'])) echo $order['location']; else  echo Helpers::getFirstLocation(); ?>,
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            beforeSend: function() {
                details.hide();
                jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();
            },
            error: function() {
                jQuery('#wpmm-item-loading').css({'background-color': 'red'});
                jQuery('#wpmm-item-loading').hide(50);
            },
            success: function(data) {
                details.show();
                if (data) {
                    jQuery('#wpmm-item-loading').hide();
                    details.html(data);
                    if (mobile) {
                        jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                    } else {
                        details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                    }
                }
            }
        });
        return false;
    });

    jQuery("#wpmm-form-reorder").on('click', function() {
        var details = jQuery('#wpmm-item-details');
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'reorder': true,
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            beforeSend: function() {
                details.hide();
                jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();
            },
            success: function(data) {
                if (data) {
                    jQuery('#wpmm-item-loading').hide();
                        details.show();
                        details.html(data);
                        if (mobile) {
                            jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                        } else {
                            details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                        }
                }
            }
        });
        return false;
    });

    jQuery("#wpmm-form-checkout").on('click', function() {
        var params = {};
        var details = jQuery('#wpmm-item-details');
        jQuery.each(jQuery('#wpmm-form').children().serializeArray(), function(index, value) {
            if (value.name == 'order-time') {
                if (jQuery('#wpmm-today').is(':checked')) {
                    params[value.name] = jQuery('#wpmm-form-today-time').val();
                    jQuery('#wpmm-form-date-time').val('');
                } else if (jQuery('#wpmm-date').is(':checked')) {
                    params[value.name] = jQuery('#wpmm-form-date-time').val();
                    jQuery('#wpmm-form-today-time').val('');
                }
            } else {
                params[value.name] = value.value;
            }
        });
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'submitorder': true,
                'data': params,
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            beforeSend: function() {
                details.hide();
                jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();
            },
            success: function(data) {
                if (data) {
                    jQuery('#wpmm-item-loading').hide();
                    if (data !== 'There where errors') {
                        details.show();
                        details.html(data);
                        if (mobile) {
                            jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                        } else {
                            details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                        }
                    }
                    refresh_order();
                }
            }
        });
        return false;
    });

    jQuery("#wpmm-form-delete").on('click', function() {
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: {
                'action': 'front',
                'deleteorder': true,
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "json",
            success: function(data) {
                if (data) {
                    refresh_order(false);
                }
            }
        });
        return false;
    });
    jQuery('.wpmm-form-item-table').on('mouseenter', function() {
        jQuery(this).find('.wpmm-form-item-buttons').show();
    }).on('mouseleave', function() {
        jQuery(this).find('.wpmm-form-item-buttons').hide();
    }).on('click', function() {
        if (!mobile) {
            jQuery(this).next('.wpmm-form-item-extra').slideToggle('normal', function() {
                fixHeight();
                jQuery("#wpmm-form-container-scroll").mCustomScrollbar('update');
            });
        }
    });
    jQuery('.wpmm-forn-dev-toggle').on('click', function() {
        if (!mobile) {
            jQuery('.wpmm-form-delivery-info').slideToggle('normal', function() {
                fixHeight();
                jQuery("#wpmm-form-container-scroll").mCustomScrollbar('update');
            });
        }
        return false;
    });
    jQuery('#wpmm-form-time input[type=radio]').on('click', function() {
        if (jQuery(this).attr('id') === 'wpmm-asap') {
            jQuery('#wpmm-form-today-time, #wpmm-form-date-time').hide();
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'saveordertime': {'datetime': ''},
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data) {
                        refresh_order();
                    }
                }
            });
        }
        else if (jQuery(this).attr('id') === 'wpmm-today') {
            jQuery('#wpmm-form-today-time').show();
            jQuery('#wpmm-form-date-time').hide();
        }
        else if (jQuery(this).attr('id') === 'wpmm-date') {
            jQuery('#wpmm-form-today-time').hide();
            jQuery('#wpmm-form-date-time').show();
        }
        if (mobile) {
            jQuery('#wpmm-form-time div.ui-input-text').each(function(){
                if (jQuery(this).height() < 1) {
                    jQuery(this).hide();
                } else {
                    jQuery(this).show();
                }
            });
        } else {
            fixHeight();
            jQuery("#wpmm-form-container-scroll").mCustomScrollbar('update');
        }
    });
    jQuery('#wpmm-form-today-time').mobiscroll().time({
        theme: 'wp light',
        display: 'modal',
        mode: 'mixed',
        stepMinute: 15,
        onSelect: function(datetime) {
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'saveordertime': {'datetime': datetime},
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data) {
                        refresh_order();
                    }
                }
            });
        }
    });
    var now = new Date();
    var max = new Date();
    now.setDate(now.getDate() + 1);
    max.setDate(now.getDate() + 10);
    jQuery('#wpmm-form-date-time').mobiscroll().datetime({
        minDate: new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(), now.getUTCHours(), now.getUTCMinutes()),
        maxDate: new Date(max.getUTCFullYear(), max.getUTCMonth(), max.getUTCDate()),
        stepMinute: 15,
        theme: 'wp light',
        display: 'modal',
        animate: 'fade',
        mode: 'mixed',
        onSelect: function(datetime) {
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'saveordertime': {'datetime': datetime},
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data) {
                        refresh_order();
                    }
                }
            });
        }
    });
    jQuery('#wpmm-form-delivery input[type=radio]').on('click', function() {
        refresh_order();
    });
    jQuery('#wpmm-form-tip input[type=radio]').on('click', function() {
        var value = jQuery('#wpmm-tip-value').val();
        if (value != 0) {
            setTimeout(function() {
                save_tip();
            }, 1500);
        }
    });
    jQuery('#wpmm-location').on('change', function() {
        var loc = $(this).val();
        var details = jQuery('#wpmm-item-details');
        jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'changelocation': loc,
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "html",
                beforeSend: function() {
                    details.hide();
                    jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();
                },
                success: function(data) {
                        location.reload();
                }
            });
    });
    jQuery('#wpmm-tip-value').on('change', function(event) {
        save_tip();
    }).keyup(function(event) {
        setTimeout(function() {
            save_tip();
        }, <?php if(Yii::app()->mobileDetect->isMobile()) echo '5000'; else echo '1500'; ?>);
    });
    function save_tip() {
        switch (jQuery('#wpmm-form-tip input[type=radio]:checked').attr('id')) {
            case 'wpmm-tip-percent':
                var type = 'percent';
                break;
            case 'wpmm-tip-amount':
                var type = 'amount';
                break;
        }
        var value = jQuery('#wpmm-tip-value').val();
        if (typeof value !== 'undefined' && value !== false) {
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'savetip': {'type': type, 'value': value},
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    if (data) {
                        refresh_order();
                    }
                }
            });
        }
    }
</script>
