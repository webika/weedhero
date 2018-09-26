<?php
class MMGroup extends CActiveRecord {

    public $id;
    public $name;
    public $type;
    public $item_id;

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                return true;
            } else {
                return true;
            }
        }
        else
            return false;
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
        foreach ($this->attribs as $item) {
            if(is_object($item)) {
                $item->delete();
            }
        }

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
        return MMSettingsForm::getTableNames('groups');
    }

    public function getTypeList($itemtype) {
        if($itemtype == 1)
            $val = array('0' => 'Multi-choice', '1' => 'Single-choise', '2' => 'Pizza');
        else
            $val = array('0' => 'Multi-choice', '1' => 'Single-choise');
        return $val;
    }

    public function rules() {
        return array(
            array('name, item_id', 'required'),
            array('type', 'safe'),
            array('name', 'length', 'max' => 150),
            array('name,  id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'item' => array(self::BELONGS_TO, 'MMItem', 'item_id'),
            'attribs' => array(self::HAS_MANY, 'MMAttribute', 'group_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Group type',
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
