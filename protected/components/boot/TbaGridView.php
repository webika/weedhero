<?php
Yii::import('bootstrap.widgets.TbGridView');
class TbaGridView extends TbGridView {

    protected function initColumns() {
        foreach ($this->columns as $i => $column) {
            if (is_array($column) && !isset($column['class']))
                $this->columns[$i]['class'] = 'application.components.boot.TbaDataColumn';
        }

        parent::initColumns();
    }

}