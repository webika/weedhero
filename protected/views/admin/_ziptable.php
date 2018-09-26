<?php
if (!empty($ziplist)) {
?>
    <table class="tbset table-bordered table-striped">
        <tr>
            <th>Zip-code</th>
            <th width="50">Remove</th>
        </tr>
        <?php
        if (is_array($ziplist)) {
           foreach ($ziplist as $zipcode) { ?>
                <tr>
                    <td>
                        <?php echo $zipcode ?>
                    </td>
                    <td style="text-align: center">
                        <?php
                        $this->widget('bootstrap.widgets.TbButton', array(
                            'label'=>'',
                            'type'=>'danger',
                            'size'=>'mini',
                            'icon'=>'remove white',
                            'size'=>'mini',
                            'htmlOptions'=>array(
                                'title'=>'Remove',
                                'onClick'=>CHtml::ajax(
                                    array(
                                        'success'=>"function(data){
                                            jQuery('#zipcodetablecontainer').empty();
                                            jQuery('#zipcodetablecontainer').append(data);
                                            jQuery('#addzipcode').val('');}",
                                        'url'=>Yii::app()->createUrl('admin/removezipcode'),
                                        'type'=>'POST',
                                        'data' => array(
                                                'action' => 'admin',
                                                'removezipcode' => $zipcode,
                                                Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
                                            ),
                                         ),
                                    array('live'=>true)
                                ),
                              ),
                            ));
                        ?>
                    </td>
                </tr>

        <?php }
        } ?>

    <?php if (isset($error['zipcode']) && $error['zipcode'][0]) {
        echo '<tr class="error"><td>'.$error['zipcode'][0].'</td><td></td></tr>';
    } ?>
<?php } else { ?>
    <table class="tbset table-bordered table-striped">
        <tr>
            <th>ZIP code</th>
        </tr>
        <tr>
            <td>There are no added ZIP codes at the moment.</td>
        </tr>
        <?php if (isset($error['zipcode']) && $error['zipcode'][0]) {
            echo '<tr class="error"><td>'.$error['zipcode'][0].'</td></tr>';
        } ?>
<?php } ?>

</table>