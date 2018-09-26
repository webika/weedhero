<?php

class WorkController extends CController {
    public $layout = 'work';
    public $returnObj = array();
    public $dispensaryInfo = array();
    public $username;
    public $password;
    public $_identity;
    public $c_mail;
    public $name;
    public $surname;
    public $salt;
    public $phone;
    public $MM_CSRF_TOKEN;
    public $newAddress = false;

    //ORDER ++++++++++++++++++++++++++++++++++++++++++++++

    public $order;
    public $orderPlaced;
    
    //order basic information
      
    public $time_ordered;
    public $time_delivery;
    public $payment_type;
    public $payment_status;
    public $payment;
    public $delivery_type;
    public $tip_type;
    public $tip_amount;
    public $tip;
    public $promo;
    public $notes;
    public $params;
    public $total_sum;

    //GENERAL INFO
    public $customer_id;
    public $address_id;
                
    //ORDER ITEMS
    public $itemid;
    public $item_name;
    public $itemAll = array();
    public $item_price;
    public $instructions;
    public $attributes_Item;
    public $tempOrderId;


    //ORDER ADDRESS
    public $city;
    public $state;    
    public $address;
    public $location;

    //TEMP
    public $orderTempId;


    public $zip_type;
    public $zipcode;
    public $tax_rate;
    public $delivery_charge;
    public $discount;
    public $min_order;
    public $work_time;
    public $admin_email;
    public $footer_text;
    public $header_text;
    public $infobox;
    public $infoboxcontent;
    public $ukformat;
    public $maxphone;
    public $indzip;
    public $indminorder;
    public $delivery_time;
    public $enable_tips;
    public $single_menu;

