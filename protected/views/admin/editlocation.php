<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'user-form',
    'type'=>'vertical',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
      'validateOnSubmit' => true,
      'validateOnChange' => false,
    ),
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
)) ?>
<?php
$worktime = array();
$timescale =  Helpers::getHours24();
$map = array();
if($iscreate){
  $map = array(
      'map-zoom' => '4',
      'map-center-lat' => '37.910342',
      'map-center-long' => '-464.282758',
      'mapmark-center-lat' => '37.910342',
      'mapmark-center-long' => '-464.282758',
      'enable_map' => '0',
  );
  $worktime[0]['active'] = 1;
  $worktime[0]['from']='0';
  $worktime[0]['to']='48';
  $worktime[1]['active'] = 1;
  $worktime[1]['from']='0';
  $worktime[1]['to']='48';
  $worktime[2]['active'] = 1;
  $worktime[2]['from']='0';
  $worktime[2]['to']='48';
  $worktime[3]['active'] = 1;
  $worktime[3]['from']='0';
  $worktime[3]['to']='48';
  $worktime[4]['active'] = 1;
  $worktime[4]['from']='0';
  $worktime[4]['to']='48';
  $worktime[5]['active'] = 1;
  $worktime[5]['from']='0';
  $worktime[5]['to']='48';
  $worktime[6]['active'] = 1;
  $worktime[6]['from']='0';
  $worktime[6]['to']='48';

} else {
  $worktime = unserialize($model->l_calendar);
  $map = unserialize($model->gmap);
}
Yii::app()->clientScript->registerScriptFile('//maps.googleapis.com/maps/api/js?sensor=false', CClientScript::POS_HEAD);
                        Yii::app()->clientScript->registerScript(
                            'apply-save actions',
                            "jQuery('#apply_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','true');
                            });
                            jQuery('#save_submit').click(function() {
                               jQuery('#form_apply_ckeck').attr('value','false');
                            });
");
                        ?>
<script>
    var hours =[  <?php $temp_hour = Helpers::getHours24();
        foreach ($temp_hour as $fig_hour){
            echo '"'.$fig_hour.'", ';
        }
    ?>  ];
    function sliderTohrs(slideval){
            return hours[slideval];
    }
    function cutindexSlide(slideval){
            return slideval;
    }
</script>
<legend>
    <div style="float:left"><?php if($iscreate == false) { ?> <?php echo Yii::t('_', 'Update Location') ?> - <?php echo $model->name; } else { echo Yii::t('_', 'Add Location'); } ?></div>
    <div align="right" style="margin-right: 10px">
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'ok white', 'type' => 'primary', 'label' => 'Save Changes', 'htmlOptions' => array('name' => 'wpmm-items', 'id' => 'save_submit',))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit', 'icon' => 'ok white', 'type' => 'success', 'label' => 'Apply Changes', 'htmlOptions' => array('name' => 'wpmm-items-apply', 'id' => 'apply_submit'))); ?>
        <?php $this->widget('bootstrap.widgets.TbButton', array('url' => Yii::app()->createUrl('admin/locations'), 'label' => 'Close', 'type' => 'danger', 'icon' => 'remove white')); ?>
    </div>
</legend>
        <?php if (!$iscreate) {
                 echo Chtml::hiddenField('ajax_validation', $model->id );
            } else {
                echo Chtml::hiddenField('ajax_validation', '0' );
                } ?>
        <?php echo Chtml::hiddenField('action', 'admin'); ?>
        <?php echo Chtml::hiddenField('model_name', 'MMLocation'); ?>
        <?php echo Chtml::hiddenField('apply', 'false',array('id'=>'form_apply_ckeck')); ?>
