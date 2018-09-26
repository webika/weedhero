<?php
//namespace app\models;

class MMItem extends CActiveRecord {

    public $id;
    public $name;
    public $description;
    public $image;
    public $size;
    public $price;
    public $type;
    public $published = 1;
    public $searchCategory;
    public $attributes;

    protected function beforeSave() {
        return parent::beforeSave();
    }

    protected function afterSave() {
        parent::afterSave();
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $this->name=trim($this->name);
            $this->description=trim($this->description);
            return true;
        }
        return false;
    }

    protected function afterDelete() {
        $sql = "delete ignore from " . MMSettingsForm::getTableNames('category_items') . " where item_id = '{$this->id}'";
        Yii::app()->db->createCommand($sql)->execute();
        foreach ($this->params as $item) {
            $item->delete();
        }
        $dir = MM_UPLOADS_DIR . "/";
        if (is_file($dir . $this->image)) {

            chmod($dir . $this->image, 0777);
            unlink($dir . $this->image);
            if (is_file($dir . 'thumb_' . $this->image)) {
                chmod($dir . 'thumb_' . $this->image, 0777);
                unlink($dir . 'thumb_' . $this->image);
            }
        }
        if (parent::afterDelete())
            return true;
    }

    protected function afterFind() {
        parent::afterFind();
    }

    public function getRelLink() {
        if (!empty($this->categories)) {
            $data='<ul>';
            foreach ($this->categories as $cat) {
                $data.='<li><b>' . $cat->name . '</b></li>';
            }
            $data.='</ul>';
        }
        else
            $data = '<b>No related records</b>';
        $link = CHtml::link('Related Categories', '', array('data-title' => 'Related Categories', 'data-placement' => 'left', 'data-trigger' => 'hover', 'data-content' => $data, 'data-animation' => false, 'rel' => 'popover', 'class' => 'btn'));
        return $link;
    }

    public function getItemType(){
        return Helpers::getItemType($this->type);
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('items');
    }

    public function rules() {
        return array(
            array('name, price, type', 'required'),
            array('name', 'unique'),
            array('price', 'numerical'),
            array('image, categories, description, size, published', 'safe'),
            array('name', 'length', 'max' => 150),
            array('image', 'length', 'max' => 250),
            array('published', 'in', 'range' => array(0, 1)),
            array('name,  id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'params' => array(self::HAS_MANY, 'MMGroup', 'item_id'),
            'categories' => array(self::MANY_MANY, 'MMCategory', MMSettingsForm::getTableNames('category_items') . '(item_id, category_id)'),
            'coupons' => array(self::MANY_MANY, 'MMCoupons', MMSettingsForm::getTableNames('coupon_items') . '(item_id, coupon_id)'),
        );
    }

    public function behaviors() {

        return array(
            'CAdvancedArBehavior' => array('class' => 'application.extensions.CAdvancedArBehavior')
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description text',
            'price' => 'Price',
            'size' => 'Size',
            'image' => 'Item title image (optional)',
            'type' =>'Item type'
        );
    }

    public function getUrl() {
        return Yii::app()->createUrl('admin/updateitem',array('id'=>$this->id));
    }

    public function getDelUrl() {
        return Yii::app()->createUrl('admin/deleteitem',array('id'=>$this->id));
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->alias = 'item';
        //$criteria->group = 'item.id';
        $criteria->together = true;
        $criteria->with = array('categories' => array(
                'alias' => 'category',
                'criteria' => array(
                    'order' => 'category.name ASC'
                )
        ));
        $criteria->compare('item.id', $this->id, true);
        $criteria->compare('item.name', $this->name, true);
        $criteria->compare('item.price', $this->price, true);
        $criteria->compare('item.type', $this->type, true);
        $criteria->compare('category.name', $this->searchCategory->name);

        return new CActiveDataProvider(MMItem::model(), array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
        ));
    }

}
