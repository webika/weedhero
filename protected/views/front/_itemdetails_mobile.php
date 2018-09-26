<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name"><?php echo $item->name ?></p>
    <p id="wpmm-item-details-price">$<span data-price="<?php echo $item->price ?>"><?php echo $item->price ?></span></p>
</div>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-left">
        <?php if ($item->image) : ?>
            <div id="wpmm-item-details-image" style="background: url(<?php echo MM_UPLOADS_URL . '/thumb_' . $item->image ?>);"></div>
        <?php endif ?>
    </div>
    <div id="wpmm-item-details-content-right" class="<?php echo $item->image ? '' : ' noimage' ?>">
        <p id="wpmm-item-details-description"><?php echo $item->description ?>
            <?php if($item->type == 1){ ?>
            <div style="text-align: right">
            <?php
                echo CHtml::label('Pizza cut', 'wpmm-pizza-cut',array('style'=>'float: none')).'<br>';
                if(Yii::app()->mobileDetect->isMobile()){
                    echo CHtml::dropDownList('wpmm-pizza-cut', $order['items'][$item_key]['cut'], Helpers::getPizzaCut(),array('data-native-menu'=>'false'));
                } else {
                    echo CHtml::dropDownList('wpmm-pizza-cut', $order['items'][$item_key]['cut'], Helpers::getPizzaCut());
                } ?>
                </div>
           <?php } ?>
        </p>
        <?php if(MMSettingsForm::getParam('enable_social')){
            $params = parse_url("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $params1=$_GET;
            $params1['wpmm-id'] = $item->id;
            $paramString = $params['scheme'].'://'.$params['host'];
            if(!empty($params['path'])) $paramString.=rtrim($params['path'],'/');
            $paramString .= '/?'.http_build_query($params1);
            $social_link= $paramString;
        ?>
        <div class="wpmm-social">
            <div class="fb-like" data-href="<?php echo $social_link; ?>" data-send="false" data-layout="button_count" data-width="45" data-show-faces="false"></div>
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" data-url="<?php echo $social_link; ?>" data-count="none" data-text="I like <?php echo $item->name ?>">Tweet</a>
        </div>
        <?php } ?>
    </div>
</div>
<div id="wpmm-item-details-more">
    <ul id="wpmm-item-details-attributes">
        <li id="wpmm-item-details-instructions" class="active" style="display:none">
            <h2 class="wpmm-color-text wpmm-details-header">Special instructions</h2>
            <textarea placeholder="Additional comments about your order..."><?php if (isset($item_key))
                    echo $order['items'][$item_key]['instructions'] ?></textarea>
        </li>
        <?php foreach ($item->params as $param) { ?>
        <li id="wpmm-item-details-group-<?php echo $param->id ?>" data-id="<?php echo $param->id ?>">
            <?php echo '<h2 class="wpmm-color-text wpmm-details-header">'. $param->name .'</h2>'; ?>
            <?php if ($param->type === '0') { // checkbox
                foreach ($param->attribs as $key => $attribute) { ?>
                    <input type="checkbox" name="wpmm-attribute-<?php echo $attribute->id ?>" value="<?php echo $attribute->id ?>" data-price="<?php echo $attribute->price ?>"
                        <?php
                            if (isset($item_key)) {
                                if (is_array($order['items'][$item_key]['attributes']) &&
                                    array_key_exists($attribute->id, $order['items'][$item_key]['attributes'])) {
                                    echo 'checked="checked"';
                                }
                            } else {
                                if ($attribute->checked_id) {
                                    echo 'checked="checked"';
                                }
                            }
                        ?>/>
                    <span for="wpmm-attribute-<?php echo $attribute->id ?>"><?php echo $attribute->name ?></span>
                    <span class="wpmmm-attribute-extra-price">
                        <?php
                            if (substr($attribute->price, 0, 1) === '-')
                                echo '- $' . substr($attribute->price, 1);
                            else
                                echo '+ $' . $attribute->price
                        ?>
                    </span>
                    <br>
                <?php }
             } elseif ($param->type === '1') { // radio buttons
                foreach ($param->attribs as $key => $attribute) { ?>
                    <input type="radio" name="wpmm-group-<?php echo $param->id ?>" value="<?php echo $attribute->id ?>" data-price="<?php echo $attribute->price ?>"
                        <?php
                        if (isset($item_key) && is_array($order['items'][$item_key]['attributes'])) {
                            if (is_array($order['items'][$item_key]['attributes']) &&
                                array_key_exists($attribute->id, $order['items'][$item_key]['attributes'])) {
                                echo 'checked="checked"';
                            }
                        } else {
                            if ($attribute->checked_id) {
                                echo 'checked="checked"';
                            }
                        }
                         ?>/>
                    <span><?php echo $attribute->name ?></span>
                    <span class="wpmmm-attribute-extra-price">
                        <?php
                            if (substr($attribute->price, 0, 1) == '-')
                                echo '- $' . substr($attribute->price, 1);
                            else
                                echo '+ $' . $attribute->price
                        ?>
                    </span>
                    <br>
                <?php }
            } elseif($param->type === '2') { ?>
                    <?php foreach ($param->attribs as $key => $attribute) {
                        $active = array();
                        ?>
                    <input type="checkbox" name="wpmm-attribute-<?php echo $attribute->id ?>" value="<?php echo $attribute->id ?>" data-price="<?php echo $attribute->price ?>"
                        <?php
                            if (isset($item_key)) {
                                if (is_array($order['items'][$item_key]['attributes']) &&
                                    array_key_exists($attribute->id, $order['items'][$item_key]['attributes'])) {
                                    echo 'checked="checked"';
                                    $active[$order['items'][$item_key]['attributes'][$attribute->id]['size']] = 'active1';
                                }
                            } else {
                                if ($attribute->checked_id) {
                                    echo 'checked="checked"';
                                }
                                $active[1] = 'active1';
                            }
                        ?> data-size="<?php
                        if (isset($item_key)) {
                                if (is_array($order['items'][$item_key]['attributes']) &&
                                    array_key_exists($attribute->id, $order['items'][$item_key]['attributes'])) {
                                    echo $order['items'][$item_key]['attributes'][$attribute->id]['size'];
                                }
                            } else {
                                echo '1';
                            }
                        ?>"/>
                    <span for="wpmm-attribute-<?php echo $attribute->id ?>"><?php echo $attribute->name ?></span>
                    <span class="wpmmm-attribute-extra-price">
                        <?php
                            if (substr($attribute->price, 0, 1) === '-')
                                echo '- $' . substr($attribute->price, 1);
                            else
                                echo '+ $' . $attribute->price
                        ?>
                    </span>
                    <span <?php  if (isset($item_key) && $order['items'][$item_key]['cut'] != 1) echo 'style="display:none"'; ?>  class="wpmm-pizza-control" data-id="wpmm-attribute-<?php echo $attribute->id ?>"><div class="wpmm-circ-left <?php echo $active[3]; ?>"></div><div class="wpmm-circ-full <?php echo $active[1]; ?>"></div><div class="wpmm-circ-right <?php echo $active[2]; ?>"></div></span>
                    <br>
                <?php } ?>
                    <?php } ?>
        </li>
        <?php } ?>
    </ul>
</div>
<div id="wpmm-item-details-footer">
    <a href="#" id="wpmm-item-details-close">Close</a>
    <a href="#" id="wpmm-item-details-add"<?php echo isset($item_key) ? ' data-key="'.$item_key.'">Update' : '>Add' ?></a>
    <div id="wpmm-item-details-plus"></div>
    <div id="wpmm-item-details-quantity"><?php echo isset($item_key) ? $order['items'][$item_key]['quantity'] : 1 ?></div>
    <div id="wpmm-item-details-minus"></div>
</div>

<script>
    jQuery(function(){
        jQuery('#wpmm-item-details-instructions').css('min-height', jQuery('#wpmm-item-details-groups').height()+'px');
        FB.XFBML.parse();
        twttr.widgets.load();
    });
    jQuery('#wpmm-item-details-close').on('click', function() {
        jQuery('#wpmm-item-details').hide();
        jQuery('#wpmm-footer').show();
        if (jQuery("#wpmm-footer a.ui-btn-active").parent('li').hasClass('ui-block-a')) {
            jQuery('#wpmm-form').hide();
            jQuery('#wpmm .ui-collapsible-set').show();
        } else if (jQuery("#wpmm-footer a.ui-btn-active").parent('li').hasClass('ui-block-b')) {
            jQuery('#wpmm .ui-collapsible-set').hide();
            jQuery('#wpmm-form').show();
        }
        return false;
    });
    jQuery('#wpmm-pizza-cut').on('change',function(){
        if($(this).val() == 0){
            $('.wpmm-pizza-control').children().removeClass('active1');
            $('.wpmm-pizza-control .wpmm-circ-full').addClass('active1');
            $('input[type=checkbox]').each(function(){
                var attr = $(this).attr('data-size');
                if (typeof attr !== 'undefined' && attr !== false) {
                    $(this).attr('data-size','1');
                }
            });
            $('.wpmm-pizza-control').hide();
            refresh_item_total();
        } else {
            $('.wpmm-pizza-control').show();
        }
    });
    jQuery('.wpmm-circ-left, .wpmm-circ-full, .wpmm-circ-right').on('click', function() {
        var control = $(this);
        var inputc = $('#wpmm-item-details-attributes input[name='+control.parent().attr('data-id')+']');
        control.parent().children().removeClass('active1');
        control.addClass('active1');
        if(control.hasClass('wpmm-circ-left')){
            inputc.attr('data-size','3');
        } else if (control.hasClass('wpmm-circ-right')){
            inputc.attr('data-size', '2');
        } else {
            inputc.attr('data-size', '1');
        }
            inputc.attr('checked', true);
            inputc.trigger('click');
            inputc.attr('checked', true);
    });
    jQuery('#wpmm-item-details-groups a').on('click', function() {
        var attribute_id = jQuery(this).attr('href');
        jQuery('#wpmm-item-details-groups a').each(function(){
            jQuery(this).parent().removeClass();
        });
        jQuery(this).parent().attr('class', 'active');
        jQuery('#wpmm-item-details-attributes .active').removeClass();
        jQuery(attribute_id).attr('class', 'active').css('min-height', jQuery('#wpmm-item-details-groups').height()+'px');
        return false;
    });
    jQuery('#wpmm-item-details-plus, #wpmm-item-details-minus').on('click', function() {
        var id = jQuery(this).parents('#wpmm-item-details').attr('data-id');
        var quantity = parseInt(jQuery('#wpmm-item-details-quantity').text());
        switch (jQuery(this).attr('id')) {
            case 'wpmm-item-details-plus' :
                quantity++;
            break;
            case 'wpmm-item-details-minus' :
                if (jQuery('#wpmm-item-details-quantity').text() > 1) {
                    quantity--;
                }
                else
                    quantity = 1;
            break;
        }
        jQuery('#wpmm-item-details-quantity').text(quantity);
        refresh_item_total();
    });
    jQuery('#wpmm-item-details-add').on('click', function() {
        var key = jQuery(this).attr('data-key');
        var item = {
            'id' : jQuery(this).parents('#wpmm-item-details').attr('data-id'),
            'price' : jQuery('#wpmm-item-details-price span').attr('data-price'),
            'instructions' : jQuery('#wpmm-item-details-instructions textarea').val(),
            'quantity' : jQuery('#wpmm-item-details-quantity').text(),
            'cut' : jQuery('#wpmm-pizza-cut').val(),
            'attr' : []
        };
        item['attr']={};
        jQuery("#wpmm-item-details-attributes li input:checked").each(function(){
            item.attr[jQuery(this).attr('value')] = {'price':jQuery(this).attr('data-price'),'size':jQuery(this).attr('data-size')};
        });
        if (typeof key !== 'undefined' && key !== false) {
            item['key'] = key;
            var data = {
                'action': 'front',
                'edititem': item,
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            };
        }
        else {
            var data = {
                'action': 'front',
                'additem': item,
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            };
        }
        jQuery.ajax({
            url: '<?php echo MM_AJAX_URL ?>',
            data: data,
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                jQuery('#wpmm-item-loading').css({'top':'50%','left':'50%','margin-left':'-'+24+'px'}).show();
            },
            success: function(data) {
                jQuery('#wpmm-item-details-close').click();
                refresh_order();
                if (jQuery('#wpmm-form-showbutton').length)
                    jQuery('#wpmm-form-showbutton').click();
                jQuery('#wpmm-item-loading').hide();
            }
        });
        return false;
    });
    jQuery("#wpmm-item-details-attributes input[type=checkbox], #wpmm-item-details-attributes input[type=radio]").on('click', function(){
        refresh_item_total();
    });
</script>