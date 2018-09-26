<?php

class MMMenu extends CActiveRecord {

    public $id;
    public $name;
    public $description;
    public $image;
    public $time_from;
    public $time_to;
    public $sort_order;
    public $published = 1;
    public $searchCategory;
    public $categoriesRest;

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $maxOrderNumber = Yii::app()->db->createCommand()->select('max(id) as max')->from(MMSettingsForm::getTableNames('menus'))->queryScalar();
                $this->sort_order = $maxOrderNumber + 1;
                return true;
            } else {
                return true;
            }
        }
        else
            return false;
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
        $sql = "delete ignore from " . MMSettingsForm::getTableNames('menu_categories') . " where menu_id = '{$this->id}'";
        Yii::app()->db->createCommand($sql)->execute();
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

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return MMSettingsForm::getTableNames('menus');
    }

    public function rules() {
        return array(
            array('name', 'required'),
            array('name', 'unique'),
            array('image, time_from, time_to, description, published, sort_order', 'safe'),
            array('name', 'length', 'max' => 150),
            array('image', 'length', 'max' => 250),
            array('time_from, time_to, sort_order', 'numerical'),
            array('published', 'in', 'range' => array(0, 1)),
            array('name, id', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'categories' => array(self::MANY_MANY, 'MMCategory', MMSettingsForm::getTableNames('menu_categories') . '(menu_id, category_id)'),
            'locations' => array(self::MANY_MANY, 'MMLocation', MMSettingsForm::getTableNames('location_menus').'(menu_id, location_id)'),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description text',
            'image' => 'Menu title image (optional)',
            'time_from' => 'Available from',
            'time_to' => 'Available to',
        );
    }

    public function behaviors() {

        return array(
            'CAdvancedArBehavior' => array('class' => 'application.extensions.CAdvancedArBehavior')
        );
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
            $data = '<b>No related categories.</b>';
        $link = CHtml::link('Related data', '', array('data-title' => 'Related data', 'data-placement' => 'left', 'data-trigger' => 'hover', 'data-content' => $data, 'data-animation' => false, 'rel' => 'popover', 'class' => 'btn'));
        return $link;
    }

    public function getRelLinkLoc() {
        if (!empty($this->locations)) {
            $data='<ul>';
            foreach ($this->locations as $cat) {
                $data.='<li><b>' . $cat->name . '</b></li>';
            }
            $data.='</ul>';
        }
        else
            $data = '<b>No related locations.</b>';
        $link = CHtml::link('Related locations', '', array('data-title' => 'Related data', 'data-placement' => 'left', 'data-trigger' => 'hover', 'data-content' => $data, 'data-animation' => false, 'rel' => 'popover', 'class' => 'btn'));
        return $link;
    }

    public function getUrl() {
        return Yii::app()->createUrl('admin/updatemenu',array('id'=>$this->id));
    }

    public function getDelUrl() {
        return Yii::app()->createUrl('admin/deletemenu',array('id'=>$this->id));
    }

    public function getFrom() {
        $tmp = Helpers::GetHours();
        return $tmp[$this->time_from];
    }

    public function getTo() {
        $tmp = Helpers::GetHours();
        return $tmp[$this->time_to];
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->alias = 'menu';
        //$criteria->group = 'menu.id';
        $criteria->together = true;
        $criteria->with = array('locations' => array(
                'alias' => 'location',
                'criteria' => array(
                    'order' => 'location.name ASC'
                )
                ));
        $criteria->compare('menu.id', $this->id, true);
        $criteria->compare('menu.name', $this->name, true);
        $criteria->compare('menu.time_from', $this->time_from, true);
        $criteria->compare('menu.time_to', $this->time_to, true);
        $criteria->compare('location.name', $this->searchCategory->name);
        $criteria->order = 'menu.sort_order DESC';
        return new CActiveDataProvider($this, array(
                    'pagination' => array('pageSize' => '50'),
                    'criteria' => $criteria,
                ));
    }

}
