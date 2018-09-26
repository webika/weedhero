<?php
class Helpers {

    public static function GetHours($hour = false) {
        $hours = array(
            '0' => '12:00 PM',
            '24' => '12:30 PM',
            '1' => '1:00 AM',
            '25' => '1:30 AM',
            '2' => '2:00 AM',
            '26' => '2:30 AM',
            '3' => '3:00 AM',
            '27' => '3:30 AM',
            '4' => '4:00 AM',
            '28' => '4:30 AM',
            '5' => '5:00 AM',
            '29' => '5:30 AM',
            '6' => '6:00 AM',
            '30' => '6:30 AM',
            '7' => '7:00 AM',
            '31' => '7:30 AM',
            '8' => '8:00 AM',
            '32' => '8:30 AM',
            '9' => '9:00 AM',
            '33' => '9:30 AM',
            '10' => '10:00 AM',
            '34' => '10:30 AM',
            '11' => '11:00 AM',
            '35' => '11:30 AM',
            '12' => '12:00 AM',
            '36' => '12:30 AM',
            '13' => '1:00 PM',
            '37' => '1:30 PM',
            '14' => '2:00 PM',
            '38' => '2:30 PM',
            '15' => '3:00 PM',
            '39' => '3:30 PM',
            '16' => '4:00 PM',
            '40' => '4:30 PM',
            '17' => '5:00 PM',
            '41' => '5:30 PM',
            '18' => '6:00 PM',
            '42' => '6:30 PM',
            '19' => '7:00 PM',
            '43' => '7:30 PM',
            '20' => '8:00 PM',
            '44' => '8:30 PM',
            '21' => '9:00 PM',
            '45' => '9:30 PM',
            '22' => '10:00 PM',
            '46' => '10:30 PM',
            '23' => '11:00 PM',
            '47' => '11:30 PM',
        );
        if (is_numeric($hour) && array_key_exists($hour, $hours))
                return $hours[$hour];
        return $hours;
    }

    public static function getItemType($type = NULL){
        $types = array(0=>'Normal','1'=>'Pizza');
        if($type === NULL)
            return $types;
        return $types[$type];
    }

    public static function getPizzaCut($cut = NULL){
        $types = array('0'=>'Normal','1'=>'Cut half');
        if($cut === NULL)
            return $types;
        return $types[$cut];
    }

    public static function getHours24($format = true) {
        $hours = array(
            '00:00:00',
            '00:30:00',
            '01:00:00',
            '00:30:00',
            '02:00:00',
            '02:30:00',
            '03:00:00',
            '03:30:00',
            '04:00:00',
            '04:30:00',
            '05:00:00',
            '05:30:00',
            '06:00:00',
            '06:30:00',
            '07:00:00',
            '07:30:00',
            '08:00:00',
            '08:30:00',
            '09:00:00',
            '09:30:00',
            '10:00:00',
            '10:30:00',
            '11:00:00',
            '11:30:00',
            '12:00:00',
            '12:30:00',
            '13:00:00',
            '13:30:00',
            '14:00:00',
            '14:30:00',
            '15:00:00',
            '15:30:00',
            '16:00:00',
            '16:30:00',
            '17:00:00',
            '17:30:00',
            '18:00:00',
            '18:30:00',
            '19:00:00',
            '19:30:00',
            '20:00:00',
            '20:30:00',
            '21:00:00',
            '21:30:00',
            '22:00:00',
            '22:30:00',
            '23:00:00',
            '23:30:00',
            '00:00:00',
            '00:30:00',
            '01:00:00',
            '00:30:00',
            '02:00:00',
            '02:30:00',
            '03:00:00',
            '03:30:00',
            '04:00:00',
            '04:30:00',
            '05:00:00',
            '05:30:00',
            '06:00:00',
            '06:30:00',
            '07:00:00',
            '07:30:00',
            '08:00:00',
            '08:30:00',
            '09:00:00',
            '09:30:00',
            '10:00:00',
            '10:30:00',
            '11:00:00',
            '11:30:00',
            '12:00:00',
            '12:30:00',
            '13:00:00',
            '13:30:00',
            '14:00:00',
            '14:30:00',
            '15:00:00',
            '15:30:00',
            '16:00:00',
            '16:30:00',
            '17:00:00',
            '17:30:00',
            '18:00:00',
            '18:30:00',
            '19:00:00',
            '19:30:00',
            '20:00:00',
            '20:30:00',
            '21:00:00',
            '21:30:00',
            '22:00:00',
            '22:30:00',
            '23:00:00',
            '23:30:00',
        );
        if ($format) {
            foreach ($hours as $key => $ho1) {
                $hours[$key] = date('g:i A', strtotime($ho1));
            }
        }
        return $hours;
    }

