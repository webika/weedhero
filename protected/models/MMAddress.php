<?php
class MMAddress extends CActiveRecord {

    public $id;
    public $city;
    public $state;
    public $address;
    public $location;
    public $customer_id;

    protected function beforeSave() {
        if (parent::beforeSave()) {
            return true;
        }
        else
            return false;
    }

    protected function afterSave() {
        parent::afterSave();
    }

    protected function afterDelete() {

        if (parent::afterDelete())
            return true;
    }

    protected function afterFind() {
        parent::afterFind();
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('addresses');
    }

    public function rules() {
        return array(
            array('address, state, location, city', 'required'),
            array('customer_id', 'safe'),
            array('address', 'length', 'max' => 250),
            array('city', 'length', 'max' => 250),
            array('state', 'length', 'max' => 2),
            array('location', 'zipvalidate'),
            array('location', 'zipvalidatesettings'),
            array('address, location, state, city', 'safe', 'on' => 'search'),
        );
    }

    public function zipvalidate($attribute, $params) {
        if (preg_match("/(^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$)|(^\d{5}(-\d{4})?$)/", $this->$attribute)) {
            return true;
        }

        $this->addError($attribute, 'Incorrect zip code format.');
    }

    public function zipvalidatesettings($attribute, $params){
        if (Helpers::ValidateZipcodeByLocation($this->$attribute)) {
            return true;
        }

        $this->addError($attribute, 'Restaurant does not offer delivery to the chosen zip.');
    }

    public function relations() {
        return array(
           'customer' => array(self::BELONGS_TO, 'MMCustomer', 'customer_id'),
           'order' => array(self::HAS_MANY, 'MMOrder', 'address_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'city' => 'City',
            'state' => 'State (eg. DC)',
            'address' => 'Address',
            'location' => 'Zip',
        );
    }
    public function getUrl() {
        return Yii::app()->createUrl('admin/updateaddress',array('id'=>$this->id,'cid'=>$this->customer_id));
    }

    public function getDelUrl() {
        return Yii::app()->createUrl('admin/deleteaddress',array('id'=>$this->id,'cid'=>$this->customer_id));
    }

    public function search() {

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('location', $this->location, true);
        return new CActiveDataProvider($this, array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
        ));
    }

}