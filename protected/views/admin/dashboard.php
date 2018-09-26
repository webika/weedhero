<div class="well" style="height: 35px;">
    <div class="pull-left">
        <h3 style="line-height: 15px;"><?php echo Yii::t('_', 'Dashboard') ?></h3>
    </div>
    <div class="pull-right">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t('_', 'Go to orders'),
            'icon' => 'tasks',
            'type' => 'primary',
            'id' => 'crt_reminder',
            'url' => Yii::app()->createUrl('admin/orders'),
        ))
        ?>
    </div>
</div>
<?php
$this->beginWidget('bootstrap.widgets.TbBox', array(
    'title' => Yii::t('_', 'Orders sats'),
    'headerIcon' => 'icon-barcode',
    'htmlOptions' => array('class' => 'bootstrap-widget-table')
))
?>
<div class="inrfrm">
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'chart-form',
    'type' => 'vertical',
    'htmlOptions' => array('onsubmit' => 'return false;'),
        ))
?>
<table border="0" width="100%">
    <tr>
        <td width="200">
<?php echo $form->dropDownListRow($model, 'location', Helpers::getLocationList()); ?>
        </td>
        <td width="250">
<?php echo $form->dateRangeRow($model, 'daterange', array('prepend' => '<i class="icon-calendar"></i>', 'data-lastValue' => $model->daterange /* 'options' => array('startDate'=>date('m/d/Y', strtotime($cfromtemp)),'endDate'=>date('m/d/Y',strtotime($model->cto))) */)); ?>
        </td>
        <td></td>
    </tr>
</table>
<?php $this->endWidget(); ?>
<div id="am_dash_charts" class="container-fluid" style="padding-top: 5px; padding-bottom: 5px;">
<?php $this->renderPartial('_dashboardcharts', array('daterange' => $model->daterange, 'location' => $model->location)); ?>
</div>
    </div>
<?php $this->endWidget(); ?>
<?php
$sql = "SELECT count(id) FROM " . MMSettingsForm::getTableNames('customers');
$usercount = Yii::app()->db->createCommand($sql)->queryScalar();
$sql = "SELECT id FROM " . MMSettingsForm::getTableNames('customers') . " ORDER BY id DESC LIMIT 1";
$lastuser = Yii::app()->db->createCommand($sql)->queryScalar();
$user = MMCustomer::model()->findByPk($lastuser);
$criteria = new CDbCriteria;
$criteria->select = 'max(id), itemid';
$criteria->group = 'itemid';
$criteria->order = 'Count(itemid) desc';
$criteria->limit = '5';
$services1 = MMOrderItem::model()->findAll($criteria);
?>
<table border="0" width="100%" style="margin: 0px; padding: 0px; border-collapse: collapse;">
    <tr>
        <td style="padding: 0px; width: 250px;" valign="top">
            <?php
            $this->beginWidget('bootstrap.widgets.TbBox', array(
                'title' => Yii::t('_', 'Customers'),
                'headerIcon' => 'icon-user',
                'htmlOptions' => array('class' => 'bootstrap-widget-table')
            ))
            ?>
            <div class="inrfrm">
            <table border="0" width="100%">
                <tr>
                    <td><?php echo Yii::t('_', 'Registered Customers') ?> : <b><?php echo $usercount ?></b></td>
                </tr>
            <?php if ($user != null) { ?>
                    <tr>
                        <td><?php echo Yii::t('_', 'Last customer') ?> : <a href="<?php echo Yii::app()->createUrl('admin/viewcustomer', array('id' => $user->id)); ?>"><?php echo $user->c_mail; ?></a></td>
                    </tr>
            <?php } ?>
            </table>
                </div>
            <?php $this->endWidget(); ?>
        </td>
        <td valign="top" style="padding: 0px; padding-left: 10px;">
                <?php
                $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => Yii::t('_', 'Popular Items'),
                    'headerIcon' => 'icon-star',
                    'htmlOptions' => array('class' => 'bootstrap-widget-table')
                ))
                ?>
            <div class="inrfrm">
            <table border="0" width="100%">
                    <?php
                    if (!empty($services1)) {
                        $count = 1;
                        foreach ($services1 as $service) {
                            ?>
                        <tr>
                            <td style="text-align: center; width: 50px"><?php echo $count ?></td>
                            <td><?php echo Yii::t('_', 'Name') . ": " . CHtml::link(CHtml::encode($service->item->name), Yii::app()->createUrl('admin/updateitem', array('id' => $service->item->id))) ?></td>
                            <td style="text-align: right"><?php echo Yii::t('_', 'Price') . ":<b> $" . $service->item->price ?></b></td>
                        <tr>
        <?php
        $count++;
    }
} else {
    echo '<b>' . Yii::t('_', 'No items where ordered yet') . '</b>';
}
?>
            </table>
                </div>
<?php $this->endWidget(); ?>
        </td>
    </tr>
</table>
<script>
    jQuery('#MMChartForm_location').on('change', function() {
        jQuery.ajax({
            url: '<?php echo Yii::app()->createUrl('admin/dashboardcharts') ?>',
            data: {
                'charts': '1',
                'location': $('#MMChartForm_location').val(),
                'daterange': $('#MMChartForm_daterange').val(),
                '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            error: function() {
//                alert('Your request failed!');
            },
            success: function(data) {
                if (data) {
                    $('#am_dash_charts').html(data);
                }
            }
        });
        return false;
    });
    setInterval(function() {
        var txtInput = $('#MMChartForm_daterange');
        var lastValue = txtInput.data('lastValue');
        var currentValue = txtInput.val();
        if (lastValue != currentValue) {
            txtInput.data('lastValue', currentValue);
            jQuery.ajax({
                url: '<?php echo Yii::app()->createUrl('admin/dashboardcharts') ?>',
                data: {
                    'charts': '1',
                    'daterange': $('#MMChartForm_daterange').val(),
                    '<?php echo Yii::app()->request->csrfTokenName ?>': '<?php echo Yii::app()->request->csrfToken ?>'
                },
                type: "POST",
                dataType: "html",
                error: function() {
//                    alert('Your request failed!');
                },
                success: function(data) {
                    if (data) {
                        $('#am_dash_charts').html(data);
                    }
                }
            });
        }
    }, 500);
</script>
