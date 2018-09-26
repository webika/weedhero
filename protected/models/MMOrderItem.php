<?php
class MMOrderItem extends CActiveRecord {

    public $id;
    public $order_id;
    public $itemid;
    public $item_name;
    public $item_price;
    public $instructions;
    public $attribs;

    static public function model($classname = __CLASS__) {
        return parent::model($classname);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'order_id' => 'Parent Order ID',
            'time_delivery' => 'Parent Item ID',
            'item_name' => 'Item name',
            'item_price' => 'Item price',
            'instructions' => 'Additional notes',
            'attribs' => 'Selected attributes',
            'itemid' => 'Original item id'
        );
    }

    public function relations() {
        return array(
           'order' => array(self::BELONGS_TO, 'MMOrder', 'order_id'),
           'item' => array(self::BELONGS_TO, 'MMItem', 'itemid'),
        );
    }

    public function rules() {
        return array(
            array('order_id, item_name, item_price, itemid', 'required'),
            array('instructions, attribs', 'safe'),
            array('id, order_id, item_id', 'safe', 'on' => 'search'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('order_id', $this->order_id, true);
        $criteria->compare('item_name', $this->item_id, true);
        return new CActiveDataProvider($this, array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
        ));
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('order_items');
    }

}