<div class="mm-container">
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
	'title' => Yii::t('_', 'General'),
	'headerIcon' => 'icon-pencil',
	'htmlOptions' => array('class'=>'bootstrap-widget-table')
));?>
    <div class="inrfrm">
        <table border="0" width="100%">
            <tr>
                <td valign="top">
                  <div style="padding: 5px; float: left">
                      <?php echo $form->textFieldRow($model, 'name', array('prepend' => '<i class="icon-info-sign"></i>')); ?>
                      <?php echo $form->textFieldRow($model, 'phone', array('prepend' => '<i class="icon-info-sign"></i>')); ?>
                  </div>
                  <div style="padding: 5px; float: left">
                      <?php echo $form->textFieldRow($model, 'l_email', array('prepend' => '<i class="icon-envelope"></i>')); ?>
                      <?php echo $form->textFieldRow($model, 'fax', array('prepend' => '<i class="icon-info-sign"></i>')); ?>
                  </div>
                  <div style="padding: 5px; float: left">
                      <?php echo $form->dropDownListRow($model, 'timezone', Helpers::getTimezone()); ?>
                      <?php echo $form->textAreaRow($model, 'address', array('class'=>'span3', 'rows'=>2)); ?>
                  </div>
                  <div style="padding: 5px; float: left">
                    <?php echo CHtml::checkBox("MMLocation[map][enable_map]", (bool)$map['enable_map'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Enable Map') ?>
                  </div>
                </td>
                <td width="400" rowspan="2">
                    <div id="wpmm-google-map" style="width: 400px; height: 400px; margin-top: 20px; margin-bottom: 20px;"></div>
                </td>
            </tr>
            <tr>
                <td  style="padding: 5px;" valign="top">
                    <table border="0">
                            <tr>
                                <td valign="bottom" width="200">
                                    <?php echo '<label>Zip code: </label>' . CHtml::textField("zipcode",'', array('id' => 'addzipcode')) ?>
                                </td>
                                <td valign="bottom" width="120">
                                    <?php $this->widget('bootstrap.widgets.TbButton', array(
                                        'label' => 'Add Zipcode',
                                        'url' => '',
                                        'id' => 'zipsubmit',
                                        'htmlOptions' => array('style' => 'margin: 0 0 10px 5px;'),
                                    )) ?>
                                </td>
                            </tr>
                        </table>
                        <?php
                        Yii::app()->clientScript->registerScript(
                            'add-zipcode-action',
                            "jQuery('#zipsubmit').click(function() {
                                var listofzips = [];
                                $('#zipcodetablecontainer .hiddenzips').each(function(){
                                    listofzips.push($(this).val());
                                });
                                jQuery.ajax({
                                    'type':'POST',
                                    'data':{
                                        'action' : 'admin',
                                        'ziplist' : listofzips,
                                        'addzipcode' : jQuery('#addzipcode').val(),
                                        '" . Yii::app()->request->csrfTokenName . "':'" . Yii::app()->request->csrfToken . "'
                                    },
                                    'success':function( data ) {
                                        // handle return data
                                        jQuery('#zipcodetablecontainer').empty();
                                        jQuery('#zipcodetablecontainer').append(data);
                                        jQuery('#addzipcode').val('');
                                    },
                                    'error':function(data){  },
                                    'url':'" . Yii::app()->createUrl('admin/addzipcodeloc') . "',
                                    'cache':false
                                });
                            });");
                        ?>
        <hr>
                    <label>Allowed zip-codes</label>
                    <div id="zipcodetablecontainer">
                    <?php $this->renderPartial('_ziptableloc', array('ziplist' => unserialize($model->zip_list))); ?>
                    </div>
                </td>
            </tr>
        </table>
        </div>
     <?php $this->endWidget();?>
                                <input type="hidden" name="MMLocation[map][map-zoom]" id="mm-map-zoom" value="<?php echo $map['map-zoom'] ?>">
                                <input type="hidden" name="MMLocation[map][map-center-lat]" id="mm-center-lat" value="<?php echo $map['map-center-lat'] ?>">
                                <input type="hidden" name="MMLocation[map][map-center-long]" id="mm-center-long" value="<?php echo $map['map-center-long'] ?>">
                                <input type="hidden" name="MMLocation[map][mapmark-center-lat]" id="mmk-center-lat" value="<?php echo $map['mapmark-center-lat'] ?>">
                                <input type="hidden" name="MMLocation[map][mapmark-center-long]" id="mmk-center-long" value="<?php echo $map['mapmark-center-long'] ?>">
    <?php $this->beginWidget('bootstrap.widgets.TbBox', array(
	'title' => Yii::t('_', 'Location Working Hours'),
	'headerIcon' => 'icon-ok-circle',
	'htmlOptions' => array('class'=>'bootstrap-widget-table', 'id' => 'location-hours-box')
));?>
    <div class="inrfrm">
    <table border="0" width="100%">
            <tr>
                <th width="100">
                    <b><?php echo Yii::t('_', 'Day') ?></b>
                </th>
                <th>
                    <b><?php echo Yii::t('_', 'Working Hours') ?></b>
                </th>
            </tr>
            <tr>
                <td valign="middle">
                    <?php echo CHtml::checkBox("MMLocation[work_time][0][active]", (bool)$worktime[0]['active'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Sunday') ?></td>
                <td valign="top">
                    <span id="amount1" class="hourslabel"><?php echo $timescale[$worktime[0]['from']].' - '.$timescale[$worktime[0]['to']] ?></span>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options'=>array(
                            'min'=>0,
                            'max'=>48,
                            'step' => 1,
                            'range'=>true,
                            'slide'=>'js:function( event, ui ) {
                                    if(ui.values[1] - ui.values[0] > 48){
                                        return false
                                        } else {
                                        $( "#amount1" ).html( sliderTohrs(ui.values[ 0 ]) + " - " + sliderTohrs(ui.values[ 1 ]) );
                                        $( "#MMLocation_work_time_0_from" ).val( cutindexSlide(ui.values[ 0 ]) );
                                        $( "#MMLocation_work_time_0_to" ).val( cutindexSlide(ui.values[ 1 ]) );
                                    }
                                }',
                            'values'=>array($worktime[0]['from'], $worktime[0]['to'])
                        ),
                    ));
                    ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][0][from]",$worktime[0]['from']) ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][0][to]",$worktime[0]['to']) ?>
                    </td>
            </tr>
            <tr>
                <td valign="middle">
                    <?php echo CHtml::checkBox("MMLocation[work_time][1][active]", (bool)$worktime[1]['active'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Monday') ?></td>
                <td valign="top">
                 <span id="amount2" class="hourslabel"><?php echo $timescale[$worktime[1]['from']].' - '.$timescale[$worktime[1]['to']] ?></span>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options'=>array(
                            'min'=>0,
                            'max'=>48,
                            'step' => 1,
                            'range'=>true,
                            'slide'=>'js:function( event, ui ) {
                                    if(ui.values[1] - ui.values[0] > 48){
                                        return false
                                        } else {
                                        $( "#amount2" ).html( sliderTohrs(ui.values[ 0 ]) + " - " + sliderTohrs(ui.values[ 1 ]) );
                                        $( "#MMLocation_work_time_1_from" ).val( cutindexSlide(ui.values[ 0 ]) );
                                        $( "#MMLocation_work_time_1_to" ).val( cutindexSlide(ui.values[ 1 ]) );
                                    }
                                }',
                            'values'=>array($worktime[1]['from'], $worktime[1]['to'])
                        ),
                    ));
                    ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][1][from]",$worktime[1]['from']) ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][1][to]",$worktime[1]['to']) ?>
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    <?php echo CHtml::checkBox("MMLocation[work_time][2][active]", (bool)$worktime[2]['active'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Tuesday') ?></td>
                <td valign="top">
                    <span id="amount3" class="hourslabel"><?php echo $timescale[$worktime[2]['from']].' - '.$timescale[$worktime[2]['to']] ?></span>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options'=>array(
                            'min'=>0,
                            'max'=>48,
                            'step' => 1,
                            'range'=>true,
                            'slide'=>'js:function( event, ui ) {
                                    if(ui.values[1] - ui.values[0] > 48){
                                        return false
                                        } else {
                                        $( "#amount3" ).html( sliderTohrs(ui.values[ 0 ]) + " - " + sliderTohrs(ui.values[ 1 ]) );
                                        $( "#MMLocation_work_time_2_from" ).val( cutindexSlide(ui.values[ 0 ]) );
                                        $( "#MMLocation_work_time_2_to" ).val( cutindexSlide(ui.values[ 1 ]) );
                                    }
                                }',
                            'values'=>array($worktime[2]['from'], $worktime[2]['to'])
                        ),
                    ));
                    ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][2][from]",$worktime[2]['from']) ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][2][to]",$worktime[2]['to']) ?>
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    <?php echo CHtml::checkBox("MMLocation[work_time][3][active]", (bool)$worktime[3]['active'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Wednesday') ?></td>
                <td valign="top">
                    <span id="amount4" class="hourslabel"><?php echo $timescale[$worktime[3]['from']].' - '.$timescale[$worktime[3]['to']] ?></span>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options'=>array(
                            'min'=>0,
                            'max'=>48,
                            'step' => 1,
                            'range'=>true,
                            'slide'=>'js:function( event, ui ) {
                                    if(ui.values[1] - ui.values[0] > 48){
                                        return false
                                        } else {
                                        $( "#amount4" ).html( sliderTohrs(ui.values[ 0 ]) + " - " + sliderTohrs(ui.values[ 1 ]) );
                                        $( "#MMLocation_work_time_3_from" ).val( cutindexSlide(ui.values[ 0 ]) );
                                        $( "#MMLocation_work_time_3_to" ).val( cutindexSlide(ui.values[ 1 ]) );
                                    }
                                }',
                            'values'=>array($worktime[3]['from'], $worktime[3]['to'])
                        ),
                    ));
                    ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][3][from]",$worktime[3]['from']) ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][3][to]",$worktime[3]['to']) ?>
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    <?php echo CHtml::checkBox("MMLocation[work_time][4][active]", (bool)$worktime[4]['active'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Thursday') ?></td>
                <td valign="top">
                    <span id="amount5" class="hourslabel"><?php echo $timescale[$worktime[4]['from']].' - '.$timescale[$worktime[4]['to']] ?></span>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options'=>array(
                            'min'=>0,
                            'max'=>48,
                            'step' => 1,
                            'range'=>true,
                            'slide'=>'js:function( event, ui ) {
                                    if(ui.values[1] - ui.values[0] > 48){
                                        return false
                                        } else {
                                        $( "#amount5" ).html( sliderTohrs(ui.values[ 0 ]) + " - " + sliderTohrs(ui.values[ 1 ]) );
                                        $( "#MMLocation_work_time_4_from" ).val( cutindexSlide(ui.values[ 0 ]) );
                                        $( "#MMLocation_work_time_4_to" ).val( cutindexSlide(ui.values[ 1 ]) );
                                    }
                                }',
                            'values'=>array($worktime[4]['from'], $worktime[4]['to'])
                        ),
                    ));
                    ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][4][from]",$worktime[4]['from']) ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][4][to]",$worktime[4]['to']) ?>
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    <?php echo CHtml::checkBox("MMLocation[work_time][5][active]", (bool)$worktime[5]['active'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Friday') ?></td>
                <td valign="top">
                    <span id="amount6" class="hourslabel"><?php echo $timescale[$worktime[5]['from']].' - '.$timescale[$worktime[5]['to']] ?></span>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options'=>array(
                            'min'=>0,
                            'max'=>48,
                            'step' => 1,
                            'range'=>true,
                            'slide'=>'js:function( event, ui ) {
                                    if(ui.values[1] - ui.values[0] > 48){
                                        return false
                                        } else {
                                        $( "#amount6" ).html( sliderTohrs(ui.values[ 0 ]) + " - " + sliderTohrs(ui.values[ 1 ]) );
                                        $( "#MMLocation_work_time_5_from" ).val( cutindexSlide(ui.values[ 0 ]) );
                                        $( "#MMLocation_work_time_5_to" ).val( cutindexSlide(ui.values[ 1 ]) );
                                    }
                                }',
                            'values'=>array($worktime[5]['from'], $worktime[5]['to'])
                        ),
                    ));
                    ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][5][from]",$worktime[5]['from']) ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][5][to]",$worktime[5]['to']) ?>
                </td>
            </tr>
            <tr>
                <td valign="middle">
                    <?php echo CHtml::checkBox("MMLocation[work_time][6][active]", (bool)$worktime[6]['active'], array('uncheckValue' => 0)); ?> <?php echo Yii::t('_', 'Saturday') ?></td>
                <td valign="top">
                    <span id="amount7" class="hourslabel"><?php echo $timescale[$worktime[6]['from']].' - '.$timescale[$worktime[6]['to']] ?></span>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiSlider', array(
                        'options'=>array(
                            'min'=>0,
                            'max'=>48,
                            'step' => 1,
                            'range'=>true,
                            'slide'=>'js:function( event, ui ) {
                                    if(ui.values[1] - ui.values[0] > 48){
                                        return false
                                        } else {
                                        $( "#amount7" ).html( sliderTohrs(ui.values[ 0 ]) + " - " + sliderTohrs(ui.values[ 1 ]) );
                                        $( "#MMLocation_work_time_6_from" ).val( cutindexSlide(ui.values[ 0 ]) );
                                        $( "#MMLocation_work_time_6_to" ).val( cutindexSlide(ui.values[ 1 ]) );
                                    }
                                }',
                            'values'=>array($worktime[6]['from'], $worktime[6]['to'])
                        ),
                    ));
                    ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][6][from]",$worktime[6]['from']) ?>
                        <?php echo CHtml::hiddenField("MMLocation[work_time][6][to]",$worktime[6]['to']) ?>
                </td>
            </tr>
        </table>
    </div>
     <?php $this->endWidget();?>
