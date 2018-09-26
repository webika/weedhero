<!DOCTYPE html>
<html lang="en">
    <?php
    $options = MMSettingsForm::getParams();
    ?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <title><?php echo $options['app_name']; ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::app()->getBaseUrl(true) ?>/images/favicon.png" type="image/png">
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    <?php if(!empty($options['header_text'])){
        echo $options['header_text'];
    } ?>
    <style>
        <?php
        echo 'body {';
            echo 'background-color: ' . $options['out_color'] . ';';
            if ($options['out_texture'] == '1')
                echo 'background-image: url(' . Yii::app()->baseUrl . "/" . MM_UPLOADS_URL . "/" . $options['body_texture'] . ');';
        echo "}";
        echo '#wpmm {';
            echo 'width: 100%;';
        echo '}';
        if ($options['enable_logo']) {
            echo '#mm-logo-wrap {
                width: ' . $options['in_width'] . 'px;';
                if ($options['in_position'] == 'center')
                    echo 'margin: 0 auto;';
                else
                    echo 'float: ' . $options['in_position'] . ';';
            echo '}';
            echo '#mm-logo {';
                if ($options['logo_position'] == 'center')
                    echo 'text-align: center;';
                else
                    echo 'text-align: ' . $options['logo_position'] . ';';
            echo '}';
        }
        if ($options['enable_footer']) {
            echo '#mm-footer {';
                if ($options['in_width']) {
                    echo 'width: ' . $options['in_width'] . 'px;';
                    if ($options['in_position'] == 'center')
                        echo 'margin-left: auto; margin-right: auto;';
                    else
                        echo 'float: ' . $options['in_position'] . ';';
                }
                echo 'padding: 10px;';
            echo '}';
        }
        ?>
    </style>
</head>

