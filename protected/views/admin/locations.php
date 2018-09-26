<div class="mm-container">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t('_', 'Add a location'),
        'icon' => 'bookmark',
        'url' => Yii::app()->createUrl('admin/createlocation'),
    ))
    ?>
<?php
    $this->widget('ext.bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered condensed',
        'id' => 'menu-grid',
        'enablePagination' => true,
        'ajaxUrl'=>$this->createUrl('admin/locations'),
        'dataProvider' => $model->search(),
        'filter' => $model,
        'template' => "{items}{pager}",
        'columns' => array(
            array('name' => 'id', 'header' => '#', 'htmlOptions' => array('style' => 'width: 40px; vertical-align:middle; text-align:center')),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link(CHtml::encode($data->name), $data->url)',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'name' => 'timezone',
                'value' => 'Helpers::getTimezone($data->timezone)',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'header' =>Yii::t('_', 'Street Address'),
                'name' => 'address',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'name' => 'phone',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'name' => 'fax',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'name' => 'l_email',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'class' => 'application.components.boot.TbaButtonColumn',
                'template' => '{update}{delete}',
                'htmlOptions' => array('style' => 'width: 55px; vertical-align:middle; text-align: center'),
                'updateButtonUrl' => '$data->url',
                'deleteButtonUrl' => '$data->delurl',
                'deleteButtonLabel' => Yii::t('_', 'Delete'),
                'updateButtonLabel' => Yii::t('_', 'Edit'),
                'deleteConfirmation' => Yii::t('_', 'Are you sure you want to delete this location?'),
            ),
        ),
    ));
    ?>
</div>