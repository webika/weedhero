<?php
class MMCoupons extends CActiveRecord {

    public $id;
    public $name;
    public $code;
    public $amount;
    public $type = 0;
    public $cfrom;
    public $cto;
    public $active = 0;
    public $image;
    public $products;
    public $description;
    public $minimum_order;

    static public function model($classname = __CLASS__) {
        return parent::model($classname);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Coupon name',
            'code' => 'Promo code',
            'amount' => 'Discount ammount',
            'type' => 'Discount type',
            'cfrom' => 'Active from',
            'cto' => 'Active to',
            'active' => 'Enable coupon',
            'description' => 'Description',
            'products' => 'Associate Products',
            'image' => 'Image',
            'minimum_order' => 'Minimum Order Amount',
        );
    }

    protected function beforeValidate() {
        if(parent::beforeValidate()){
            $pos=strpos($this->cfrom, '-');
            $tempcfrom=$this->cfrom;
            $this->cto=date('Y-m-d H:i:s',strtotime(substr($tempcfrom,$pos+1)));
            $this->cfrom=date('Y-m-d H:i:s',strtotime(substr($tempcfrom,0,$pos-1)));
            //exit(var_dump($this->cto));
            return true;
        }
        return true;
    }

    public function getInRange() {
        if(Helpers::check_in_range($this->cfrom, $this->cto, date('Y-m-d H:i:s',  time())))
            return 'Active';
        else
            return 'Inactive';
    }

    public function getUrl() {
        return Yii::app()->createUrl('admin/editcoupon',array('id'=>$this->id));
    }

    public function getDelUrl() {
        return Yii::app()->createUrl('admin/deletecoupon',array('id'=>$this->id));
    }

    public function rules() {
        return array(
            array('name, code, amount, type, cfrom, cto, active', 'required'),
            array('image, description, products', 'safe'),
            array('code', 'unique'),
            array('amount, minimum_order', 'numerical'),
            array('name', 'length', 'max' => 250),
            array('code', 'length', 'max' => 150),
            array('type', 'in', 'range' => array(0, 1)),
            array('active', 'in', 'range' => array(0, 1)),
            array('name, id, code, amount, type, cfrom, cto, active', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'items' => array(self::MANY_MANY, 'MMItem', MMSettingsForm::getTableNames('coupon_items') . '(coupon_id, item_id)'),
        );
    }

    public function behaviors() {
        //new CAdvancedArBehavior();
        return array(
            'CAdvancedArBehavior' => array('class' => 'application.extensions.CAdvancedArBehavior')
        );
    }

    protected function afterDelete() {

        $dir = MM_UPLOADS_DIR . "/";
        if (is_file($dir . $this->image)) {
            chmod($dir . $this->image, 0777);
            unlink($dir . $this->image);
        }
        return parent::afterDelete();
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->alias = 'coupon';

        $criteria->compare('coupon.id', $this->id, true);
        $criteria->compare('coupon.name', $this->name, true);
        $criteria->compare('coupon.code', $this->code, true);
        $criteria->compare('coupon.cfrom', $this->cfrom, true);
        $criteria->compare('coupon.cto', $this->cto, true);
        $criteria->compare('coupon.active', $this->active, true);
        $criteria->compare('coupon.type', $this->type, true);

        $criteria->compare('coupon.minimum_order', $this->minimum_order, true);
        $criteria->compare('coupon.description', $this->description, true);
        $criteria->compare('coupon.type', $this->type, true);
        return new CActiveDataProvider($this, array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'coupon.id DESC',
             ),
        ));
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('coupons');
    }

}