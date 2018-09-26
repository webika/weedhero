<?php
class MMAttribute extends CActiveRecord {

    public $id;
    public $name;
    public $price;
    public $checked_id;
    public $group_id;

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                return true;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $this->name=trim($this->name);
            return true;
        }
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
        return MMSettingsForm::getTableNames('attributes');
    }

    public function rules() {
        return array(
            array('name, group_id', 'required'),
            array('price, checked_id', 'safe'),
            array('name', 'length', 'max' => 150),
            array('name,  id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'group' => array(self::BELONGS_TO, 'MMGroup', 'group_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'group_id' => 'Parent ID',
        );
    }

    public function getDelUrl() {
        return 'admin.php?page=wpmm_items&action=deleteitem&id=' . $this->id;
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('price', $this->price, true);

        return new CActiveDataProvider($this, array(
            'pagination' => array('pageSize' => '50'),
            'criteria' => $criteria,
                ));
    }

}
