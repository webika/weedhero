<?php
class MMLocation extends CActiveRecord {

    public $id;
    public $name;
    public $timezone;
    public $address;
    public $phone;
    public $fax;
    public $l_email;
    public $gmap;
    public $l_calendar;
    public $zip_list;

    static public function model($classname = __CLASS__) {
        return parent::model($classname);
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('locations');
    }

    public function __call($name, $parameters) {
        parent::__call($name, $parameters);
    }

    public function attributeLabels() {
        return array(
            'id' => Yii::t('_', 'ID'),
            'name' => Yii::t('_', 'Location name'),
            'timezone' => Yii::t('_', 'Timezone'),
            'address' => Yii::t('_', 'Street address (255 chars)'),
            'phone' => Yii::t('_', 'Phone number'),
            'fax' => Yii::t('_', 'Fax number'),
            'l_email' => Yii::t('_', 'Location e-mail'),
            'gmap' => Yii::t('_', 'Map Parameters'),
            'l_calendar' => Yii::t('_', 'Calendar'),
            'zip_list' => Yii::t('_', 'Allowed Zipcodes')
        );
    }

    protected function beforeValidate() {
        return true;
    }

    protected function afterDelete() {

        $sql = "delete ignore from ".MMSettingsForm::getTableNames('location_menus')." where location_id = '{$this->id}'";
        Yii::app()->db->createCommand($sql)->execute();

        if (parent::afterDelete())
            return true;
    }

    public function getUrl() {
        return Yii::app()->createUrl('admin/editlocation',array('id'=>$this->id));
    }

    public function getDelUrl() {
        return Yii::app()->createUrl('admin/deletelocation',array('id'=>$this->id));
    }

    public function relations() {
        return array(
            'menus' => array(self::MANY_MANY, 'MMMenu', MMSettingsForm::getTableNames('location_menus').'(location_id, menu_id)'),
            'orders' => array(self::HAS_MANY, 'MMOrder', 'location_id'),
        );
    }

    public function behaviors() {
        return array(
            'CAdvancedArBehavior' => array('class' => 'application.extensions.CAdvancedArBehavior')
        );
    }

    public function rules() {
        return array(
            array('name, timezone, address, phone, fax, l_email', 'required'),
            array('gmap, l_calendar, zip_list', 'safe'),
            array('phone, fax','numerical'),
            array('name', 'length', 'max' => 250),
            array('timezone', 'length', 'max' => 250),
            array('address', 'length', 'max' => 250),
            array('l_email', 'email', 'message' => Yii::t('_', 'Invalid Email address')),
            array('id, name, timezone, address, phone, fax, l_email', 'safe', 'on' => 'search'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('timezone', $this->timezone, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('l_email', $this->l_email, true);
        return new CActiveDataProvider($this, array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
            'sort' => array(
            'defaultOrder' => 'id DESC',
         ),
        ));
    }

}