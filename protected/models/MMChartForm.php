<?php
class MMChartForm extends CFormModel {

    public $location;
    public $daterange;

    public function attributeLabels() {
        return array(
            'location' => Yii::t('_', 'Location'),
            'daterange' => Yii::t('_', 'Date range'),
        );
    }
}
?>
