<?php
Yii::import('bootstrap.widgets.TbDataColumn');
class TbaDataColumn extends TbDataColumn {

    protected function renderHeaderCellContent() {
        if ($this->grid->enableSorting && $this->sortable && $this->name !== null) {
            $sort = $this->grid->dataProvider->getSort();
            $label = isset($this->header) ? $this->header : $sort->resolveLabel($this->name);

            if ($sort->resolveAttribute($this->name) !== false)
                $label .= '<span class="caret"></span>';
            preg_match_all('/href=\"(.*?)\"/', $sort->link($this->name, $label, array('class' => 'sort-link')), $matches);
            $param = substr($matches[0][0], strrpos($matches[0][0], '&'), -1);
//            echo '<a class="sort-link" href="' . admin_url() . 'admin.php?page=' . $_GET['page'] . $param . '">' . $label . '</a>';
        }
        else {
            if ($this->name !== null && $this->header === null) {
                if ($this->grid->dataProvider instanceof CActiveDataProvider)
                    echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
                else
                    echo CHtml::encode($this->name);
            }
            else
                parent::renderHeaderCellContent();
        }
    }

}