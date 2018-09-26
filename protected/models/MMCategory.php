<?php
class MMCategory extends CActiveRecord {

    public $id;
    public $name;
    public $description;
    public $image;
    public $published = 1;
    public $searchMenu;
    public $itemsRest;

    protected function beforeSave() {
        if (parent::beforeSave()) {
            return true;
        }
        else
            return false;
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $this->name=trim($this->name);
            $this->description=trim($this->description);
            return true;
        }
        return false;
    }

    protected function afterSave() {
        parent::afterSave();
    }

    protected function afterDelete() {

        $sql = "delete ignore from " . MMSettingsForm::getTableNames('menu_categories') . " where category_id = '{$this->id}'";
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
        return MMSettingsForm::getTableNames('categories');
    }

    public function rules() {
        return array(
            array('name', 'required'),
            array('name', 'unique'),
            array('image, description, menus, published', 'safe'),
            array('name', 'length', 'max' => 150),
            array('image', 'length', 'max' => 250),
            array('published', 'in', 'range' => array(0, 1)),
            array('name,  id, sMenu, description', 'safe', 'on' => 'search'),
        );
    }

    public function relations() {
        return array(
            'items' => array(self::MANY_MANY, 'MMItem', MMSettingsForm::getTableNames('category_items') . '(category_id, item_id)'),
            'menus' => array(self::MANY_MANY, 'MMMenu', MMSettingsForm::getTableNames('menu_categories') . '(category_id, menu_id)'),
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
            'image' => 'Category title image (optional)',
        );
    }

    public function getRelLink() {
        if (!empty($this->menus)) {
            $data='<ul>';
            foreach ($this->menus as $cat) {
                $data.='<li><b>' . $cat->name . '</b></li>';
            }
            $data.='</ul>';
        }
        else
            $data = '<b>No related records</b>';
        $link = CHtml::link('Related Menus', '', array('data-title' => 'Related Menus', 'data-placement' => 'left', 'data-trigger' => 'hover', 'data-content' => $data, 'data-animation' => false, 'rel' => 'popover', 'class' => 'btn'));
        return $link;
    }

    public function getUrl() {
        return Yii::app()->createUrl('admin/updatecategory',array('id'=>$this->id));
    }

    public function getDelUrl() {
        return Yii::app()->createUrl('admin/deletecategory',array('id'=>$this->id));
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->alias = 'category';
        $criteria->with = array(
            'menus' => array(
                'alias' => 'menu',
                'criteria' => array(
                    'order' => 'menu.name ASC'
                )
            ));
        //$criteria->group = 'category.id';
        $criteria->together = true;
        $criteria->compare('category.id', $this->id, true);
        $criteria->compare('category.name', $this->name, true);
        $criteria->compare('menu.name', $this->searchMenu->name);

        return new CActiveDataProvider(MMCategory::model(), array(
            'pagination' => array(
                'pageSize' => '50'
            ),
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder'=>array(
                    'id' => CSort::SORT_DESC,
                ),
                'attributes' => array(
                    'id' => array(
                        'asc' => 'category.id ASC',
                        'desc' => 'category.id DESC',
                    ),
                    'name' => array(
                        'asc' => 'category.name ASC',
                        'desc' => 'category.name DESC',
                    ),
                    '*',
                ),
            ),
        ));
    }

}