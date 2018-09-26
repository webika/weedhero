<?php
class MMCustomer extends CActiveRecord {

    public $id;
    public $c_mail;
    public $name;
    public $surname;
    public $password;
    public $salt;
    public $phone;

    static public function model($classname = __CLASS__) {
        return parent::model($classname);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'c_mail' => 'Contact e-mail',
            'name' => 'Customer first name',
            'surname' => 'Customer last name',
            'password' => 'Customer password',
            'salt' => 'More salt sir ?',
            'phone' => 'Contact phone',
        );
    }

    public function beforeSave()
    {
        if(parent::beforeSave()){
            if ($this->isNewRecord) {
                $this->name=strip_tags(trim($this->name));
                $this->surname=strip_tags(trim($this->surname));
                $this->salt = md5(uniqid());
                $this->password = $this->hashPassword($this->password, $this->salt);
                return true;
            }
            $this->password = $this->hashPassword($this->password, $this->salt);
            return true;
		}
        return false;
    }

    public function validatePassword($password)
    {
        return $this->hashPassword($password,$this->salt)===$this->password;
    }

    public function hashPassword($password,$salt)
    {
        return md5($salt.$password);
    }

    public function getUrl() {
        return Yii::app()->createUrl('admin/viewcustomer',array('id'=>$this->id));
    }

    public function relations() {
        return array(
           'orders' => array(self::HAS_MANY, 'MMOrder', 'customer_id'),
           'addresses' => array(self::HAS_MANY, 'MMAddress', 'customer_id'),
        );
    }

    public function rules() {
        return array(
            array('c_mail, password, phone', 'required'),
            array('c_mail', 'unique','message'=>'Email {attribute} is alredy in use.'),
            array('c_mail', 'email'),
            array('name, surname, phone, salt', 'safe'),
            array('phone','numerical', 'integerOnly'=>true),
            array('phone','length', 'min' => 10, 'max'=>10),
            array('c_mail, name, surname, id, phone', 'safe', 'on' => 'search'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('c_mail', $this->c_mail, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('surname', $this->surname, true);
        $criteria->compare('phone', $this->phone, true);
        return new CActiveDataProvider($this, array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
        ));
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('customers');
    }

}
