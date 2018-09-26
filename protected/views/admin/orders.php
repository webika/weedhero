<div class="mm-container">
    <?php $this->widget('bootstrap.widgets.TbButton', array('url' => $this->createUrl('admin/coupons'), 'label' => 'Coupons', 'icon' => 'bookmark', 'htmlOptions' => array('style' => 'margin: 5px;'))); ?>
    <?php
    $this->widget('ext.bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered condensed',
        'id' => 'Orders-grid',
        'enablePagination' => true,
        'dataProvider' => $model->search(),
        'afterAjaxUpdate' => 'js:function(id, data) { jQuery(document).trigger("change"); }',
        'filter' => $model,
        'template' => "{items}",
        'columns' => array(
            array('name' => 'id', 'header' => '#', 'htmlOptions' => array('style' => 'width: 40px; vertical-align:middle; text-align: center;')),
            array(
                'name' => 'id',
                'type' => 'raw',
                'header' => 'Customer',
                'value' => '$data->UserUrl',
                'filter' => false,
                'sortable' => false,
                'htmlOptions' => array('align' => 'center', 'style' => ' vertical-align:middle; text-align:center')
            ),
            array(
                'name' => 'time_ordered',
                'value' => 'date("m/d/Y h:i a", strtotime($data->time_ordered))',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:140px; text-align:center')
            ),
            array(
                'name' => 'time_delivery',
                'value' => 'date("m/d/Y h:i a", strtotime($data->time_delivery))',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:140px; text-align:center')
            ),
            array(
                'name' => 'payment_type',
                'value' => '($data->payment_type == "credit_card") ? "Credit Card" : "Cash"',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:80px;')
            ),
            array(
                'name' => 'payment',
                'header'=>'Payment',
                'value'=>'($data->payment == 0)? "No" : "Yes"',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:20px; text-align:center')
            ),
            array(
                'name' => 'tip',
                'htmlOptions' => array('style' => 'vertical-align:middle; width:60px; text-align:center')
            ),
            array(
                'name' => 'delivery_type',
                'filter'=>Helpers::enumItemArray($model, 'delivery_type'),
                'htmlOptions' => array('style' => 'vertical-align:middle; width:120px;')
            ),
            array(
                    'class'=>'application.components.boot.TbaRelationalColumn',
                    'name' => 'addresses',
                    'url' => Yii::app()->createUrl('admin/relationalorder'),
                    'value'=> '"Address"',
                    'filter' => false,
                    'htmlOptions' => array('style' => 'vertical-align:middle; width:120px; text-align:center')
                ),
            array(
                'name' => 'location_id',
                'type' => 'raw',
                'header' => 'Location',
                'value' => '$data->LocationUrl',
                'filter' => false,
                'sortable' => false,
                'htmlOptions' => array('align' => 'center', 'style' => ' vertical-align:middle; text-align:center')
            ),
             array(
                'class' => 'TbaEditableColumn',
                'url' => Yii::app()->createUrl('admin/savestatus'),
                'name' => 'payment_status',
                'header' => 'Order status',
                'sortable' => true,
                'type' => 'select',
                'params'=>array(Yii::app()->request->csrfTokenName => Yii::app()->getRequest()->getCsrfToken()),
                'source'    => Helpers::enumItem($model, 'payment_status'),
                'filter'=> Helpers::enumItemArray($model, 'payment_status'),
                'htmlOptions' => array('style' => 'vertical-align:middle; width:150px; text-align:center')
            ),
            array(
                'class' => 'application.components.boot.TbaButtonColumn',
                'template' => '{update}',
                'htmlOptions' => array('style' => 'width: 25px; vertical-align:middle;', 'align' => 'center'),
                'updateButtonUrl' => '$data->url',
            ),
        ),
    )) ?>
</div>