    public static function getLocationList($all = true) {
        $val = MMLocation::model()->findall(array(
            'select' => 'id, name',
                ));
        $val = CHtml::listData($val, 'id', 'name');
        if ($all)
            $val[0] = Yii::t('_', 'All Locations');
        return $val;
    }

    public static function zipcodevalidate($zipcode) {
        if (!preg_match("/(^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$)|(^\d{5}(-\d{4})?$)/", $zipcode)){
            return false;
        } else {
            return true;
        }
    }

    public static function get_credit_supported()
    {
        return array (
          'Visa'=>'Visa',
          'MasterCard'=>'MasterCard',
          'American Express'=>'American Express',
          'Discover'=>'Discover',
          'JCB'=>'JCB',
          'Diner\'s Club'=>'Diner\'s Club',
          'EnRoute'=>'EnRoute',
        );
    }

    public static function get_country_code()
    {
        return array (
          'AU'=>'AUSTRALIA',
          'BR'=>'BRAZIL',
          'CA'=>'CANADA',
          'GB'=>'UNITED KINGDOM',
          'US'=>'UNITED STATES',
        );
    }

    public static function check_in_range($start_date, $end_date, $date_from_user)
    {
      $start_ts = strtotime($start_date);
      $end_ts = strtotime($end_date);
      $user_ts = strtotime($date_from_user);

      return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
    }

