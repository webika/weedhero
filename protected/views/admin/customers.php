<div class="mm-container">
    <?php
    $script = <<<SCRIPT

SCRIPT;
    $this->widget('ext.bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered condensed',
        'id' => 'customers-grid',
        'enablePagination' => true,
        'dataProvider' => $model->search(),
        'afterAjaxUpdate' => 'js:function(id, data) {' . $script . '}',
        'filter' => $model,
        'template' => "{items}{pager}",
        'columns' => array(
            array('name' => 'id', 'header' => '#', 'htmlOptions' => array('style' => 'width: 40px; vertical-align:middle; text-align: center;')),
            array(
                'name' => 'c_mail',
                'type' => 'raw',
                'value' => 'CHtml::link(CHtml::encode($data->c_mail), $data->url)',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'name' => 'name',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
            ),
            array(
                'name' => 'surname',
                'htmlOptions' => array('style' => 'vertical-align:middle;')
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
