<?php
class MMSettingsForm extends CFormModel {

    public $license_key;
    public $theme;
    public $layout;
    public $tax_rate;
    public $delivery_charge;
    public $discount;
    public $min_order;
    public $item_sort_field;
    public $item_sort_order;
    public $cart_position;
    public $file;
    public $tabs_effect;
    public $items_pict;
    public $cat_pict;
    public $cat_sort_field;
    public $cat_sort_order;
    public $api_login_id;
    public $transaction_key;
    public $enable_payments;
    public $efax_login_id;
    public $efax_username;
    public $efax_password;
    public $enable_efax;
    public $powered_by;
    public $out_color;
    public $out_texture;
    public $body_texture;
    public $in_texture;
    public $in_shadow;
    public $inner_texture;
    public $in_color;
    public $in_width;
    public $enable_logo;
    public $logo_position;
    public $in_position;
    public $logo_image;
    public $admin_login;
    public $admin_salt;
    public $admin_hash;
    public $admin_email;
    public $enable_footer;
    public $footer_text;
    public $header_text;
    public $app_name;
    public $apiUsername;
    public $apiPassword;
    public $apiSignature;
    public $enable_payments_paypal;
    public $dbversion;
    public $enable_delivery;
    public $item_mode;
    public $enable_social;

    public function attributeLabels() {
        return array(
            'license_key' => 'License key',
            'theme' => 'Plugin theme',
            'layout' => 'Menus layout',
            'powered_by' => 'Show Powered By link',
            'tax_rate' => 'Tax rate (%)',
            'delivery_charge' => 'Delivery charge ($)',
            'discount' => 'Discount (%)',
            'min_order' => 'Minimum order amount ($)',
            'item_sort_field' => 'Sort items by',
            'item_sort_order' => 'Sort order',
            'cart_position' => 'Shopping cart position',
            'tabs_effect' => 'Tabs transition effect',
            'items_pict'=>'Enable Items Pictures',
            'cat_pict'=>'Enable Category Pictures',
            'cat_sort_field'=>'Sort categories by',
            'cat_sort_order'=>'Sort order',
            'api_login_id'=>'API Login ID',
            'transaction_key'=>'Transaction Key',
            'enable_payments' => 'Authorize.net payments',
            'efax_login_id'=>'Company',
            'efax_username'=>'Username',
            'efax_password'=>'Password',
            'enable_efax'=>'Enable FAXAGE',
            'out_color' => 'Select color',
            'out_texture' => 'Background texture',
            'body_texture' => 'Body texture',
            'in_texture' => 'Inner texture',
            'in_shadow' => 'Inner block shadow',
            'enable_logo' => 'Enable logo',
            'inner_texture' =>'Inner texture',
            'in_color'=>'Inner color',
            'in_width'=>'Width (pixels)',
            'in_position'=>'Inner position',
            'logo_position'=>'Logo Position',
            'logo_image'=>'Logo Image',
            'admin_login'=>'Admin login',
            'admin_salt'=>'Repeat password',
            'admin_hash'=>'Password',
            'admin_email'=>'Admin Email',
            'enable_footer'=>'Enable footer',
            'footer_text'=>'Footer text (html)',
            'header_text'=>'Header data (meta keywords, google analitics code)',
            'app_name'=>'Application title',
            'apiUsername'=>'Paypal API username',
            'apiPassword'=>'API Password',
            'apiSignature'=>'API Signature',
            'enable_payments_paypal'=>'Enable PayPal',
            'dbversion' => 'Database version',
            'enable_delivery'=>'Enable Delivery',
            'item_mode'=>'Item Display style',
            'enable_social'=>'Enable social buttons'
        );
    }

    public function rules() {
        return array(
            array('vendor_email, admin_email',
                'email', 'message' => 'Invalid Email address'),
            array('tax_rate, delivery_charge, discount, min_order',
                'numerical'),
            array('enable_social, item_mode, enable_delivery, dbversion, enable_payments_paypal ,apiSignature ,apiPassword ,apiUsername, app_name, header_text, footer_text, enable_footer, admin_hash, admin_salt, admin_login, logo_image, logo_position, in_width, in_color, inner_texture, in_texture, in_shadow, body_texture, out_texture, out_color,enable_efax, efax_password, efax_username, efax_login_id, license_key, theme, layout, powered_by, item_sort_field, item_sort_order, cart_position, file, zipcode, tabs_effect, items_pict, cat_pict, cat_sort_field, cat_sort_order, api_login_id, transaction_key, enable_payments',
                'safe'),
            array('file',
                'file', 'allowEmpty' => true, 'on' => 'installtheme'),
        );
    }

    public function getThemesOptions() {
        $path = realpath(YiiBase::getPathOfAlias('application') . DIRECTORY_SEPARATOR . '..'  . DIRECTORY_SEPARATOR . 'themes');
        $dir = opendir($path);
        while (false !== ($name = readdir($dir))) {
            if ($name == '.' || $name == '..')
                continue;
            $file = $path . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . '.xml';
            if (file_exists($file)) {
                $theme = new SimpleXMLElement(file_get_contents($file));
                $themes[$name] = array('fullname' => $theme->fullname,
                    'author' => $theme->author,
                    'date' => $theme->date,
                    'version' => $theme->version,
                    'name' => $name,
                    'thumb' => Yii::app()->baseUrl . '/themes/' . $name .  "/thumb.png",
                );
            }
        }
        closedir($dir);
        return $themes;
    }

    public function getJqueryOptions() {
        if (self::getParam('jquery') === '1') {
            $options['checked'] = 'checked';
        }
        return $options;
    }

    static function getTableNames($table = NULL) {
        $tables = array(
            'addresses',
            'attributes',
            'categories',
            'category_items',
            'groups',
            'items',
            'menu_categories',
            'menus',
            'order_items',
            'orders',
            'settings',
            'customers',
            'coupons',
            'locations',
            'location_menus',
            'coupon_items',
        );
        if (is_null($table)) {
            foreach ($tables as $table) {
                $names[$table] = MM_TABLE_PREFIX . $table;
            }
            return $names;
        } elseif (in_array($table, $tables)) {
            return MM_TABLE_PREFIX . $table;
        } else {
            return FALSE;
        }
    }

    static function getParam($param) {
        return Yii::app()->db->createCommand()
                        ->select('value')
                        ->from(self::getTableNames('settings'))
                        ->where("name = '$param'")
                        ->queryScalar();
    }

    static function getParams() {
        $params_multiarray = Yii::app()->db->createCommand()
                        ->select('name, value')
                        ->from(self::getTableNames('settings'))
                        ->queryAll();
        foreach ($params_multiarray as $param) {
            $params[$param['name']] = $param['value'];
        }
        return $params;
    }

    public function save($param = null) {
        $sql = "UPDATE `" . self::getTableNames('settings') . "` SET `value`=:value WHERE `name`=:name";
        $command = Yii::app()->db->createCommand($sql);
        if (is_null($param)) {
            foreach ($this->attributes as $name => $value) {
                if ($this->validate(array($name))) {
                $command->bindParam(':value', $value, PDO::PARAM_STR)
                        ->bindParam(':name', $name, PDO::PARAM_STR)
                        ->execute();
                }
            }
        }
        elseif (in_array($param, array_keys(Yii::app()->params['defaultSettings']))) {
            $command->bindParam(':value', $this->{$param}, PDO::PARAM_STR)
                    ->bindParam(':name', $param, PDO::PARAM_STR)
                    ->execute();
        }
        else {
            return false;
        }
        return true;
    }
}