<!DOCTYPE html>
<html lang="en">
    <?php
    $options = MMSettingsForm::getParams();
    ?>
<head>
    <meta charset="utf-8">
    <title><?php echo $options['app_name']; ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::app()->getBaseUrl(true) ?>/images/favicon.png" type="image/png">
    <?php if(MMSettingsForm::getParam('enable_social')){ ?><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><?php } ?>
    <?php if(!empty($options['header_text'])){
        echo $options['header_text'];
    } ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <style>
        <?php
        echo 'body {';
            echo 'background-color: ' . $options['out_color'] . ';';
            if ($options['out_texture'] == '1')
                echo 'background-image: url(' . Yii::app()->baseUrl . "/" . MM_UPLOADS_URL . "/" . $options['body_texture'] . ');';
        echo "}";
        echo '#wpmm {';
            if ($options['in_width'])
                echo 'width: ' . $options['in_width'] . 'px;';
            echo 'background-color: ' . $options['in_color'] . ';';
            if ($options['in_texture'] == '1')
                echo 'background-image: url(' . Yii::app()->baseUrl . "/" . MM_UPLOADS_URL . "/" . $options['inner_texture'] . ');';
            if ($options['in_position'] == 'center')
                echo 'margin: 0 auto;';
            else
                echo 'float: ' . $options['in_position'] . ';';
            if ($options['in_shadow'] == '1')
                echo '-webkit-box-shadow: 0px 0px 10px rgba(50, 50, 50, 0.77); -moz-box-shadow:    0px 0px 10px rgba(50, 50, 50, 0.77); box-shadow:         0px 0px 10px rgba(50, 50, 50, 0.77);';
            echo 'padding: 10px;';
            echo '-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;';
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
		body{
			margin:0;
		}
		#header-v3{
			font-family: Delius, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: normal;
    color: #767676;
		}
		#top-bar {
    background-color: #6cbc43;
	    padding: 5px 0px;
		    box-sizing: border-box;
			    float: left;
    width: 100%;
	    line-height: 22px;
}

.container {
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}
.row {
    margin-right: -15px;
    margin-left: -15px;
}
.tagline {
    font-family: Raleway, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: normal;
    color: #ffffff;
}
.call-number {
    font-family: Raleway, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: normal;
    color: #f9f9f9;
	    text-align: right;
}
#header {
    background:#FFF;
    width: 100%;
    padding: 20px 0;
	    float: left;
}
.col-md-4 {
    width: 33.33333333333333%;
	float:left;
}
.col-md-8 {
    width: 66.66666666666666%;
	float:right;
	padding-top: 13px;
}

.ddsmoothmenu {
    padding-top: 11px;
	float: right;
	background: transparent scroll;
    width: auto;
    padding: 0;
    margin: 0;
}
.ddsmoothmenu ul {
    display: inline-block;
	    padding: 0;
    margin: 0;
    list-style-type: none;
    background: transparent;
    box-shadow: none;
    z-index: 100;
}
.ddsmoothmenu ul li {
    padding: 0;
    margin: 0;
    border: none;
    position: relative;
    display: inline;
    float: left;
}
.ddsmoothmenu ul li a {
    font-family: Delius, Arial, Helvetica, sans-serif;
    font-size: 14px;
    font-weight: bold;
    color: #4f4f4f;
	    line-height: 1em;
    text-transform: uppercase;
    text-decoration: none;
    outline: none;
    margin-left: 1px;
    background: transparent;
	    transition: all 0.3s ease;
    display: block;
    padding: 10px;
	    cursor: pointer;
    font-style: normal;
}
.ddsmoothmenu ul li.current-menu-item a, .ddsmoothmenu ul li a:hover {
    background: #efefef;
    color: #4f4f4f !important;
	box-shadow: 0 1px 0px 0px rgba(255, 255, 255, 0.2) inset;
	    -webkit-border-radius: 4px;
    border: none;
}
@media (min-width: 1200px)
{
.container {
    max-width: none !important;
    width: 970px;
}
}
@media (min-width: 992px)
{
.col-md-6 {
    width:47%;
	float:left;
}
.tagline {
    text-align: left;
    margin: 0;
}
}
*[role="log"] {
    display:none;
}

    </style>