<body>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <div id="wpmm-mobile-header" data-role="header" data-position="fixed" data-tap-toggle="false">
        <h1 style="text-align:left; margin-left:10px;"><?php echo $options['vendor_name'] ?></h1>
        <a href="#" data-icon="check" class="ui-btn-right">
            <?php
                if(Yii::app()->session['mm_order']['items']){
                    echo '$ '.Helpers::getOrderTotal();
                } else {
                    echo '$ '.number_format((float) 0, 2);
                }
            ?>
        </a>
    </div>
    <div id="wpmm">
        <?php echo $content ?>
        <div id="wpmm-item-details"></div>
        <div id="wpmm-item-loading"></div>
    </div>
    <div id="wpmm-footer" data-role="footer" data-position="fixed" data-tap-toggle="false">
        <div data-role="navbar"  data-iconpos="top">
            <ul>
                <li><a href="#" class="ui-btn-active" data-icon="bars">Menu</a></li>
                <li><a href="#" data-icon="check">Checkout</a></li>
            </ul>
        </div>
    </div>
    <script>
        jQuery(function(){
            AddressControlsInit();
        });
        // ПЕРЕМИКАННЯ "СТОРІНОК"
        jQuery("#wpmm-footer li").on('click', function() {
            if (jQuery(this).hasClass('ui-block-a')) {
                jQuery('#wpmm-form').hide();
                jQuery('#wpmm .ui-collapsible-set').show();
            } else if (jQuery(this).hasClass('ui-block-b')) {
                jQuery('#wpmm .ui-collapsible-set').hide();
                jQuery('#wpmm-form').show();
            }
        });

        jQuery("#wpmm-mobile-header a").on('click', function() {
            jQuery("#wpmm-footer ul > li").eq(1).trigger('click');
            jQuery("#wpmm-footer ul > li").eq(0).children().removeClass('ui-btn-active');
            jQuery("#wpmm-footer ul > li").eq(1).children().removeClass('ui-btn-active');
            jQuery("#wpmm-footer ul > li").eq(1).children().addClass('ui-btn-active');
        });

        // КЛІК ПО ПРОДУКТУ
        jQuery('.wpmm-item').on('click', function() {
            var details = jQuery('#wpmm-item-details');
            var id = jQuery(this).attr('data-id');
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'itemdetails': {'id': id, 'mobile': true},
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
                    jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                    if (data) {
                        jQuery('#wpmm-item-loading').hide();
                        details.html(data);
                        details.attr('data-id', id);
                        jQuery(document).scrollTop(0);
                    }
                }
            });
            return false;
        });

        function get_order_total_summ(){
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'get_summ': true,
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "html",
                success: function(data) {
                    if (data) {
                       jQuery("#wpmm-mobile-header a span").find(':first-child').text(data);
                    }
                }
            });
        }

        function refresh_order(form) {
            if (typeof form === 'undefined' && form !== false) {
                form = true;
            }
            var params = {};
            if (form) {
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
            }
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'refreshorder': true,
                    'data': params,
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "html",
                beforeSend: function() {
                    jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();
                },
                success: function(data) {
                    if (data) {
                        jQuery('#wpmm-form').html(data).trigger('create');
                        jQuery('#wpmm-item-loading').hide();
                        get_order_total_summ();
                        AddressControlsInit();
                        var errors = document.getElementsByClassName('wpmm-form-error');
                        if (errors.length) {
                           errors[0].parentNode.scrollIntoView(true);
                        }
                        fixTimeInputs();
                    }
                }
            });
        }

        function AddressControlsInit() {
            jQuery("#wpmm-add-address").on('click', function() {
                var details = jQuery('#wpmm-item-details');
                var ddlValue = jQuery("#wpmm-address-dropdown option:selected").val();
                var id = 0;
                if (ddlValue) {
                    id = ddlValue;
                }
                jQuery.ajax({
                    url: '<?php echo MM_AJAX_URL ?>',
                    data: {
                        'action': 'front',
                        'add_address': id,
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
                            jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                            details.html(data);
                        }
                        details.show();
                    }
                });
                return false;
            });
            jQuery(".wpmm-add-address-mobile").on('click', function() {
                var details = jQuery('#wpmm-item-details');
                var ddlValue = '';
                var id = 0;
                if (ddlValue) {
                    id = ddlValue;
                }
                jQuery.ajax({
                    url: '<?php echo MM_AJAX_URL ?>',
                    data: {
                        'action': 'front',
                        'add_address': id,
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
                            jQuery('#wpmm .ui-collapsible-set, #wpmm-form, #wpmm-footer').hide();
                            details.html(data);
                        }
                        details.show();
                    }
                });
                return false;
            });
        }

        function continueOrder(total) {
            var details = jQuery('#wpmm-item-details');
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'continueorder': true,
                    'orderammount': total,
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "html",
                beforeSend: function() {
                    details.hide();
                    jQuery('#wpmm-item-loading').css({'top': '50%', 'left': '50%', 'margin-left': '-' + 24 + 'px'}).show();
                },
                success: function(data) {
                    jQuery('#wpmm-item-loading').hide();
                    if (data !== 'There where errors') {
                        details.html(data);
                        details.show();
                    } else {
                        refresh_order();
                    }
                }
            });
        }
        function checkoutOrder() {
            var details = jQuery('#wpmm-item-details');
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'checkout': true,
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "html",
                success: function(data) {
                    jQuery('#wpmm-item-loading').hide();
                    details.hide();
                    if (data != 'There where errors') {
                        details.html(data);
                        refresh_order(false);
                        details.show();
                    } else {
                        refresh_order();
                    }
                }
            });
        }
        function checkoutOrderCard() {
            var details = jQuery('#wpmm-item-details');
            var form = {};
            form['card_number'] = jQuery('#card_number').val();
            form['exp_date'] = jQuery('#exp_date').val();
            form['orderamount'] = jQuery('#wpmm-orderamount').val();
            form['payment_vendor'] = jQuery('input[name=payment_vendor]:checked').val();
            form['p_first_name'] = jQuery('#p_first_name').val();
            form['p_last_name'] = jQuery('#p_last_name').val();
            form['p_billing_address'] = jQuery('#p_billing_address').val();
            form['p_billing_country'] = jQuery('#p_billing_country').val();
            form['p_billing_state'] = jQuery('#p_billing_state').val();
            form['p_billing_zip'] = jQuery('#p_billing_zip').val();
            form['p_card_number'] = jQuery('#p_card_number').val();
            form['p_expiration_month'] = jQuery('#p_expiration_month').val();
            form['p_expiration_year'] = jQuery('#p_expiration_year').val();
            form['p_cv_code'] = jQuery('#p_cv_code').val();
            form['p_credit_type'] = jQuery('#p_credit_type').val();
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'checkout': true,
                    'card_form': form,
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "html",
                success: function(data) {
                    jQuery('#wpmm-item-loading').hide();
                    details.hide();
                    if (data) {
                        details.html(data);
                        details.show();
                        refresh_order();
                    }
                }
            });
        }

        /* ДОПОМІЖНІ ФУНКЦІЇ */
        function refresh_item_total() {
            var price = parseFloat(jQuery('#wpmm-item-details-price span').attr('data-price'));
            var quantity = parseInt(jQuery('#wpmm-item-details-quantity').text());
            jQuery("#wpmm-item-details-attributes li input:checked").each(function() {
                 if(jQuery(this).attr('data-size') == 3 || jQuery(this).attr('data-size') == 2){
                    price += parseFloat(jQuery(this).attr('data-price')/2);
                } else {
                    price += parseFloat(jQuery(this).attr('data-price'));
                }
            });
            jQuery('#wpmm-item-details-price span').text((price * quantity).toFixed(2));
            jQuery('#wpmm-item-details-price').animate({
                'font-size': '24px'
            }, 150).animate({
                'font-size': '18px'
            }, 150);
        }
        <?php if(isset($_GET['wpmm-id'])) {?>
         jQuery(document).ready(function() {
             setTimeout(function() {
                    $( ".wpmm-item[data-id=<?php echo $_GET['wpmm-id']; ?>]").first().click();
                }, 5000);
         });
         <?php } ?>
    </script>
</body>
</html>