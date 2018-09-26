<legend>License</legend>
<div class="mm-container">
    <?php
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'License',
        'headerIcon' => 'icon-time',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <div class="inrfrm">
        <table>
            <tr>
                <td>
                    <?php echo $form->textFieldRow($model, 'license_key', array('hint' => 'Enter license key to activate FULL VERSION!')) ?>
                </td>
                <td id="key-info" align="center">
                    <?php
                        echo $this->_res;
                        if (substr($this->_res, 3, 6) !== 'Active')
                            echo '<a href="http://wpmenumaker.com/pricing" target="_blank">Purchase WPMenuMaker</a>';
                    ?>
                </td>
                <td>
                    <p style="padding-left: 5px;">
                    <?php echo CHtml::image(Yii::app()->baseUrl . "/images/icons/ajax-loader.gif", "", array(
                        "style" => "height:16px; display:none",
                        'class' => 'ajax_loader_settings'
                            ))
                    ?>
                    </p>
                </td>
            </tr>
        </table>

    </div>
    <?php $this->endWidget() ?>
</div>