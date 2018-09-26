<legend>Theme</legend>
<div class="mm-container">
    <table style="margin: 10px;" cellpadding="10" border="0">
        <tr>
            <td valign="top">
                <label>Theme file (*.zip)</label>
                <?php
                echo CHtml::activeFileField(
                        $model, 'file', array('style' => 'width:260px')
                )
                ?>
            </td>
            <td valign="bottom" width="200" align="center">
                <?php
                $this->widget('bootstrap.widgets.TbButton', array(
                    'type' => 'primary',
                    'buttonType' => 'submit',
                    'label' => 'Install Theme',
                    'htmlOptions' => array('name' => 'wpmm-save-settings')
                ))
                ?>
            </td>
        </tr>
    </table>

    <?php
    Yii::app()->clientScript->registerScript('sortable-project',
            "jQuery(document).ready(function() { jQuery('[rel=\"tooltip\"]').tooltip({ html:true });});");
    $this->beginWidget('bootstrap.widgets.TbBox', array(
        'title' => 'Themes',
        'headerIcon' => 'icon-th-list',
        'htmlOptions' => array('class' => 'bootstrap-widget-table')
    ))
    ?>
    <table class="table">
        <thead>
            <tr>
                <th width="30">#</th>
                <th width="20"></th>
                <th>Name</th>
                <th>Version</th>
                <th>Date</th>
                <th>Author</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($model->getThemesOptions() as $theme) {
                ?> <tr class="odd">
                    <?php
                    $cheked = 0;
                    if ($theme['name'] == $model->theme) {
                        $cheked = 1;
                        $tmp = "checked='checked'";
                    }
                    else
                        $tmp = '';
                    echo '<td>' . $i . '</td>';
                    echo "<td><input value='{$theme['name']}' {$tmp} name='MMSettingsForm[theme]' id='SettingsForm_theme_" . ($i - 1) . "' type='radio' /></td>";
                    echo '<td><a href="js:function(e){ return false; }" rel="tooltip" data-placement="top" data-title="<img src=\'' . $theme['thumb'] . '\' alt=\'' . $theme['fullname'] . '\' width=\'200\'/>">' . $theme['fullname'] . '</a></td>';
                    echo '<td>' . $theme['version'] . '</td>';
                    echo '<td>' . $theme['date'] . '</td>';
                    echo '<td>' . $theme['author'] . '</td>';
                    ?>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
    </table>
    <?php $this->endWidget() ?>
    <?php
    echo $form->dropDownListRow($model, 'layout',
        array(
            'vertical' => 'Vertical',
            'tabs' => 'Tabs'
        ),
        array(
            'id' => 'layout'
        )) ?>
    <div id="tabs-effect">
    <?php echo $form->dropDownListRow($model, 'tabs_effect',
        array(
            'fade' => 'Fade',
            'slide' => 'Slide',
            'scale' => 'Scale',
        )) ?>
    </div>
</div>
<script>
    if (jQuery("#layout").val() === 'tabs')
        jQuery("#tabs-effect").show();
    else
        jQuery("#tabs-effect").hide();
    jQuery("#layout").on('change', function(){
        if (this.value === 'tabs')
            jQuery("#tabs-effect").show();
        else
            jQuery("#tabs-effect").hide();
    });
</script>