</head>

<body>
<section id="header-wrapper" class="clearfix">
    
<div id="header-v3" class="clearfix">
  <div id="top-bar" class="clearfix">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="tagline">
                        Online delivery software for medical marijuana dispensaries                      </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="call-number">
                        Call us today: 503 922 1026                 </div>
                    <div class="header-wiget-area">
                        <div id="text-3" class="widget widget_text">			<div class="textwidget"></div>
		</div><div id="text-4" class="widget widget_text">			<div class="textwidget"></div>
		</div>                      </div>
                  </div>
      </div>
    </div>
  </div>
  <!-- #topbar -->
  
  <div id="header">
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-sm-4 col-xs-12 logo">
          <a href="http://www.weedhero.net/" title="Online delivery and pickup system for medical marijuana dispensaries"><img src="http://www.weedhero.net/wp-content/uploads/2017/05/logo_weedhero.png" alt="Online delivery and pickup system for medical marijuana dispensaries"></a>
        </div>
        <!-- .logo -->
        
        <div class="col-md-8 col-sm-8 col-xs-12 clearfix">
          <div id="smoothmenu" class="ddsmoothmenu"><ul id="nav" class="menu"><li id="menu-item-2019" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2019"><a href="http://www.weedhero.net/">Home</a></li>
<li id="menu-item-135" class="menu-item menu-item-type-custom menu-item-object-custom  current-menu-item page_item page-item-32 current_page_item menu-item-135"><a target="_blank" href="http://www.weedhero.net/marijuana-online-ordering-software/">Demo</a></li>
<li id="menu-item-2377" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2377"><a href="http://www.weedhero.net/software-features/">Features</a></li>
<li id="menu-item-2016" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2016"><a href="http://www.weedhero.net/support/">FAQ</a></li>
<li id="menu-item-2134" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2134"><a href="http://www.weedhero.net/about-us/">About Us</a></li>
<li id="menu-item-2310" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2310"><a href="http://www.weedhero.net/pricing/">Pricing</a></li>
<li id="menu-item-22" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-22"><a href="http://www.weedhero.net/contact/">Contact US</a></li>
<li id="menu-item-2238" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2238"><a target="_blank" href="http://www.weedhero.net/control/status">Log in</a></li>
</ul></div>        </div>
        <!-- #menu --> 
        
      </div>
    </div>
  </div>
  <!-- #header --> 
</div>
<!-- #header variation --> </section>

