<legend>General</legend>
<div class="mm-container">
    <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Pricing',
        'headerIcon' => 'icon-flag',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'tax_rate', array('prepend' => '<i class="icon-cog"></i>'));
        echo $form->textFieldRow($model, 'delivery_charge', array('prepend' => '<i class="icon-flag"></i>'));
        ?>
    </div>
    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'discount', array('prepend' => '<i class="icon-gift"></i>'));
        echo $form->textFieldRow($model, 'min_order', array('prepend' => '<i class="icon-thumbs-down"></i>'));
        ?>
    </div>
    <?php $this->endWidget() ?>
    <table border="0" width="100%">
        <tr>
            <td valign="top" width="250">
    <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Authorize.net payments',
        'headerIcon' => 'icon-signal',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div style="float: left; padding: 10px;">
        <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'api_login_id', array('prepend' => '<i class="icon-asterisk"></i>'));
        echo $form->textFieldRow($model, 'transaction_key', array('prepend' => '<i class="icon-wrench"></i>'));
        ?>
            </div>
        <?php  echo $form->toggleButtonRow($model, 'enable_payments');  ?>
    </div>
    <?php $this->endWidget() ?>
            </td>
            <td valign="top">
    <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Paypal payments',
        'headerIcon' => 'icon-signal',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div style="float: left; padding: 10px;">
        <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'apiUsername', array('prepend' => '<i class="icon-asterisk"></i>'));
        echo $form->textFieldRow($model, 'apiPassword', array('prepend' => '<i class="icon-wrench"></i>'));
        ?>
        </div>
        <div style="float: left; padding: 10px;">
        <?php echo $form->textFieldRow($model, 'apiSignature', array('prepend' => '<i class="icon-wrench"></i>')); ?>

        </div><br style="clear:both">
        <?php  echo $form->toggleButtonRow($model, 'enable_payments_paypal');  ?>
    </div>
    <?php $this->endWidget() ?>
            </td></tr><tr>
            <td valign="top" colspan="2">
     <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'FAXAGE faxing system',
        'headerIcon' => 'icon-print',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div style="float: left; padding: 10px;">
        <?php
        echo $form->textFieldRow($model, 'efax_username', array('prepend' => '<i class="icon-user"></i>'));
        echo $form->textFieldRow($model, 'efax_password', array('prepend' => '<i class="icon-wrench"></i>'));
        ?>
        <?php  echo $form->toggleButtonRow($model, 'enable_efax');  ?>
    </div>
                <div style="float: left; padding: 10px;">
                    <?php echo $form->textFieldRow($model, 'efax_login_id', array('prepend' => '<i class="icon-asterisk"></i>')); ?>
                </div>
    <?php $this->endWidget() ?>
            </td>
        </tr>
    </table>
    <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Display',
        'headerIcon' => 'icon-comment',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div class="inrfrm">
        <span class="cool_heding">Content</span><hr>
        <div style="float: left; padding: 10px;">
            <table border="0">
                <tr>
                    <td>
                        <?php echo $form->dropDownListRow($model, 'item_sort_field', array('id' => 'Added', 'price' => 'Price', 'name' => 'Name')); ?>
                    </td>
                    <td>
                        <?php echo $form->dropDownListRow($model, 'item_sort_order', array('ASC' => 'Increasing', 'DESC' => 'Decreasing'), array('style' => 'width:120px;')); ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $form->dropDownListRow($model, 'cat_sort_field', array('id' => 'Added', 'name' => 'Name')); ?>
                    </td>
                    <td>
                        <?php echo $form->dropDownListRow($model, 'cat_sort_order', array('ASC' => 'Increasing', 'DESC' => 'Decreasing'), array('style' => 'width:120px;')); ?>
                    </td>
                </tr>
            </table>
        </div>
        <div style="float: left; padding: 10px;">
            <table border="0" cellpadding="4">
                <tr>
                    <td>
                        <?php  echo $form->toggleButtonRow($model, 'items_pict');  ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php  echo $form->toggleButtonRow($model, 'cat_pict');  ?>
                    </td>
                </tr>
            </table>
        </div>
        <br style="clear:both">
        <span class="cool_heding">Shopping cart</span><hr>
        <div style="padding: 10px;" align="left">
            <table border="0">
                <tr>
                    <td>
                        <?php
                        echo $form->dropDownListRow(
                                $model,
                                'cart_position',
                                array(
                                    'inside-left' => 'Inside left',
                                    'inside-right' => 'Inside right',
                                    'outside-left' => 'Outside left',
                                    'outside-right' => 'Outside right'
                                ),
                                array('style' => 'width:150px')
                        );
                        ?>
                    </td>
                </tr>
            </table>
        </div>
        <div style="padding: 10px;" align="left">
            <?php echo $form->dropDownListRow($model, 'item_mode', array('0' => 'As cards', '1' => 'As table')); ?>
            <?php
                echo $form->toggleButtonRow($model, 'enable_delivery');
            ?>
            <?php
                echo $form->toggleButtonRow($model, 'enable_social');
            ?>
            <?php
                echo $form->toggleButtonRow($model, 'powered_by');
            ?>
        </div>
    </div>
    <?php $this->endWidget() ?>
</div>