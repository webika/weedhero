<div class="mm-container">
    <?php
    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile(Yii::app()->baseUrl.'/js' . DIRECTORY_SEPARATOR . 'jquery.printElementt.js');
    ?>
    <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Order',
        'headerIcon' => 'icon-barcode',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ));
    ?>
    <?php $this->widget('bootstrap.widgets.TbButton', array('url' => $this->createUrl('admin/orders'), 'label' => 'Close', 'type' => 'danger', 'icon' => 'remove white', 'htmlOptions' => array('style' => 'margin: 5px;'))); ?>
    <?php
    $this->widget('bootstrap.widgets.TbButton', array('url' => '', 'label' => 'Print', 'type' => 'primary', 'icon' => 'print white',
        'htmlOptions' => array('style' => 'margin: 5px; margin-left:0px;',
            'onClick' => 'jQuery(".inrfrm").printElement();',
            )));
    ?>
    <?php if($model->customer) $this->widget('bootstrap.widgets.TbButton', array('url' => '#', 'label' => 'Re-send bill', 'icon' => 'envelope','id'=>'wpmm-admin-resend-bill')); ?>
    <div class="inrfrm">
            <?php if(!empty($model->trans_id)) { ?>
        <div class="well">
            <?php  echo '<span style="font-size: 22px;">Order payment transaction id: <b>'.$model->trans_id.'</b></span>'; ?>
        </div>
                <?php } ?>
         <?php   $this->renderPartial('_orderblank', array('model'=>$model,'full'=>true)); ?>
    </div>
<?php $this->endWidget(); ?>
</div>
<?php if($model->customer){ ?>
<script>
jQuery('#wpmm-admin-resend-bill').on('click', function(){
    if(confirm('Re-send bill for <?php echo $model->customer->c_mail ?>! Continue ?')){
        var details = jQuery('#wpmm-item-details');
        jQuery.ajax({
            url: '<?php echo $this->createUrl('admin/resendbill') ?>',
            data: {
                'action': 'admin',
                'resend_bill': <?php echo $model->id; ?>,
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            error: function(){
                alert('Your request failed!');
            },
            success: function(data) {
                if (data) {
                    alert('Bill sent succsesfuly!');
                } else {
                  alert('Your request failed!');
                }
            }
        });
            return false;
        } else {
           return false;
        }
    });
</script>
<?php } ?>