    public static function json_encodealt($a=false)
    {
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                return floatval(str_replace(',', '.', strval($a)));
            }
            if (is_string($a))
            {
                static $jsonReplaces = array(array('\\', '/', "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); true; $i++) {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = self::json_encodealt($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = self::json_encodealt($k).':'.self::json_encodealt($v);
            return '{' . join(',', $result) . '}';
        }
    }

    public static function ValidateZipTable($zipcode) {
        return true;
        $zipoption = MMSettingsForm::getParam('zip_type');
        switch ((int) $zipoption) {
            case 0:
                $ziptable[] = MMSettingsForm::getParam('zip_self');
                break;
            case 1:
                $ziptable = unserialize(MMSettingsForm::getParam('zip_list'));
                break;
            case 2:
                $ziptable = unserialize(MMSettingsForm::getParam('zip_list'));
                $ziptable[] = MMSettingsForm::getParam('zip_self');
                break;
            default:
                $ziptable = unserialize(MMSettingsForm::getParam('zip_list'));
                $ziptable[] = MMSettingsForm::getParam('zip_self');
                break;
        }
        if (in_array($zipcode, $ziptable)) {
            return true;
        } else {
            if (strlen(strpos($zipcode, '-')) > 0) {
                $zipcode = substr($zipcode, 0, 5);
                if (in_array($zipcode, $ziptable)) {
                    return true;
                }
                else
                    return false;
            }
            return false;
        }
        return false;
    }

    public static function ValidateZipcodeByLocation($zipcode){
        $order = Helpers::getSessionOrder();
        $loc = MMLocation::model()->findByPk($order['location']);
        if($loc == NULL) return false;
        $ziparray = unserialize($loc->zip_list);
        if (in_array($zipcode, $ziparray)) {
            return true;
        } else {
            if (strlen(strpos($zipcode, '-')) > 0) {
                $zipcode = substr($zipcode, 0, 5);
                if (in_array($zipcode, $ziparray)) {
                    return true;
                }
                else
                    return false;
            }
            return false;
        }

        return false;
    }

    public static function GetHoursPopUp($location) {
        $location = MMLocation::model()->findByPk($location);
        if($location == NULL) return false;
        $worktime = unserialize($location->l_calendar);
        $daysofweek = array('0' => 'Sunday', '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday');
        $hours_array = Helpers::GetHours24();
        $data = ' Working Hours:<br>';
        foreach ($worktime as $key => $day) {
            if ($day['active'] == '1') {
                $data.='<b>' . $daysofweek[$key] . ':</b> ' . $hours_array[(int) $day['from']] . ' - ' . $hours_array[(int) $day['to']] . '<br>';
            }
        }
        $data.='';
        return $data;
    }

    public static function renderErrors($errors) {
        if (!empty($errors)) {
            echo '<div class="wpmm-form-error"><ul>';
            foreach ($errors as $error) {
                if (!empty($error)) {
                    if (is_array($error)) {
                        foreach ($error as $err) {
                            if (!empty($err))
                                echo '<li>' . $err . '</li>';
                        }
                    }
                    else
                        echo '<li>' . $error . '</li>';
                }
            }
            echo '</ul></div>';
        }
    }

    public static function renderInfo($messages) {
        echo '<div class="wpmm-form-info"><ul>';
        foreach ($messages as $message) {
            if (!empty($message))
                echo '<li>' . $message . '</li>';
        }
        echo '</ul></div>';
    }

    public static function randomPassword() {
        return substr(sha1(uniqid()), 0, 10);
    }

    public static function getTimezone($zone=null) {
        $timezone = array(
            'Australia/Adelaide'            => 'Australia - Adelaide',
            'Australia/Brisbane'            => 'Australia - Brisbane',
            'Australia/Broken_Hill'         => 'Australia - Broken Hill',
            'Australia/Currie'              => 'Australia - Currie',
            'Australia/Darwin'              => 'Australia - Darwin',
            'Australia/Eucla'               => 'Australia - Eucla',
            'Australia/Hobart'              => 'Australia - Hobart',
            'Australia/Lindeman'            => 'Australia - Lindeman',
            'Australia/Lord_Howe'           => 'Australia - Lord Howe',
            'Australia/Melbourne'           => 'Australia - Melbourne',
            'Australia/Perth'               => 'Australia - Perth',
            'Australia/Sydney'              => 'Australia - Sydney',
            'America/Atikokan'              => 'Canada - Atikokan',
            'America/Blanc-Sablon'          => 'Canada - Blanc-Sablon',
            'America/Cambridge_Bay'         => 'Canada - Cambridge Bay',
            'America/Creston'               => 'Canada - Creston',
            'America/Dawson'                => 'Canada - Dawson',
            'America/Dawson_Creek'          => 'Canada - Dawson Creek',
            'America/Edmonton'              => 'Canada - Edmonton',
            'America/Glace_Bay'             => 'Canada - Glace Bay',
            'America/Goose_Bay'             => 'Canada - Goose Bay',
            'America/Halifax'               => 'Canada - Halifax',
            'America/Inuvik'                => 'Canada - Inuvik',
            'America/Iqaluit'               => 'Canada - Iqaluit',
            'America/Moncton'               => 'Canada - Moncton',
            'America/Montreal'              => 'Canada - Montreal',
            'America/Nipigon'               => 'Canada - Nipigon',
            'America/Pangnirtung'           => 'Canada - Pangnirtung',
            'America/Rainy_River'           => 'Canada - Rainy River',
            'America/Rankin_Inlet'          => 'Canada - Rankin Inlet',
            'America/Regina'                => 'Canada - Regina',
            'America/Resolute'              => 'Canada - Resolute',
            'America/St_Johns'              => 'Canada - St Johns',
            'America/Swift_Current'         => 'Canada - Swift Current',
            'America/Thunder_Bay'           => 'Canada - Thunder Bay',
            'America/Toronto'               => 'Canada - Toronto',
            'America/Vancouver'             => 'Canada - Vancouver',
            'America/Whitehorse'            => 'Canada - Whitehorse',
            'America/Winnipeg'              => 'Canada - Winnipeg',
            'America/Yellowknife'           => 'Canada - Yellowknife',
            'Europe/London'                 => 'United Kingdom',
            'America/Adak'                  => 'USA - Adak',
            'America/Anchorage'             => 'USA - Anchorage',
            'America/Boise'                 => 'USA - Boise',
            'America/Chicago'               => 'USA - Chicago',
            'America/Denver'                => 'USA - Denver',
            'America/Detroit'               => 'USA - Detroit',
            'Pacific/Honolulu'              => 'USA - Honolulu',
            'America/Indiana/Indianapolis'  => 'USA - Indiana / Indianapolis',
            'America/Indiana/Knox'          => 'USA - Indiana / Knox',
            'America/Indiana/Marengo'       => 'USA - Indiana / Marengo',
            'America/Indiana/Petersburg'    => 'USA - Indiana / Petersburg',
            'America/Indiana/Tell_City'     => 'USA - Indiana / Tell City',
            'America/Indiana/Vevay'         => 'USA - Indiana / Vevay',
            'America/Indiana/Vincennes'     => 'USA - Indiana / Vincennes',
            'America/Indiana/Winamac'       => 'USA - Indiana / Winamac',
            'America/Juneau'                => 'USA - Juneau',
            'America/Kentucky/Louisville'   => 'USA - Kentucky / Louisville',
            'America/Kentucky/Monticello'   => 'USA - Kentucky / Monticello',
            'America/Los_Angeles'           => 'USA - Los Angeles',
            'America/Menominee'             => 'USA - Menominee',
            'America/Metlakatla'            => 'USA - Metlakatla',
            'America/New_York'              => 'USA - New York',
            'America/Nome'                  => 'USA - Nome',
            'America/North_Dakota/Beulah'   => 'USA - North Dakota / Beulah',
            'America/North_Dakota/Center'   => 'USA - North Dakota / Center',
            'America/North_Dakota/New_Salem'=> 'USA - North Dakota / New Salem',
            'America/Phoenix'               => 'USA - Phoenix',
            'America/Shiprock'              => 'USA - Shiprock',
            'America/Sitka'                 => 'USA - Sitka',
            'America/Yakutat'               => 'USA - Yakutat',
        );
        if (!empty($zone)) {
            return $timezone[$zone];
        } else {
           return $timezone;
        }
    }

    public static function getFirstLocation() {
        $val = MMLocation::model()->findall(array(
            'select' => 'id, name',
                ));
        $val = CHtml::listData($val, 'id', 'name');
        reset($val);
        return key($val);
    }

    public static function enumDropDownList($model, $attribute, $htmlOptions = array()) {
        return CHtml::activeDropDownList($model, $attribute, self::enumItem($model, $attribute), $htmlOptions);
    }

    public static function GetAddressList() {
        $order = Yii::app()->session['mm_order'];
        $list = array();
        if (!Yii::app()->user->isGuest) {
            $user = MMCustomer::model()->findByPk(Yii::app()->user->id);
            if ($user) {
                if ($user->addresses) {
                    foreach ($user->addresses as $address_tmp) {
                        $address = '';
                        if (!empty($address_tmp->address)) {
                            $address .= $address_tmp->address;
                            if (!empty($address_tmp->city))
                                $address .= ', ' . $address_tmp->city;
                            if (!empty($address_tmp->state))
                                $address .= ', ' . $address_tmp->state;
                            if (!empty($address_tmp->location))
                                $address .= ', ' . $address_tmp->location;
                        }
                        $list[$address_tmp->id] = $address;
                    }
                }
            }
        }
        if (isset($order['address'])) {
            foreach ($order['address'] as $addr) {
                $address = '';
                if (!empty($addr['address'])) {
                    $address.=$addr['address'];
                    if (!empty($addr['city']))
                        $address.=', ' . $addr['city'];
                    if (!empty($addr['state']))
                        $address.=', ' . $addr['state'];
                    if (!empty($addr['location']))
                        $address.=', ' . $addr['location'];
                }
                $list[$addr['id']] = $address;
            }
        }
        return $list;
    }

    public static function enumItem($model, $attribute) {
        $attr = $attribute;
        CHtml::resolveName($model, $attr);
        preg_match('/\((.*)\)/', $model->tableSchema->columns[$attr]->dbType, $matches);
        foreach (explode(',', $matches[1]) as $value) {
            $value = str_replace("'", null, $value);
            $values[$value] = $value;
        }
        return $values;
    }

    public static function enumItemArray($model, $attribute) {
        $attr = $attribute;
        CHtml::resolveName($model, $attr);
        $values[''] = 'Select...';
        preg_match('/\((.*)\)/', $model->tableSchema->columns[$attr]->dbType, $matches);
        foreach (explode(',', $matches[1]) as $value) {
            $value = str_replace("'", null, $value);
            $values[$value] = $value;
        }
        return $values;
    }

    public static function rmdirr($dirname, $root = '') {
        if (!file_exists($dirname))
            return false;
        if (is_file($dirname) || is_link($dirname))
            return unlink($dirname);
        $dir = dir($dirname);
        while (false !== $entry = $dir->read()) {
            if ($entry == '.' || $entry == '..')
                continue;
            self::rmdirr($dirname . DIRECTORY_SEPARATOR . $entry, $root);
        }
        $dir->close();
        if ($dirname == $root)
            return true;
        else
            return rmdir($dirname);
    }

    public static function activeCheckBoxListMany($model, $attribute, $data, $htmlOptions = array()) {
        CHtml::resolveNameID($model, $attribute, $htmlOptions);
        if (is_array($model->$attribute) && isset($htmlOptions['attributeitem']) && $htmlOptions['attributeitem']) {
            $selection = array();
            foreach ($model->$attribute as $a)
                $selection[] = $a->{$htmlOptions['attributeitem']};
        } else {
            $selection = $model->$attribute;
        }
        if ($model->hasErrors($attribute))
            self::addErrorCss($htmlOptions);
        $name = $htmlOptions['name'];
        unset($htmlOptions['name']);
        if (array_key_exists('uncheckValue', $htmlOptions)) {
            $uncheck = $htmlOptions['uncheckValue'];
            unset($htmlOptions['uncheckValue']);
        }
        else
            $uncheck = '';
        $hiddenOptions = isset($htmlOptions['id']) ? array('id' => CHtml::ID_PREFIX . $htmlOptions['id']) : array('id' => false);
        $hidden = $uncheck !== null ? CHtml::hiddenField($name, $uncheck, $hiddenOptions) : '';
        return $hidden . CHtml::checkBoxList($name, $selection, $data, $htmlOptions);
    }

    public static function getSessionOrder() {
        return Yii::app()->session['mm_order'];
    }

    public static function setSessionOrder(array $order) {
        Yii::app()->session['mm_order'] = $order;
    }

    public static function arrayToFloat($array) {
        if (is_null($array))
            return;
        foreach ($array as $key => $data)
            if (!empty($data))
                $float_array[$key] = array('price'=>(float) $data['price'],'size'=>(int) $data['size']);
        return $float_array;
    }

    public static function addSessionItem(array $order, array $post) {
        $order['items'][] = array(
            'id' => (int) $post['id'],
            'quantity' => (int) $post['quantity'],
            'price' => (float) $post['price'],
            'instructions' => trim(strip_tags($post['instructions'])),
            'attributes' => Helpers::arrayToFloat($post['attr']),
            'cut' => $post['cut']
        );
        return $order;
    }

    public static function getItemById($id) {
        return MMItem::model()->findByPk($id);
    }

    public static function getAttrById($id) {
        return MMAttribute::model()->findByPk($id);
    }

    public static function addSessionAddress($order, array $post) {
        $address_id = '';
        if (!isset($post['id'])) {
            $address_id = uniqid('address');
            $order['address'][] = array(
                'id' => $address_id,
                'city' => trim(strip_tags($post['city'])),
                'state' => trim(strip_tags($post['state'])),
                'address' => trim(strip_tags($post['address'])),
                'location' => trim(strip_tags($post['location'])),
            );
        } else {
            $address_id = trim(strip_tags($post['id']));
            foreach ($order['address'] as $key => $addrr) {
                if ($addrr['id'] == $address_id) {
                    $order['address'][$key] = array(
                        'id' => $address_id,
                        'city' => trim(strip_tags($post['city'])),
                        'state' => trim(strip_tags($post['state'])),
                        'address' => trim(strip_tags($post['address'])),
                        'location' => trim(strip_tags($post['location'])),
                    );
                    break;
                }
            }
        }
        $order['cart_form']['address'] = 'wppp' . $address_id;
        return $order;
    }

    public static function itemCountTotal($key, $float = false) {
        $order = Helpers::getSessionOrder();
        $id = $order['items'][$key]['id'];
        $item = MMItem::model()->with(array(
                    'params' => array(
                        'together' => true,
                        'with' => array(
                            'attribs' => array(
                                'together' => true
                            )
                        )
                    )
                ))->findByPk($id);
        $price = (float) $item->price;
        if (is_array($order['items'][$key]['attributes'])) {
            foreach ($item->params as $param) {
                foreach ($param->attribs as $attribute) {
                    if (array_key_exists((int) $attribute->id, $order['items'][$key]['attributes']))
                            if($param->type == 2){
                                if($order['items'][$key]['attributes'][$attribute->id]['size'] == 3 || $order['items'][$key]['attributes'][$attribute->id]['size'] == 2)
                                    $price += ((float) $attribute->price)/2;
                                else
                                    $price += ((float) $attribute->price);
                            } else {
                                $price += (float) $attribute->price;
                            }
                }
            }
        }
        return $float ? $price : number_format($price, 2);
    }

    public static function deleteSessionItem($id) {
        $order = Yii::app()->session['mm_order'];
        unset($order['items'][$id]);
        Yii::app()->session['mm_order'] = $order;
        return !isset($order['items'][$id]);
    }

    public static function getDiscountInfo($calculate = false, $number = false) {
        $discount = (float) MMSettingsForm::getParam('discount');
        if ($calculate) {
            $value = $discount / 100 * Helpers::getOrderSubtotal();
            if ($number)
                return $value;
            $info = 'Discount (' . number_format($discount, 2) . '%): <strong>-$' . number_format($value, 2);
        } else {
            $info = 'Discount: <strong>-' . number_format($discount, 2) . '%';
        }
        $info .= '</strong>';
        return $info;
    }

    public static function getTaxInfo($calculate = false, $number = false) {
        $taxrate = (float) MMSettingsForm::getParam('tax_rate');
        if ($calculate) {
            $value = $taxrate / 100 * (Helpers::getOrderSubtotal() - Helpers::getDiscountInfo(true, true));
            if ($number)
                return $value;
            $info = 'Tax (' . number_format($taxrate, 2) . '%): <strong>$' . number_format($value, 2);
        } else {
            $info = 'Tax: <strong>' . number_format($taxrate, 2) . '%';
        }
        $info .= '</strong>';
        return $info;
    }

    public static function getDeliveryInfo($number = false) {
        $deliverycharge = (float) MMSettingsForm::getParam('delivery_charge');
        if ($number)
            return $deliverycharge;
        else {
            $info = 'Delivery charge: <strong>$' . number_format($deliverycharge, 2);
        }
        $info .= '</strong>';
        return $info;
    }

    public static function getTipInfo($calculate = false, $number = false) {
        $order = Helpers::getSessionOrder();
        $info = 'Tip: <strong>';
        if ($calculate) {
            switch ($order['cart_form']['payment-tip-type']) {
                case 'percent':
                    $info = 'Tip (' . number_format((float) $order['cart_form']['payment-tip-value'], 2) . '%): <strong>';
                    $value = (float) $order['cart_form']['payment-tip-value'] / 100 * Helpers::getOrderSubtotal();
                    $info .= '$' . number_format($value, 2);
                    break;
                case 'amount':
                    $value = (float) $order['cart_form']['payment-tip-value'];
                    $info .= '$' . number_format($value, 2);
                    break;
            }
            if ($number)
                return $value;
        } else {
            switch ($order['cart_form']['payment-tip-type']) {
                case 'percent':
                    $info .= number_format((float) $order['cart_form']['payment-tip-value'], 2) . '%';
                    break;
                case 'amount':
                    $info .= '$' . number_format((float) $order['cart_form']['payment-tip-value'], 2);
                    break;
            }
        }
        $info .= '</strong>';
        return $info;
    }

    public static function getOrderSubtotal() {
        $order = Helpers::getSessionOrder();
        $subtotal = 0.00;
        foreach ($order['items'] as $key => $item) {
            $subtotal += Helpers::itemCountTotal($key, true) * (int) $item['quantity'];
        }
        return $subtotal;
    }

    public static function getOrderTotal($number = false) {
        $order = Helpers::getSessionOrder();
        $total = Helpers::getOrderSubtotal();
        $params = MMSettingsForm::getParams();
        if ($params['discount'] > 0) {
            $total -= Helpers::getDiscountInfo(true, true);
        }
        if ($params['tax_rate'] > 0) {
            $total += Helpers::getTaxInfo(true, true);
        }
        if ($order['cart_form']['payment-tip-value'] > 0) {
            $total += Helpers::getTipInfo(true, true);
        }
        if ($order['cart_form']['delivery-type'] == 'Delivery') {
            $total += Helpers::getDeliveryInfo(true);
        }
        return $number ? (float) $total : number_format((float) $total, 2);
    }

    public static function getWorkingHours($time_string,$location_id) {
        $timestamp = strtotime($time_string);
        if (!$timestamp)
            return 'Incorrect delivery time.';
        $dayofweek = (int) date('w', $timestamp);
        $location = MMLocation::model()->findByPk($location_id);
        if($location == NULL) return false;
        $worktime = unserialize($location->l_calendar);
        $hours_array = Helpers::GetHours24();
        return '<div style="text-align: center; margin-left: -10px">
                Selected date working hours:<br>' . $hours_array[(int) $worktime[$dayofweek]['from']] . ' - ' . $hours_array[(int) $worktime[$dayofweek]['to']] .
            '</div>';
        ;
    }

    public static function validateWorkingHours($time_string,$location_id) {
        $timestamp = strtotime($time_string);
        if (!$timestamp)
            return false;
        if ($timestamp < time())
            return false;
        $hour = date('H:i:s', $timestamp);
        $dayofweek = (int) date('w', $timestamp);
        $location = MMLocation::model()->findByPk($location_id);
        if($location == NULL) return false;
        $worktime = unserialize($location->l_calendar);
        $hours = self::GetHours24();
        if (strtotime($hours[$worktime[$dayofweek]['from']]) > strtotime($hours[$worktime[$dayofweek]['to']]))
            $overnight = true;
        elseif ($worktime[$dayofweek]['from'] == $worktime[$dayofweek]['to'])
            return true;
        if (isset($overnight)) {
            if ($worktime[$dayofweek]['active'] && (strtotime($hours[$worktime[$dayofweek]['from']]) <= strtotime($hour) || strtotime($hours[$worktime[$dayofweek]['to']]) > strtotime($hour)))
                return true;
        } else {
            if ($worktime[$dayofweek]['active'] && strtotime($hours[$worktime[$dayofweek]['from']]) <= strtotime($hour) && strtotime($hours[$worktime[$dayofweek]['to']]) > strtotime($hour))
                return true;
        }
        return false;
    }

    public static function validateItemTime($id) {
        $valid = false;
        $item = MMItem::model()->with(array(
                    'categories' => array(
                        'together' => true,
                        'with' => array(
                            'menus' => array(
                                'together' => true
                            )
                        )
                    )
                ))->findByPk($id);
        foreach ($item->categories as $category) {
            foreach ($category->menus as $menu) {
                $menu_ids[] = $menu->id;
            }
        }
        $order = Helpers::getSessionOrder();
        if(isset($order['cart_form']['order-time']) && $order['cart_form']['delivery-time'] != 'asap'){
            $hour_now = date('H:i:s',strtotime($order['cart_form']['order-time']));
        } else {
            $hour_now = date('H:i:s', time());
        }
        $menus = MMMenu::model()->findAllByPk(array_unique($menu_ids));
        $hours = self::GetHours();
        foreach ($menus as $menu) {
            if ($menu->time_from == $menu->time_to) {
                $valid = true;
            } elseif ($menu->published && strtotime($hours[$menu->time_from]) <= strtotime($hour_now) && strtotime($hours[$menu->time_to]) > strtotime($hour_now)) {
                $valid = true;
            }
        }
        return $valid;
    }

    public static function sendOrderToEmail($order,$email, $location,$full=false){
       YiiBase::import('ext.YiiMail.YiiMailMessage');
       $shopemail = $location->l_email;
       if(empty($shopemail)) $shopemail='noemail@noemail.mail';
       $shopname = $location->name;
       if(empty($shopname)) $shopname='Unknown shop';
       $message = new YiiMailMessage;
       $message->setBody(Yii::app()->controller->renderPartial('//admin/_orderblank', array('model' => $order, 'full' => $full), true).self::shopDetails($location), 'text/html', 'utf-8');
       $message->addTo($email);
       $message->setFrom(array($shopemail => $shopname));
       $message->setReplyTo(array($shopemail => $shopname));
       $message->setSubject("Order N-" . $order->id . " information");
       Yii::app()->mail->send($message);
    }

    public static function sendRegisterMessage($user, $pass, $location) {
        YiiBase::import('ext.YiiMail.YiiMailMessage');
        $shopemail = $location->l_email;
        if(empty($shopemail)) $shopemail='noemail@noemail.mail';
        $shopname = $location->name;
        if(empty($shopname)) $shopname='Unknown shop';
        $message = new YiiMailMessage;
        $message->setBody('<b>Hello, '.$user->name.'</b><br>You have registered on to: <b>' . $shopname . '</b> by submiting order from us. Your account is<br>Username: ' . $user->c_mail . '<br>Password: ' . $pass . '<b>Thank you for using our service!</b><br>' . self::shopDetails($location), 'text/html', 'utf-8');
        $message->addTo($user->c_mail);
        $message->setFrom(array($shopemail => $shopname));
        $message->setReplyTo(array($shopemail => $shopname));
        $message->setSubject("Registration to: " . $shopname);
        Yii::app()->mail->send($message);
    }

    public static function shopDetails($location){
        $message='<br><hr><br>If you have questions, ask us by any of this contacts:';
        $email=$location->l_email;
        $phone=$location->phone;
        $fax=$location->fax;
        if(!empty($email))
            $message.='<br><b>Email: </b>'.$email;
        if(!empty($phone))
            $message.='<br><b>Phone: </b>'.$phone;
        if(!empty($fax))
            $message.='<br><b>Fax: </b>'.$fax;
        return $message;
    }

    public static function renderSelectedAttribute($attribute, $property = 'abc') {
        $slicearray = array(3=>'left',1=>'full',2=>'right');
        global $selected_attrs;
        global $first;
        if ($first) {
            $selected_attrs = '<div id="selected-attributes">
                    <div class="wpmm-form-info">
                    <p>Selected options:</p>
                    <table>';
            $first = false;
        }
        if(is_numeric($property)){
            if($property == 3 || $property == 2) $divider = 2; else $divider = 1;
            $selected_attrs .=
                '<tr>
                    <td>' . $attribute->name . ' ( ' . $slicearray[$property] . ' )</td>' .'
                    <td width="65" class="selected-attr-price">' . ((substr($attribute->price, 0, 1) === '-') ? '- $' . substr($attribute->price, 1) : '+ $' . $attribute->price/$divider) . '</td>
                </tr>';
        } else {
            $selected_attrs .=
                '<tr>
                    <td>' . $attribute->name . '</td>' .'
                    <td width="65" class="selected-attr-price">' . ((substr($attribute->price, 0, 1) === '-') ? '- $' . substr($attribute->price, 1) : '+ $' . $attribute->price) . '</td>
                </tr>';
        }
    }

}