<?php if(MMSettingsForm::getParam('enable_social')){ ?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    <?php } ?>
    <?php
    if ($options['enable_logo'] == '1') {
        echo '<div id="mm-logo-wrap">';
            echo '<div id="mm-logo">';
                echo '<img src="'.Yii::app()->baseUrl . "/" . MM_UPLOADS_URL . "/".$options['logo_image'].'" alt="'.$options['vendor_name'].'"/>';
            echo '</div>';
        echo '</div>';
    }
    ?>
    <div id="wpmm">
        <?php echo $content ?>
        <div id="wpmm-item-details"></div>
        <div id="wpmm-item-loading"></div>
        <?php if (MMSettingsForm::getParam('powered_by')) { ?>
        <div align="right"><a href="http://wpmenumaker.com/" class="wpmm-powered"></a></div>
        <?php } ?>
    </div>
    <?php if ($options['enable_footer']) { ?>
    <div id="mm-footer">
        <?php echo MMSettingsForm::getParam('footer_text');  ?>
    </div>
    <?php } ?>
    <script>
        function buttonInit() {
            jQuery('#wpmm-form-showbutton').click(function() {
                var cart = jQuery('#wpmm-form');
                jQuery('#wpmm-form').attr('data-hide', 0);
                jQuery('#wpmm-form-showbutton').remove();
                if (jQuery('#wpmm-form').hasClass('outside-right')) {
                    cart.animate({right: '0px'});
                }
                if (jQuery('#wpmm-form').hasClass('outside-left')) {
                    cart.animate({left: '0px'});
                }
                return false;
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
                            details.html(data);
                            details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 150) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                        }
                        details.show();
                    }
                });
                return false;
            });
        }

        function CalculateFromScroll() {
            if (jQuery('#wpmm-form').hasClass('outside-left') || jQuery('#wpmm-form').hasClass('outside-right')) {
                var sumheight = jQuery('#wpmm-forn-hide-container').height() + jQuery('#wpmm-cart-control').height();
                sumheight = jQuery('#wpmm-form').height() - sumheight;
                jQuery('#wpmm-form-container-scroll').css({'height': sumheight + 'px', 'overflow': 'hidden'});
            }
        }

        function InitCart() {
            AddressControlsInit();
            if (jQuery('#wpmm-form').hasClass('outside-left') || jQuery('#wpmm-form').hasClass('outside-right')) {
                var cart = jQuery('#wpmm-form');
                cart.prepend('<div id="wpmm-forn-hide-container"><a class="wpmmcart-hide" href="#">Hide</a><hr class="wpmm-form-hr"></div>');
                jQuery('#wpmm-form').attr('data-hide', 0);
                jQuery('#wpmm-cart-control').prepend('<hr class="wpmm-form-hr">').css({'position': 'absolute', 'bottom': '0', 'width': '100%', 'height': '60px', 'display': 'block', 'zIndex': '5'});
                jQuery(document).ready(function() {
                    CalculateFromScroll();
                    jQuery("#wpmm-form-container-scroll").mCustomScrollbar();
                    if (jQuery(window).width() <= 1280 && jQuery('#wpmm-form').attr('data-hide') == 0) {
                        jQuery('.wpmmcart-hide').trigger('click');
                    }
                }
                );

                jQuery('.wpmmcart-hide').click(function() {
                    var cart = jQuery('#wpmm-form');
                    jQuery('#wpmm-form').attr('data-hide', 1);
                    if (jQuery('#wpmm-form').hasClass('outside-right')) {
                        cart.animate({right: '-' + jQuery('#wpmm-form').width() + 'px'}, function() {
                            if (jQuery('#wpmm-form-showbutton').length == 0) {
                                jQuery('#wpmm').append('<div id="wpmm-form-showbutton" align="center"><div>ORDER</div></div>');
                                jQuery('#wpmm-form-showbutton').css({'right': '0'});
                                buttonInit();
                            }
                        });
                    }
                    if (jQuery('#wpmm-form').hasClass('outside-left')) {
                        cart.animate({left: '-' + jQuery('#wpmm-form').width() + 'px'}, function() {
                            if (jQuery('#wpmm-form-showbutton').length == 0) {
                                jQuery('#wpmm').append('<div id="wpmm-form-showbutton" align="center"><div>ORDER</div></div>');
                                jQuery('#wpmm-form-showbutton').css({'left': '0'});
                                buttonInit();
                            }
                        });
                    }
                    return false;
                });
            }
            jQuery(window).resize(function() {
                CalculateFromScroll();
                var vwidth = jQuery(window).width();
                if (vwidth <= 1280 && jQuery('#wpmm-form').attr('data-hide') == 0) {
                    jQuery('.wpmmcart-hide').trigger('click');
                } else if (jQuery('#wpmm-form').attr('data-hide') == 1 && vwidth >= 1280) {
                    jQuery('#wpmm-form-showbutton').trigger('click');
                }
            });
        }
        jQuery("a[rel=wpmm-lightbox]").fancybox({'titlePosition': 'over', 'showNavArrows':false});
        jQuery("a[rel=wpmm-lightbox] img").hover(function() {
            jQuery(this).stop().animate({"opacity": 0.8});
        }, function() {
            jQuery(this).stop().animate({"opacity": 1});
        });
        function fixHeight() {
            if (jQuery("#wpmm-form").hasClass("inside-left") || jQuery("#wpmm-form").hasClass("inside-right")) {
                var effect = '<?php echo MMSettingsForm::getParam('tabs_effect') ?>';
                jQuery("#wpmm").css('minHeight', jQuery('#wpmm-form').outerHeight());
            }
        }
        fixHeight();
        function setupFade() {
            var effect = '<?php echo MMSettingsForm::getParam('tabs_effect') ?>';
            jQuery('#wpmm-fade-container').css({'position': 'relative'});
            if (effect === 'fade') {
                jQuery('.wpmm-item-description').mCustomScrollbar();
            }
        }
        function adjustslide() {
            var effect = '<?php echo MMSettingsForm::getParam('tabs_effect') ?>';
            if (effect == 'slide') {
                jQuery('#wpmm-slider').css({'width': (jQuery('#wpmm-slide-container').width() * jQuery('.wpmm-tab-content.visible').length + 100)});
                jQuery('.wpmm-tab-content.visible').css({'width': jQuery('#wpmm-slide-container').width() - 10});
                var animWidth = jQuery('#wpmm-slide-container').width();
                jQuery("#wpmm-menu-list a").each(function() {
                    if (jQuery(this).hasClass('active')) {
                        var positiontab = jQuery(this).attr('data-num');
                        jQuery('#wpmm-slider').css({left: '-' + animWidth * positiontab + 'px'});
                        positiontab++;
                        jQuery('#wpmm-slide-container').css({'overflow-y': 'hidden', 'height': jQuery('#wpmm-tab-' + positiontab).height()});
                    }
                });
            }
        }
        function ajustfade() {
            var effect = '<?php echo MMSettingsForm::getParam('tabs_effect') ?>';
            if (effect == 'fade') {
                jQuery("#wpmm-menu-list a").each(function() {
                    if (jQuery(this).hasClass('active')) {
                        var heightofdiv = jQuery(jQuery(this).attr('href')).height();
                        jQuery('#wpmm-fade-container').css({'height': heightofdiv});
                    }
                });
            }
        }
        jQuery(document).ready(function() {
            jQuery(window).resize(function() {
                adjustslide();
                ajustfade();
            });
            var effect = '<?php echo MMSettingsForm::getParam('tabs_effect') ?>';
            if (effect != 'fade' && effect != 'scale') {
                jQuery('.wpmm-item-description').mCustomScrollbar();
                setTimeout(function() {
                    jQuery('#wpmm-slide-container').css({'overflow-y': 'hidden', 'height': jQuery('#wpmm-tab-1').height()});
                }, 2000);
            } else if (effect == 'scale') {
                jQuery('.wpmm-tab-content').each(function() {
                    jQuery(this).addClass('visible');
                });
                jQuery('.wpmm-item-description').mCustomScrollbar();
                jQuery('.wpmm-tab-content').each(function() {
                    jQuery(this).removeClass('visible');
                });
                jQuery('#wpmm-tab-1').addClass('visible');
            }
            adjustslide();
            setupFade();
            InitCart();
            <?php if(isset($_GET['wpmm-id'])) {?>
            jQuery(document).ready(function() {
                setTimeout(function() {
                       $( ".wpmm-item[data-id=<?php echo $_GET['wpmm-id']; ?>]").first().click();
                   }, 5000);
            });
            <?php } ?>
        });

        jQuery("#wpmm-menu-list-vert a").on('click', function() {
            var target = jQuery(this).attr('href');
            jQuery('html, body').animate({scrollTop: jQuery(target).offset().top - 50}, 1000, function() {
                jQuery(target + ' .wpmm-menu-image-wrap').animate({opacity: 0.4}, 50, function() {
                    jQuery(target + ' .wpmm-menu-image-wrap').animate({opacity: 0.8}, 50, function() {
                        jQuery(target + ' .wpmm-menu-image-wrap').animate({opacity: 1}, 50);
                    });
                });
            });
            return false;
        });

        jQuery("#wpmm-menu-list a").on('click', function() {
            if (jQuery(this).hasClass('active') || jQuery(".wpmm-tab-content.loading").length > 0)
                return false;
            var effect = '<?php echo MMSettingsForm::getParam('tabs_effect') ?>';
            jQuery('.wpmm-item-details').hide();
            var clicked = jQuery(this).attr('href');
            var currenttab = null;
            jQuery("#wpmm-menu-list a").each(function() {
                if (jQuery(this).hasClass('active')) {
                    currenttab = jQuery(this).attr('href');
                }
                jQuery(this).removeClass();
            });
            jQuery(this).attr('class', 'active');
            switch (effect) {
                default :
                case 'fade' :
                    jQuery(clicked).show();
                    jQuery(currenttab).animate({opacity: 0.0}, 200, function() {
                        jQuery(clicked).animate({opacity: 1.0}, 200, function() {
                            jQuery('#wpmm-fade-container').css({'position': 'relative', 'height': jQuery(clicked).height()});
                            jQuery(currenttab).hide();
                        });
                    });
                    break;
                case 'slide' :
                    var animWidth = jQuery('#wpmm-slide-container').width();
                    var positiontab = jQuery(this).attr('data-num');
                    jQuery('#wpmm-slider').stop().animate({left: '-' + animWidth * positiontab + 'px'}, 500, function() {
                        positiontab++;
                        jQuery('#wpmm-slide-container').css({'overflow-y': 'hidden', 'height': jQuery('#wpmm-tab-' + positiontab).height()});
                    });
                    break;
                case 'scale' :
                    var formheight = jQuery('#wpmm-form').height();
                    var currentheight = jQuery(currenttab).height();
                    var clickedheight = jQuery(clicked).height();
                    if (jQuery('#wpmm-tab-content-wrap').length)
                        jQuery('#wpmm-tab-content-wrap').css('height', Math.max(formheight, currentheight, clickedheight) + 'px');
                    else
                        jQuery('#wpmm-fade-container').wrapInner('<div id="wpmm-tab-content-wrap" style="height:' + currentheight + 'px" />');
                    jQuery('.wpmm-tab-content.visible').hide('slow', function() {
                        jQuery(clicked).attr({'class': 'wpmm-tab-content visible'}).show('slow', function() {
                            jQuery(this).attr({'class': 'wpmm-tab-content visible', 'style': 'display:block'});
                        });
                        jQuery(this).attr({'class': 'wpmm-tab-content', 'style': 'display:none'});
                    });
                    jQuery('html, body').animate({scrollTop: jQuery("#wpmm").offset().top - 40}, 1000);
                    if (jQuery('#wpmm-form').hasClass('inside-left') || jQuery('#wpmm-form').hasClass('inside-right'))
                        jQuery('#wpmm-tab-content-wrap').css('height', Math.max(formheight, clickedheight) + 'px');
                    else
                        jQuery('#wpmm-tab-content-wrap').css('height', clickedheight + 'px');
                    break;
            }
            jQuery('#wpmm').trigger('resize');
            return false;
        });
        jQuery('.wpmm-item-buy, .wpmm-item').on('click', function() {
            var details = jQuery('#wpmm-item-details');
            var id = jQuery(this).attr('data-id');
            jQuery.ajax({
                url: '<?php echo MM_AJAX_URL ?>',
                data: {
                    'action': 'front',
                    'itemdetails': {'id': id},
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
                        details.attr('data-id', id);
                        details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                        refresh_item_total();
                    }
                }
            });
            return false;
        }).children(':not(.wpmm-item-header)').click(function() {
            return false;
        });
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
                        jQuery('#wpmm-form').html(data);
                        InitCart();
                        jQuery('#wpmm-item-loading').hide();
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
                        details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
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
                        details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                        refresh_order();
                        details.show();
                    }
                }
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
                    if (data != 'There where errors') {
                        details.html(data);
                        details.css({'top': '50%', 'left': '50%', 'margin': '-' + (details.height() - 200) + 'px' + ' 0 0 -' + (details.width() / 2) + 'px'});
                        details.show();
                    } else {
                        refresh_order();
                    }
                }
            });
        }
    </script>
</body>
</html>
