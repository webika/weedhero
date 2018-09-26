<?php
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'WeedHero',

    'preload' => array(
        'log',
    ),

    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.CAdvancedArBehavior',
    ),

    'defaultController' => 'front',

    'aliases' => array(
        'RestfullYii' =>realpath(__DIR__ . '/../extensions/starship/RestfullYii'),
    ),

    'components' => array(
        'Paypal' => array(
            'class'=>'application.components.Paypal',
            'apiUsername' => '',
            'apiPassword' => '',
            'apiSignature' => '',
            'apiLive' => true,

            'returnUrl' => 'paypal/confirm/',
            'cancelUrl' => 'paypal/cancel/',
            'currency' => 'USD',
        ),
        'file' => array(
            'class' => 'application.extensions.CFile',
        ),
        'mail' => array(
             'class' => 'ext.YiiMail.YiiMail',
             'transportType' => 'php',
             'viewPath' => 'application.views.mail',
             'logging' => true,
             'dryRun' => false
         ),
        'user' => array(
            'class' => 'WebUser',
            'loginUrl'=>array('admin'),
            'allowAutoLogin' => true,
        ),
        'authManager' => array(
            'class' => 'PhpAuthManager',
            'defaultRoles' => array('guest'),
        ),
        'request' => array(
            'class' => 'application.components.HttpRequest',
            'enableCsrfValidation' => true,
            'csrfTokenName' => 'MM_CSRF_TOKEN',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            //'caseSensitive'=>false,
            'rules' => require(dirname(__FILE__).'/../extensions/starship/RestfullYii/config/routes.php'
            ),
        ),
        'db' => array(
            'connectionString' => 'mysql:host=' . MM_DB_HOST . ';dbname=' . MM_DB_NAME,
            'emulatePrepare' => true,
            'username' => MM_DB_USER,
            'password' => MM_DB_PASS,
            'charset' => 'utf8',
            'enableParamLogging' => true,
        ),
        'session' => array(
            'class' => 'CDbHttpSession',
            'sessionName' => 'mm_session',
            'autoCreateSessionTable' => true,
            'sessionTableName' => MM_TABLE_PREFIX . 'session',
            'connectionID' => 'db',
            'timeout' => 21600,
        ),
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
        ),
        'errorHandler' => array(
            'errorAction' => 'front/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
//                    'class'=>'CFileLogRoute',
//                    'levels'=>'trace,log',
//                    'categories' => 'system.db.CDbCommand',
//                    'logFile' => 'db.log',
                ),
            ),
        ),
        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap',
            'coreCss' => false,
            'yiiCss' => false,
            'jqueryCss' => false,
            'popoverSelector' => '.mm-popover',
            'tooltipSelector' => '.mm-tooltip',
        ),
        'mobileDetect' => array(
            'class' => 'ext.MobileDetect.MobileDetect'
        ),
    ),

    'params' => array(
        'defaultSettings' => array(
            'license_key' => '',
            'theme' => 'light',
            'layout' => 'vertical',
            'zip_type' => '2',
            'zip_self'=>'',
            'zip_list'=>'',
            'vendor_name'=>'',
            'vendor_email'=>'',
            'vendor_city'=>'',
            'vendor_state'=>'',
            'vendor_street'=>'',
            'vendor_phone'=>'',
            'vendor_fax'=>'',
            'tax_rate'=>'0',
            'delivery_charge'=>'0',
            'discount' => '0',
            'min_order' => '0',
            'item_sort_field'=>'id',
            'item_sort_order'=>'ASC',
            'cart_position'=>'inside-left',
            'cart_type'=>'1',
            'tabs_effect' => 'fade',
            'cat_pict'=>'1',
            'items_pict'=>'1',
            'cat_sort_order'=>'ASC',
            'cat_sort_field'=>'id',
            'work_time'=>'a:7:{i:0;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:1;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:2;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:3;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:4;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:5;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"18";}i:6;a:3:{s:6:"active";s:1:"1";s:4:"from";s:1:"9";s:2:"to";s:2:"15";}}',
            'timezone'=>'America/New_York',
            'api_login_id'=>'******',
            'transaction_key'=>'******',
            'enable_payments' => '0',
            'enable_efax' => '0',
            'efax_login_id' => '',
            'efax_username' => '',
            'efax_password' => '',
            'powered_by' => '0',
            'body_texture' => 'bodytexture.jpg',
            'out_color' => '#dedede',
            'out_texture' => '0',
            'inner_texture' => 'inner_texture.jpg',
            'in_color' => '#FFF',
            'in_texture' => '0',
            'in_width'=>'1130',
            'in_position'=>'center',
            'in_shadow'=>'1',
            'logo_position'=>'center',
            'enable_logo'=>'1',
            'logo_image'=>'logo_image.png',
            'admin_login'=>'admin',
            'admin_salt'=>'$1$a5cc6cd154$',
            'admin_hash'=>'$1$4b073a7d$nQpCoOV82Cz1WN.zQaMRl1',
            'admin_email'=>'',
            'enable_footer'=>'1',
            'footer_text'=>'<b>WeedHero</b>. All rights reserved ©',
            'powered_by'=>'1',
            'header_text'=>'<meta name="description" content="WeedHero Online Ordering">',
            'app_name'=>'WeedHero',
            'enable_payments_paypal'=>'0',
            'apiSignature'=>'',
            'apiPassword'=>'',
            'apiUsername'=>'',
            'enable_delivery'=>'1',
            'item_mode'=>'0',
            'enable_social'=>'1'
        ),
    ),
);
