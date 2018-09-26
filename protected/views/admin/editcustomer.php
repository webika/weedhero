<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'user-form',
    'type' => 'vertical',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
    ),
        ))
?>
<legend>
    <div style="float:left">Update User - <?php echo $model->c_mail; ?></div>
    <?php
    Yii::app()->clientScript->registerScript(
            'apply-save actions', "jQuery('#apply_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','true');
                            });
                            jQuery('#save_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','false');
                            });");
    ?>
    <div align="right" style="margin-right: 10px">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'ok white', 'type' => 'primary', 'label' => 'Save Changes', 'htmlOptions' => array('name' => 'wpmm_menus', 'id' => 'save_submit',))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'ok white', 'type' => 'success', 'label' => 'Apply Changes', 'htmlOptions' => array('name' => 'wpmm_menus-apply', 'id' => 'apply_submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('url' => Yii::app()->createUrl('admin/customers'), 'label' => 'Close', 'type' => 'danger', 'icon' => 'remove white')); ?>
    </div>
</legend>
<?php echo Chtml::hiddenField('ajax_validation', $model->id); ?>

<?php echo Chtml::hiddenField('action', 'admin'); ?>
<?php echo Chtml::hiddenField('model_name', 'MMCustomer'); ?>
<?php echo Chtml::hiddenField('apply', 'false', array('id' => 'form_apply_ckeck')); ?>
<div class="mm-container">
    <table border="0" width="100%">
        <tr>
            <td valign="top" width="30%">
                <?php
                $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => 'General',
                    'headerIcon' => 'icon-pencil',
                    'htmlOptions' => array('class' => 'bootstrap-widget-table')
                ))
                ?>
                <div class="inrfrm">
                    <div style="padding: 5px;">
                        <?php echo $form->textFieldRow($model, 'name'); ?>
                        <?php echo $form->textFieldRow($model, 'surname'); ?>
                    </div>
                    <div style="padding: 5px;">
                        <?php echo $form->textFieldRow($model, 'phone'); ?><br><br>
                        <?php $this->widget('bootstrap.widgets.TbButton', array('url' => '#', 'label' => 'Reset Password', 'icon' => 'user','id'=>'wpmm-admin-reset-pass')); ?>
                    </div>
                </div>
                <?php $this->endWidget() ?>
            </td>
            <td valign="top">
                <?php
                $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => 'Addresses',
                    'headerIcon' => 'icon-home',
                    'htmlOptions' => array('class' => 'bootstrap-widget-table')
                ))
                ?>
                <div class="inrfrm">
                    <?php if (!empty($model->addresses)) {
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th>Address</th>
                                    <th width="60">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($model->addresses as $address) {

                                    echo '<tr>';
                                    echo '<td>' . $address->id . '</td>';
                                    echo '<td>';
                                    echo $address->address . ',   ';
                                    echo $address->city . ', ';
                                    if (!empty($address->state))
                                        echo $address->state . '  ';
                                    echo $address->location . ' ';
                                    echo '</td>';
                                    echo '<td>';
                                    $this->widget('bootstrap.widgets.TbButton', array('url' => $address->url, 'label' => 'Edit', 'size' => 'small', 'icon' => 'pencil'));
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }else
                        echo '<b><i>The current user does not have an address data.</i></b>';
                    ?>
                </div>
                <?php $this->endWidget() ?>
            </td>
        </tr>
    </table>
    <?php
    $orders_submited=0;
    $payed_by_credit=0;
    $payed_by_cash=0;
    $del_pikup=0;
    $del_del=0;
    ?>
    <table border="0" width="100%">
        <tr>
            <td valign="top" width="70%">
                <?php
                $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => 'Orders',
                    'headerIcon' => 'icon-barcode',
                    'htmlOptions' => array('class' => 'bootstrap-widget-table')
                ))
                ?>
                <div class="inrfrm">
                    <?php if (!empty($model->orders)) {
                        ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th>Ordered</th>
                                    <th>Payment type</th>
                                    <th>Tips</th>
                                    <th>Delivery type</th>
                                    <th>Status</th>
                                    <th width="80">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $payment_type=array('credit_card'=>'Credit Card','cash'=>'Cash');
                                foreach ($model->orders as $order) {
                                    $orders_submited++;
                                    if($order->payment_type == 'credit_card') { $payed_by_credit++; } else { $payed_by_cash++; }
                                    if($order->delivery_type == 'Delivery') { $del_del++; } else { $del_pikup++; }
                                    echo '<tr>';
                                    echo '<td>' . $order->id . '</td>';
                                    echo '<td>'.date("m/d/Y h:i a", strtotime($order->time_ordered)).'</td>';
                                    echo '<td>'.$payment_type[$order->payment_type].'</td>';
                                    echo '<td>'.$order->tip.'</td>';
                                    echo '<td>'.$order->delivery_type.'</td>';
                                    echo '<td>'.$order->payment_status.'</td>';
                                    echo '<td>';
                                    $this->widget('bootstrap.widgets.TbButton', array('url' => $order->url, 'label' => 'View', 'size' => 'small', 'icon' => 'book'));
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                    }else
                        echo '<b><i>This user is still not made any orders.</i></b>';
                    ?>
                </div>
                <?php $this->endWidget() ?>
            </td>
            <td valign="top">
                <?php
                $this->beginWidget('bootstrap.widgets.TbBox', array(
                    'title' => 'User Stats',
                    'headerIcon' => 'icon-search',
                    'htmlOptions' => array('class' => 'bootstrap-widget-table')
                ))
                ?>
                <div class="inrfrm">
<table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Result</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Orders</td>
                                    <td><?php echo $orders_submited; ?></td>
                                </tr>
                                <tr>
                                    <td>Payments</td>
                                    <td><?php echo 'By credit card :'.$payed_by_credit; echo ' | By cash: '.$payed_by_cash; ?></td>
                                </tr>
                                <tr>
                                    <td>Delivery</td>
                                    <td><?php echo 'Delivery :'.$del_del; echo ' | Pickup: '.$del_pikup; ?></td>
                                </tr>
                                </tbody>
                        </table>
                </div>
                <?php $this->endWidget() ?>
            </td>
        </tr>
    </table>

    <?php $this->endWidget() ?>

</div>
<script>
jQuery('#wpmm-admin-reset-pass').on('click', function(){
    if(confirm('Reset password for <?php echo $model->c_mail ?>! Continue ?')){
        var details = jQuery('#wpmm-item-details');
        jQuery.ajax({
            url: '<?php echo Yii::app()->createUrl('admin/resetpassword') ?>',
            data: {
                'action': 'admin',
                'reset_pass': <?php echo $model->id; ?>,
                '<?php echo Yii::app()->request->csrfTokenName ?>':'<?php echo Yii::app()->request->csrfToken ?>'
            },
            type: "POST",
            dataType: "html",
            error: function(){
                alert('Your request failed!');
            },
            success: function(data) {
                if (data) {
                    alert('Password reset was succsesful !');
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