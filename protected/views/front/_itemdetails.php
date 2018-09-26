<div id="wpmm-item-details-header">
    <p id="wpmm-item-details-name"><?php echo $item->name ?></p>
    <p id="wpmm-item-details-price">$<span data-price="<?php echo $item->price ?>"><?php echo $item->price ?></span></p>
    <?php
        global $selected_attrs, $first;
        $selected_attrs = '<div id="selected-attributes" style="display: none">
                                <div class="wpmm-form-info">
                                <p>Selected options:</p>
                                <table>';
        $first = true;
        if (isset($item_key) && is_array($order['items'][$item_key]['attributes'])) {
            foreach ($item->params as $param) {
                foreach ($param->attribs as $attribute) {
                    if (array_key_exists($attribute->id, $order['items'][$item_key]['attributes'])) {
                        if($param->type == 2)
                            Helpers::renderSelectedAttribute($attribute,$order['items'][$item_key]['attributes'][$attribute->id]['size']);
                        else
                            Helpers::renderSelectedAttribute($attribute);
                    }
                }
            }
        }
        else {
            foreach ($item->params as $param) {
                foreach ($param->attribs as $attribute) {
                    if ($attribute->checked_id) {
                        if($param->type == 2)
                            Helpers::renderSelectedAttribute($attribute,1);
                        else
                            Helpers::renderSelectedAttribute($attribute);
                    }
                }
            }
        }
        $selected_attrs .= '</table></div></div>';
        echo $selected_attrs;
    ?>
</div>
<div id="wpmm-item-details-content">
    <div id="wpmm-item-details-content-left">
        <?php if ($item->image) : ?>
         <a href="<?php echo MM_UPLOADS_URL . '/' . $item->image ?>" alt="<?php echo $item->image ?>" rel="wpmm-lightbox" title="<?php echo $item->name ?>">
            <img id="wpmm-item-details-image" src="<?php echo MM_UPLOADS_URL . '/thumb_' . $item->image ?>" alt="<?php echo $item->name ?>"/>
         </a>
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
    </div>
</div>
<div id="wpmm-item-details-more">
    <ul id="wpmm-item-details-groups">
        <li class="active"><a href="#wpmm-item-details-instructions">Special instructions</a></li>
        <?php foreach ($item->params as $param) { ?>
            <li><a href="#wpmm-item-details-group-<?php echo $param->id ?>"><?php echo $param->name ?></a></li>
        <?php } ?>
    </ul>
    <ul id="wpmm-item-details-attributes">
        <li id="wpmm-item-details-instructions" class="active">
            <textarea placeholder="Additional comments about your order..."><?php if (isset($item_key))
                    echo $order['items'][$item_key]['instructions'] ?></textarea>
        </li>
        <?php foreach ($item->params as $param) { ?>
        <li id="wpmm-item-details-group-<?php echo $param->id ?>" data-id="<?php echo $param->id ?>">
            <div class="wpmm-item-details-srcoll">
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
                                } else {
                                    $active[1]='active1';
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
                                } else {
                                    echo '1';
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
                    <span <?php  if (isset($item_key) && $order['items'][$item_key]['cut'] != 1 || !isset($item_key)) echo 'style="display:none"'; ?>  class="wpmm-pizza-control" data-id="wpmm-attribute-<?php echo $attribute->id ?>"><div class="wpmm-circ-left <?php echo $active[3]; ?>"></div><div class="wpmm-circ-full <?php echo $active[1]; ?>"></div><div class="wpmm-circ-right <?php echo $active[2]; ?>"></div></span>
                    <br>
                <?php } ?>
                    <?php } ?>
            </div>
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
    jQuery('.wpmm-item-details-srcoll').mCustomScrollbar({
        advanced:{
        updateOnBrowserResize:true,
        updateOnContentResize:true
        }
    });
    function UpdateSidePanel(){
        var selected_attrs = '';
        var sizes = ['left','full','right','left'];
        jQuery('#wpmm-item-details-attributes :checked').each(function(){
            var size = jQuery(this).attr('data-size');
            var price = jQuery(this).attr('data-price');
            if(size == 1 || size == 2 || size == 3){
                if(size == 3 || size == 2){
                    price = parseFloat(price/2);
                } else {
                    price = parseFloat(price);
                }
                if(price.toString().substring(1) == '-'){
                    price = '- $'+price.toString().substring(1);
                } else { price='+ $'+price; }
                size = sizes[jQuery(this).attr('data-size')];
                selected_attrs += '<tr><td>'+jQuery.trim(jQuery(this).next('span').text())+' ( '+size+' )</td><td width="65" class="selected-attr-price">'+jQuery.trim(price)+'</td></tr>';
            } else {
                selected_attrs += '<tr><td>'+jQuery.trim(jQuery(this).next('span').text())+'</td><td width="65" class="selected-attr-price">'+jQuery.trim(jQuery(this).next('span').next('span').text())+'</td></tr>';
            }
        });
        if (selected_attrs.length > 0)
            jQuery('#selected-attributes').show().find(".wpmm-form-info").children('table').html(selected_attrs);
        else
            jQuery('#selected-attributes').hide();
    }
    jQuery(document).trigger('resize');
    jQuery("a[rel=wpmm-lightbox]").fancybox({'titlePosition' : 'over', 'showNavArrows':false});
    jQuery('#wpmm-item-details').draggable({ handle: "#wpmm-item-details-header" });
    jQuery('#wpmm-item-details-attributes').height(jQuery('#wpmm-item-details-groups').height());
    jQuery('#wpmm-item-details-instructions.active textarea').css('height',jQuery('#wpmm-item-details-instructions.active').height()-8);
    jQuery('#wpmm-item-details-close').on('click', function() {
        jQuery('#wpmm-item-details').hide();
        return false;
    });
    jQuery('#wpmm-item-details-groups a').on('click', function() {
        var attribute_id = jQuery(this).attr('href');
        jQuery('#wpmm-item-details-groups a').each(function(){
            jQuery(this).parent().removeClass();
        });
        jQuery(this).parent().attr('class', 'active');
        jQuery('#wpmm-item-details-attributes .active').removeClass();
        jQuery(attribute_id).attr('class', 'active');
        jQuery('.wpmm-item-details-srcoll').mCustomScrollbar('update');
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
            UpdateSidePanel();
            refresh_item_total();
        } else {
            $('.wpmm-pizza-control').show();
        }
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
    jQuery("#wpmm-item-details-attributes li input").on('click', function(){
        refresh_item_total();
    });
    jQuery("#wpmm-item-details-attributes input[type=checkbox], #wpmm-item-details-attributes input[type=radio]").on('click', function(){
        UpdateSidePanel();
    });
    jQuery('.wpmm-item-details-srcoll').css({'height': Math.max.apply(Math, jQuery("#wpmm-item-details-groups").map(function() { return jQuery(this).height()-20; }))});
</script>