<?php $this->endWidget(); ?>

</div>
<script>
            jQuery(document).ready(function(){
                var mapOptions = {
                    center: new google.maps.LatLng(<?php echo $map['map-center-lat'] ?>, <?php echo $map['map-center-long'] ?>),
                    zoom: <?php echo $map['map-zoom'] ?>,
                    disableDefaultUI: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                var map = new google.maps.Map(document.getElementById("wpmm-google-map"),
                mapOptions);
                map.setOptions({draggable: true, zoomControl: true, scrollwheel: true, disableDoubleClickZoom: true});
                var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(<?php echo $map['mapmark-center-lat'] ?>,<?php echo $map['mapmark-center-long'] ?>),
                            map: map,
                            title:"<?php echo 'name'; ?>"
                        });
                google.maps.event.addListener(map, "click", function (e) {
                    jQuery('#mmk-center-lat').val(e.latLng.lat().toFixed(6));
                    jQuery('#mmk-center-long').val(e.latLng.lng().toFixed(6));
                    var newLatLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                    marker.setPosition(newLatLng);
                });
                google.maps.event.addListener(map, "center_changed", function (e) {
                        var a = map.getCenter();
                        jQuery('#mm-center-lat').val(a.lat().toFixed(6));
                        jQuery('#mm-center-long').val(a.lng().toFixed(6));
                });
                google.maps.event.addListener(map, "zoom_changed", function (e) {
                        jQuery('#mm-map-zoom').val(map.getZoom());
                });
            google.maps.event.trigger(map, 'resize');
            });
        </script>