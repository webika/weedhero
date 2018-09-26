<div class="mm-container">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Create new Coupon',
        'size' => 'large',
        'icon' => 'bookmark',
        'url' => Yii::app()->createUrl('admin/createcoupon'),
    )) ?>
<?php
    $this->widget('ext.bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered condensed',
        'id' => 'coupons-grid',
        'enablePagination' => true,
        'dataProvider' => $model->search(),
        'afterAjaxUpdate' => 'js:function(id, data) { jQuery(document).trigger("change"); }',
        'filter' => $model,
        'template' => "{items}",
        'columns' => array(
            array('name' => 'id', 'header' => '#', 'htmlOptions' => array('style' => 'width: 40px; vertical-align:middle; text-align: center;')),
            array(
                'name' => 'name',
                'type' => 'raw',
                'header' => 'Name',
                'value' => 'CHtml::link(CHtml::encode($data->name), $data->url)',
                'htmlOptions' => array('align' => 'center', 'style' => ' vertical-align:middle; text-align:center')
            ),
            array(
                'name' => 'code',
                //'value' => 'date("m/d/Y h:i a", strtotime($data->time_ordered))',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:140px; text-align:center')
            ),
            array(
                'name' => 'amount',
                //'value' => 'date("m/d/Y h:i a", strtotime($data->time_delivery))',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:140px; text-align:center')
            ),
            array(
                'name' => 'type',
                'value' => '($data->type == "0") ? "Specific amount, $" : "Percentage, %"',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:120px;')
            ),
            array(
                'name' => 'cfrom',
                'value' => 'date("m/d/Y", strtotime($data->cfrom))',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:140px; text-align:center')
            ),
            array(
                'name' => 'cto',
                'value' => 'date("m/d/Y", strtotime($data->cto))',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:140px; text-align:center')
            ),
            array(
                'name' => 'cto',
                'type' => 'raw',
                'header' => 'Active',
                'value' => '$data->inrange',
                'filter' => '',
                'sortable' => false,
                'htmlOptions' => array('style' => 'width: 40px; vertical-align:middle; text-align: center;'),
            ),
            array(
                'name' => 'active',
                'header' => 'Enabled',
                'htmlOptions' => array(
                'style' => 'width: 65px; vertical-align:middle; text-align: center;'),
                'class' => 'FlagColumn',
                'callbackUrl' => array('flagcoupon'),
            ),
            array(
                'class' => 'application.components.boot.TbaButtonColumn',
                'template' => '{update}{delete}',
                'htmlOptions' => array(
                'style' => 'width: 55px; vertical-align:middle; text-align: center;'),
                'updateButtonUrl' => '$data->url',
                'deleteButtonUrl' => '$data->delurl',
                'deleteButtonLabel' => 'Delete',
                'updateButtonLabel' => 'Edit',
                'deleteConfirmation' => 'Are you sure you want to delete this coupon?',
            ),
        ),
    )) ?>
</div>
