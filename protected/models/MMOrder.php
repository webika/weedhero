<?php
class MMOrder extends CActiveRecord {

    public $id;
    public $time_ordered;
    public $time_delivery;
    public $payment_type;
    public $payment_status;
    public $payment;
    public $delivery_type;
    public $tip;
    public $tip_type;
    public $promo_code;
    public $notes;
    public $trans_id;
    public $params;
    public $address_id;
    public $customer_id;
    public $total_sum;
    public $location_id;

    static public function model($classname = __CLASS__) {
        return parent::model($classname);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'time_ordered' => 'Ordered',
            'time_delivery' => 'Schedule delivery',
            'payment_type' => 'Payment type',
            'payment_status' => 'Order status',
            'tip' => 'Tips',
            'promo_code' => 'Promo Code',
            'notes' => 'Notes',
            'params' => 'Payment contants',
            'address_id' => 'Addresses',
            'delivery_type'=> 'Delivery Type',
            'payment'=>'Is payment Recived',
            'tip_type'=>'Type of the tip',
            'trans_id'=>'The payment transaction ID',
            'total_sum' => 'Total Sum of the order',
            'location_id' => 'Ordered from a location'
        );
    }

    public function getUrl() {
        return Yii::app()->createUrl('admin/vieworder',array('id'=>$this->id));
    }

    public function getUserUrl(){
        if($this->customer){
            $link=CHtml::link(CHtml::encode($this->customer->c_mail), Yii::app()->createUrl('admin/viewcustomer',array('id'=>$this->customer->id)));
            return $link;
        } else {
            return 'Customer not found';
        }
    }

    public function getLocationUrl(){
        if($this->location){
            $link=CHtml::link(CHtml::encode($this->location->name), Yii::app()->createUrl('admin/editlocation',array('id'=>$this->location_id)));
            return $link;
        } else {
            return 'Location not found';
        }
    }

    public function relations() {
        return array(
           'items' => array(self::HAS_MANY, 'MMOrderItem', 'order_id'),
           'customer' => array(self::BELONGS_TO, 'MMCustomer', 'customer_id'),
           'addresses' => array(self::BELONGS_TO, 'MMAddress', 'address_id'),
           'location' => array(self::BELONGS_TO, 'MMLocation', 'location_id'),
        );
    }

    public function rules() {
        return array(
            array('time_ordered, time_delivery, payment_type, address_id, delivery_type', 'required'),
            array('payment_status, tip_type, tip, promo_code, notes, customer_id, payment, trans_id, params, total_sum, location_id', 'safe'),
            array('payment', 'in', 'range' => array(0, 1)),
            array('time_ordered, id, time_delivery, payment_type, tip, items, addresses, payment_status, delivery_type', 'safe', 'on' => 'search'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('time_ordered', $this->time_ordered, true);
        $criteria->compare('time_delivery', $this->time_delivery, true);
        $criteria->compare('payment_type', $this->payment_type, true);
        $criteria->compare('tip', $this->tip, true);
        $criteria->compare('addresses', $this->addresses, true);
        $criteria->compare('payment_status', $this->payment_status, true);
        $criteria->compare('delivery_type', $this->delivery_type, true);
        return new CActiveDataProvider($this, array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
            'sort' => array(
            'defaultOrder' => 'time_ordered DESC',
         ),
        ));
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('orders');
    }

}