   ///ORDER ++++++++++++++++++++++++++++++++++++++++++++++END


    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            array(
                'RestfullYii.filters.ERestFilter + 
			 	    REST.GET, REST.PUT, REST.POST, REST.DELETE, REST.OPTIONS'
            ),
        );
    }

    public function actions()
    {
        return array(
            'REST.'=>'RestfullYii.actions.ERestActionProvider',

        );
    }

    public function accessRules()
    {
        return array(
          
             array('allow', 'actions'=>array('REST.GET', 'REST.PUT', 'REST.POST', 'REST.DELETE'),
            'users'=>array('*'),
            ),

            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionForgotpassword()
    {
        $test = '';
    }
    public function restEvents()

     {

        // $this->onRest('req.cors.access.control.allow.origin', function() {
		// 	return ['http://localhost']; //List of sites allowed to make CORS requests 
		// });
		
		// $this->onRest('req.cors.access.control.allow.methods', function() {
		// 	return ['GET', 'POST', 'PUT', 'DELETE']; //List of allowed http methods (verbs) 
        // });
        
       
        // $this->onRest('post.filter.req.auth.ajax.user', function($validation) {
        //     return $validation; //Bool
        // });
      
        

//CHECKOUT PROCESS / PLACING AN ORDER
$this->onRest('req.post.loginaccount.render',function($data){
             
    if(isset($data)) {    

        //collecting the data from POST
        $this->c_mail = $data['c_mail'];
        $this->password = $data['password'];
        $csrfTokenName = Yii::app()->request->csrfTokenName;
        $csrfToken = Yii::app()->request->csrfToken;
        $this->MM_CSRF_TOKEN = $csrfToken;
//        $this->MM_CSRF_TOKEN = $data['MM_CSRF_TOKEN'];
    
    
        
        //validating and checking if the customer exist
        //if not creating new user
       
    $model = MMCustomer::model()->find(array('condition' => 'c_mail="' . $this->c_mail . '"'));
    
    //If user not exist create a new one
    if($model == null) {
            
       
            echo CJSON::encode($model->errors);
            echo CJSON::encode(['success'=>false,'message'=>'User is not found']);
        
        //user exist validate, login and send responce
        } else  {
    
            
           // $tempdata =array();
            $temppdata = $data;
            $model = new MMLoginForm();
            $tempassword = $temppdata['password'];
            $tempusername = $temppdata['c_mail'];
    
                        
           
            if(!$model->authenticate($tempusername,$tempassword) && $model->login()) {
            
           echo CJSON::encode($model->errors); 
           echo CJSON::encode(['success'=>false,'message'=>'Failed Attempt']);
              } else {
                $kostya = MMCustomer::model()->find(array('condition' => 'c_mail="' . $this->c_mail . '"'));
                $pkKostya = $kostya['id'];
                $kostyaWithAddress = MMAddress::model()->findAll(array('condition' => 'customer_id="' . $pkKostya . '"'));
  
                $userInfo = (object)[
                    'user' => $kostya,
                    'address' => $kostyaWithAddress
                ];
                echo CJSON::encode($userInfo);
           // echo CJSON::encode(['success'=>true,'message'=>'Authentificated']);
    
            };
        };
    
    } else {
       
        $errores = isset($model)? $model->getErrors() : [] ;
        echo CJSON::encode("nemae danuh z POST requesta" . $errores);
    
    };
        
});

$this->onRest('req.post.register.render',function($data){
             
    if(isset($data)) {    

    //collecting the data from POST
    $this->name = $data['name'];
    $this->c_mail = $data['c_mail'];
    $this->phone = $data['phone'];
    $this->surname = $data['surname'];
    $this->password = $data['password'];
    $csrfTokenName = Yii::app()->request->csrfTokenName;
    $csrfToken = Yii::app()->request->csrfToken;
    echo CJSON::encode($csrfTokenName);
    echo CJSON::encode($csrfToken);

    $this->MM_CSRF_TOKEN = $csrfToken;


    
    //validating and checking if the customer exist
    //if not creating new user
   
$model = MMCustomer::model()->find(array('condition' => 'c_mail="' . $this->c_mail . '"'));

//If user not exist create a new one
if($model == null) {
        
        $model = new MMCustomer();
        $model->name = $this->name;
        $model->c_mail = $this->c_mail;
        $model->phone = $this->phone;
        $model->surname = $this->surname;
        //$model->salt = md5('Mithrandir99');
        $model->password = $model->hashPassword($this->password, $this->salt); 
        $model->save();
        $errores = $model->getErrors();
        echo CJSON::encode($data);
        echo CJSON::encode($errores);
        echo CJSON::encode($model->attributes);
        echo CJSON::encode(['success'=>true,'message'=>'Created new user']);
    
    //user exist validate, login and send responce
    } else  {

        
       // $tempdata =array();
        $temppdata = $data;
        $model = new MMLoginForm();
        $tempassword = $temppdata['password'];
        $tempusername = $temppdata['c_mail'];

                    
       
if(!$model->authenticate($tempusername,$tempassword) && $model->login()) {
        
            echo CJSON::encode($data);

            

            echo CJSON::encode(['success'=>false,'message'=>'Failed Attempt']);
} else {
        echo CJSON::encode($data);
        echo CJSON::encode(['success'=>true,'message'=>'Authentificated']);

};
    };

} else {
   
    $errores = $model->getErrors();
    echo CJSON::encode("nemae danuh z POST requesta" . $errores);

};

});



$this->onRest('req.post.forgotpassword.render',function($data){
             
    if(isset($data)) {    
    
    
    //CUSTOMER INFORMATION
    $this->c_mail = $data['c_mail'];
    $this->password = $data['password'];


    $username = trim(strip_tags($this->c_mail));
    $validator = new CEmailValidator;
        if ($validator->validateValue($username)) {
            $user = MMCustomer::model()->find(array('condition' => 'c_mail="' . $username . '"'));
            if ($user == NULL) {
                echo CJSON::encode('Invalid user email address!');
            } else {
                $newpass = Helpers::randomPassword();
                $user->password = $newpass;
                if ($user->save()) {
                    // YiiBase::import('ext.YiiMail.YiiMailMessage');
                    // $shopemail = MMSettingsForm::getParam('vendor_email');
                    // $shopname = MMSettingsForm::getParam('vendor_name');
                    // $message = new YiiMailMessage;
                    // $message->setBody(Yii::t('_', '<b>Good Day</b><br>You requested password resent from our service. Your new password is: <br><b>') . $newpass . Yii::t('_','</b><br>Thank you for using our service!') . Helpers::shopDetails(), 'text/html', 'utf-8');
                    // $message->addTo($user->c_mail);
                    // $message->setFrom(array($shopemail => $shopname));
                    // $message->setReplyTo(array($shopemail => $shopname));
                    // $message->setSubject("Password reset for: " . $user->c_mail);
                    // Yii::app()->mail->send($message);
                    echo CJSON::encode($newpass);
                    echo CJSON::encode('New password has been successfully sent to your email');
                }
            }
        } else {
            echo CJSON::encode( 'Not valid user e-mail!') . $username;
        }
    
    
}
        
});

        $this->onRest('req.post.checkout.render',function($data){
             
            if(isset($data)) {    
            
            
            //CUSTOMER INFORMATION
            $this->name = $data['name'];
            $this->c_mail = $data['c_mail'];
            $this->phone = $data['phone'];
            $this->surname = $data['surname'];
            $this->password = $data['password'];

            //ORDER INFORMATION
            $this->time_ordered = $data['time_ordered'];
            $this->time_delivery = $data['time_delivery'];
            $this->payment_type = $data['payment_type'];
            $this->payment_status = $data['payment_status'];
            $this->payment = $data['payment'];
            $this->delivery_type = $data['delivery_type'];
            $this->tip_type = $data['tip_type'];
            $this->tip_amount = $data['tip_amount'];
            $this->tip = $data['tip'];
            $this->promo = $data['promo'];
            $this->notes = $data['notes'];
            $this->params = $data['params'];
            $this->total_sum = $data['total_sum'];
        
            //GENERAL INFO
            // $this->customer_id = $data['customer_id'];
            // $this->address_id = $data['address_id'];
                        
            //ORDER ITEMS
            //$this->order_id = $data['order_id'];
            $this->itemid = $data['itemid'];
            $this->item_name = $data['item_name'];
            $this->item_price = $data['item_price'];
            $this->instructions = $data['instructions'];
            $this->attributes_Item = $data['attributes_Item'];

            $this->itemAll = $data['itemAll'];
        
            //ORDER ADDRESS
            $this->city = $data['city'];
            $this->state = $data['state'];
            $this->address = $data['address'];
            $this->location = $data['location'];



            
//$this->orderTempId = Yii::app()->db->createCommand()->select('max(id) as max')->from('MM_Orders')->queryScalar();    
         
            $orderPlaced = new MMOrder();
            //$orderPlaced->id = $this->orderTempId + 1;
            $orderPlaced->time_ordered =  $this->time_ordered;
            $orderPlaced->time_delivery = $this->time_delivery;
            $orderPlaced->payment_type = $this->payment_type;
            $orderPlaced->payment_status = $this->payment_status;
            $orderPlaced->payment = $this->payment;

            //Delivery type validation
            $orderPlaced->delivery_type = $this->delivery_type;
            $orderPlaced->tip_type = $this->tip_type;
            $orderPlaced->tip = $this->tip;
            $orderPlaced->promo_code = $this->promo;
            $orderPlaced->notes = $this->notes;
            $orderPlaced->params = $this->params;
            $orderPlaced->total_sum = $this->total_sum;
            $orderPlaced->trans_id = "";

                        
    //finding customer id or creating a new one
   $customerModel = MMCustomer::model()->find(array('condition' => 'c_mail="' . $this->c_mail . '"'));
            
            if($customerModel == null) {

                $model = new MMCustomer();
                $model->name = $this->name;
                $model->c_mail = $this->c_mail;
                $model->phone = $this->phone;
                $model->surname = $this->surname;
                $model->salt = md5('Mithrandir99');
                $model->password = $model->hashPassword($this->password, $this->salt); 
                $model->save();
                echo CJSON::encode(['success'=>true,'message'=>'Created new user']);

                $customerIdfromOrders = Yii::app()->db->createCommand()->select('max(id) as max')->from('mm_customers')->queryScalar();
                $orderPlaced->customer_id = $customerIdfromOrders;

            } else {
             
                $model = new MMLoginForm();
                $tempassword = $this->password;
                $tempusername = $this->c_mail;
                if(!$model->authenticate($tempusername,$tempassword) && $model->login()) {                
                    echo CJSON::encode(['success'=>false,'message'=>'Failed Attempt']);
                    echo CJSON::encode($model->errors);
                    
                } else {
                
                echo CJSON::encode(['success'=>true,'message'=>'Authentificated']);

                                        }

                $orderPlaced->customer_id = $customerModel->id;
            }

           
            
            $addressmodel = new MMAddress();
            $addressmodel->city = $this->city;
            $addressmodel->state = $this->state;
            $addressmodel->address = $this->address;
            $addressmodel->location = $this->location;
            
            $this->tempOrderId = Yii::app()->db->createCommand()->select('max(id) as max')->from('mm_orders')->queryScalar();    

            //Manually assigning order id in ADDRESS table
            $addressmodel->order_id = $this->tempOrderId + 1;
             if(!$customerModel == null) {
                 //Assigning customer id in address table
                $addressmodel->customer_id = $customerModel->id;
             } else {

                $tempCustomerId = Yii::app()->db->createCommand()->select('max(id) as max')->from('mm_addresses')->queryScalar();    
                $addressmodel->customer_id = $tempCustomerId + 1;
                
            }
           
            $addressmodel->save(); 
            echo CJSON::encode($addressmodel->errors);

            
            $addresstest = Yii::app()->db->createCommand()->select('max(id) as max')->from('mm_addresses')->queryScalar();
            

            $orderPlaced->address_id = $addresstest;
          
            foreach($this->itemAll as $itemOrder) {
            
            $new_order_item = new MMOrderItem();

            $attribs = array();    

 


           // $nempOrderId = Yii::app()->db->createCommand()->select('max(id) as max')->from('mm_order_items')->queryScalar();    
            
            $nempOrderId = Yii::app()->db->createCommand()->select('max(id) as max')->from('mm_orders')->queryScalar();
           
           
            $new_order_item->order_id = $nempOrderId + 1;
            $new_order_item->itemid = $itemOrder['itemid'];
            $new_order_item->item_name = $itemOrder['item_name'];
            $new_order_item->item_price = $itemOrder['item_price'];
            $new_order_item->instructions = $itemOrder['instructions'];
            $attribs['quantity'] = (int) $itemOrder['quantity'];
            
            
            if (count($itemOrder['attributes'])) {
                   foreach ($itemOrder['attributes'] as $attr_id => $attr_price) {
                       $attrrObj5 = (object) $attr_price;  
                       var_dump($attrrObj5);
                    $attr = Helpers::getAttrById((int)$attrrObj5->id);
                    var_dump($attrrObj5->id);
                    $attribs[] = array('orig_id'=>$attr->id, 'name' => $attr->name, 'price' => (float) $attr->price);
                      }
                  }
            $new_order_item->attribs = serialize($attribs);
            
            echo CJSON::encode( "Testing with Irene All attributes  " . $attribs );
           // echo CJSON::encode( "Testing with Irene Attributes  " . $attr_id );
                if (!$new_order_item->save()) {
                    echo CJSON::encode($new_order_item->errors);
             
                                        } else {
                                            
                                            echo CJSON::encode('Saved order items');
                                        }
  
                        
  }

  $orderPlaced->save();
  //Helpers::sendOrderToEmail($orderPlaced, "kostya.yaremtso@gmail.com", true);
  echo CJSON::encode($orderPlaced->errors);
             
             } else {
                               
              echo CJSON::encode("Not items where passed in the order");
             
            }
                  
    }); 
        
    $this->onRest('req.get.dispensary.render', function($result){
            
            $menus = MMSettingsForm::getParams();

            $criteria = new CDbCriteria;
            $criteria->order = 'id asc';
            $criteria->limit = '1';
            $locations = MMLocation::model()->findAll($criteria);
            if(count($locations) > 0) {
                $location = $locations[0];
                $zipList = (!empty($location->zip_list))? unserialize($location->zip_list) : [] ;
                $address = explode(',',$location->address);
                $menus['vendor_street'] = isset($address[0])? trim($address[0]) : '' ;
                $menus['vendor_city'] = isset($address[1])? trim($address[1]) : '' ;
                $menus['vendor_state'] = isset($address[2])? trim($address[2]) : '' ;
                $menus['vendor_phone'] = $location->phone;
                $menus['vendor_email'] = $location->l_email;
                $menus['indzip'] = isset($zipList[0])? $zipList[0] : '';
                $menus['zipcode'] = $menus['indzip'];
                $menus['vendor_name'] = $location->name;
            }

            $this->dispensaryInfo['name'] = $menus['app_name'];
            $this->dispensaryInfo['appLogo'] = $menus['logo_image'];
            $this->dispensaryInfo['city'] = $menus['vendor_city'];
            $this->dispensaryInfo['email'] = $menus['vendor_email'];
            $this->dispensaryInfo['dispensaryName'] = $menus['vendor_name'];
            $this->dispensaryInfo['phone'] = $menus['vendor_phone'];
            $this->dispensaryInfo['state'] = $menus['vendor_state'];
            $this->dispensaryInfo['street'] = $menus['vendor_street'];
            $this->dispensaryInfo['tax_rate'] = $menus['tax_rate'];
            $this->dispensaryInfo['delivery_charge'] = $menus['delivery_charge'];
            $this->dispensaryInfo['discount'] = $menus['discount'];
            $this->dispensaryInfo['min_order'] = $menus['min_order'];
            $this->dispensaryInfo['admin_email'] = $menus['admin_email'];
            $this->dispensaryInfo['footer_text'] = $menus['footer_text'];
            $this->dispensaryInfo['header_text'] = $menus['header_text'];
            $this->dispensaryInfo['infoboxcontent'] = $menus['infoboxcontent'];

            $this->dispensaryInfo['indzip'] = $menus['indzip'];
            $this->dispensaryInfo['indminorder'] = $menus['indminorder'];

            $working_hours = unserialize($menus['work_time']);
            $daysofweek = array('0' => 'Sunday', '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday');
            $hours_array = Helpers::GetHours();
            
            foreach ($working_hours as $key => $day) {
                if ($day['active'] == '1') {
                    if($hours_array[(int) $day['from']] == $hours_array[(int) $day['to']]){
                        $data.= $daysofweek[$key] . ' 24 hrs : ';
                    } else
                        $data.=  $daysofweek[$key] .  $hours_array[(int) $day['from']] . ' - ' . $hours_array[(int) $day['to']] . '<br>';
                }
            }
            $data.=''; 

            $this->dispensaryInfo['work_time'] = explode(":", $data);

            $delivery_hours = unserialize($menus['delivery_time']);
            $daysofweek = array('0' => 'Sunday', '1' => 'Monday', '2' => 'Tuesday', '3' => 'Wednesday', '4' => 'Thursday', '5' => 'Friday', '6' => 'Saturday');
            $hours_array = Helpers::GetHours();
            if(is_array($delivery_hours)) {
                foreach ($delivery_hours as $key => $day) {
                    if ($day['active'] == '1') {
                        if ($hours_array[(int)$day['from']] == $hours_array[(int)$day['to']]) {
                            $delivery_data .= $daysofweek[$key] . ' 24 hrs :';
                        } else
                            $delivery_data .= $daysofweek[$key] . $hours_array[(int)$day['from']] . ' - ' . $hours_array[(int)$day['to']] . '<br>';
                    }
                }
            }
            $delivery_data.='';
           


            $this->dispensaryInfo['delivery_time'] = explode(":", $delivery_data);
            $this->dispensaryInfo['zipcode'] = $menus['zipcode'];

            $this->dispensaryInfo['enable_tips'] = $menus['enable_tips'];

            return json_encode($this->dispensaryInfo, true);
        });


        
        $this->onRest('req.get.items.render', function($result){

           $criteria = new CDbCriteria();
           $menuModel = MMMenu::model();
           if ($menuModel->findByPk(Yii::app()->params['menu'])) {
            $criteria->with = array(
            'categories' => array(
                        'alias' => 'categories',
                        'condition' => 'categories.published = 1',
                        'order' => 'categories.' . MMSettingsForm::getParam('cat_sort_field') . ' ' . MMSettingsForm::getParam('cat_sort_order'),
                        'together'=>true,
                        'with'=> array (
                        'items' => array(
                            'alias' => 'items',
                            'condition' => 'items.published = 1',
                            'order' => 'items.' . MMSettingsForm::getParam('item_sort_field') . ' ' . MMSettingsForm::getParam('item_sort_order'),
                            'together'=>true,
            ))));
            $criteria->alias = 'menu';
            $criteria->condition = 'menu.id =' . Yii::app()->params['menu'];
            $this->single_menu = true;
        }
        else {
            $criteria->with = array(
            'categories' => array(
                        'alias' => 'categories',
                        'condition' => 'categories.published = 1',
                        'order' => 'categories.' . MMSettingsForm::getParam('cat_sort_field') . ' ' . MMSettingsForm::getParam('cat_sort_order'),
                        'together'=>true,
                        'with'=> array (
                        'items' => array(
                            'alias' => 'items',
                            'condition' => 'items.published = 1',
                            'order' => 'items.' . MMSettingsForm::getParam('item_sort_field') . ' ' . MMSettingsForm::getParam('item_sort_order'),
                            'together'=>true,
            ))));
            $criteria->alias = 'menus';
            $criteria->condition = "menus.published = 1";
            $criteria->order = 'menus.sort_order DESC';
        }

            $menus = MMMenu::model()->findAll($criteria);

            foreach ($menus as $key => $menu) {
                if ($menu->published) {
                   array_push($this->returnObj, $menu);
                   //create array for categories
                   $newArr = array();
                   foreach($menu->categories as $k => $category){
                    array_push($newArr, $category);
                    $this->returnObj[$key]->categoriesRest = $newArr;
                    //create array for all items
                    $newItemArr = array();
                    foreach ($category->items as $item){

                    $commandGroup = Yii::app()->db->createCommand()
                        ->select('id, item_id, name')
                        ->from('mm_groups')
                        ->where('mm_groups.item_id = ' . "{$item['id']}")
                        ->queryAll();
                                         
                     $command = Yii::app()->db->createCommand()
                        ->select('mm_groups.id, mm_groups.item_id, mm_groups.name, mm_attributes.id, mm_attributes.name, mm_attributes.price, mm_attributes.group_id')
                        ->from('mm_groups')
                        ->where('mm_groups.item_id = ' . "{$item['id']}")
                        ->join('mm_attributes', 'mm_groups.id = mm_attributes.group_id')
                        ->queryAll();
                                           

                    //     $groupId = MMGroup::model()->findAll($item['id']);
                    //     $itemsAttributes = MMAttribute::model()->findAll($groupId['id']);
                         $item->attributes = $commandGroup;
                         $item->attributes[0]['Options'] = $command;
                        // $item->attributes = $command; 
                    $test = '';
                    array_push($newItemArr, $item);
                    $test = '';
                   // $this->returnObj[$key]->itemList = $newItemArr;
                   
                    }
                    $this->returnObj[$key]->categoriesRest[$k]->itemsRest = $newItemArr;
                   }

          
               }
            }
            
            
            //var_dump($this->returnObj[$key]->categoriesRest[$key]->itemsRest['attributes']);
            return json_encode($this->returnObj,true);

           // $criteria->addCondition('category_id = ' . 1 );


        });

        $this->onRest('req.get.deals.render', function($result){
            $criteria = new CDbCriteria;
            $criteria->condition = 'active = 1';
            $couponsCollection = MMCoupons::model()->findAll($criteria);
            $this->returnObj['Coupons'] = [];
            foreach($couponsCollection as $couponItem) {
                $item = $couponItem->getAttributes();
                $img_relative_path = str_replace(INST_BASE_DIR, '', MM_UPLOADS_DIR . DIRECTORY_SEPARATOR . $item['image']);

                $item['image'] = Yii::app()->getBaseUrl(true) .'/'. $img_relative_path;
                $item['products'] = [];
                foreach($couponItem->items as $_item) {
                    $item['products'][] = [
                        'id' => $_item->id,
                        'name' => $_item->name,
                    ];
                }
                $this->returnObj['Coupons'][] = $item;
            }
            return json_encode($this->returnObj,true);
        });
    }
}


/**
 * Created by PhpStorm.
 * User: constantine
 * Date: 1/2/2018
 * Time: 9:13 PM
 */