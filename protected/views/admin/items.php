<div class="mm-container">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Create new Item',
        'size' => 'large',
        'icon' => 'briefcase',
        'url' => Yii::app()->createUrl('admin/createitem'),
    )) ?>
    <?php
    $str_js = "jQuery('[rel=\"popover\"]').popover({ html:true }); jQuery('[rel=\"tooltip\"]').tooltip();";
    Yii::app()->clientScript->registerScript('sortable-project', $str_js);
    $script = <<<SCRIPT
var tdocument = jQuery(document);
jQuery('.img_popup').each(function(){
    var tthis = jQuery(this);
    // make sure that element is really an image
    if (! tthis.is('img')) return false;

    var url = tthis[0].src.replace('thumb_', '');
    var isrc = url , ibox = null;

    if (! isrc) return false;
    ibox = jQuery('<img />')
            .attr('class', 'simpleimagehover__shidivbox__')
            .css({
                display: 'none',
                zIndex : 99,
                MozBoxShadow: '0 0 0.5em #000',
                WebkitBoxShadow: '0 0 0.5em #000',
                boxShadow: '0 0 0.5em #000',
                position: 'absolute',
                MozBorderRadius : '10px',
                WebkitBorderRadius : '10px',
                borderRadius : '10px'
            })
            .attr('src', isrc)
            .appendTo(document.body);

    tthis.on('mouseenter mousemove', function(e) {
        jQuery('.simpleimagehover__shidivbox__').hide();

        var left = e.pageX + 5,
            top = e.pageY + 5,
            ww = window.innerWidth,
            wh = window.innerHeight,
            w = ibox.width(),
            h = ibox.height(),
            overflowedW = 0,
            overflowedH = 0;

        // calucation to show element avoiding scrollbars as much as possible - not a great method though
        if ((left + w + tdocument.scrollLeft()) > ww)
        {
            overflowedW = ww - (left + w + tdocument.scrollLeft());
            if (overflowedW < 0)
            {
               left -= Math.abs(overflowedW);
            }
        }

        // 25 is just a constant I picked arbitrarily to compensate pre-existing scrollbar if the page itself is too long
        left -= 220;
        left = left < tdocument.scrollLeft() ? tdocument.scrollLeft() : left;

        // if it's still overflowing because of the size, resize it

            ibox.width(400);



        if (top + h > wh + tdocument.scrollTop())
        {
            overflowedH = top + h - wh - tdocument.scrollTop();
            if (overflowedH > 0)
            {
                top -= overflowedH;
            }
        }

        top = top < tdocument.scrollTop() ? tdocument.scrollTop() : top;
        ibox.css({
            top: top,
            left: left
        });

        ibox.show();
    });



});
jQuery('.simpleimagehover__shidivbox__').mouseleave(function(){
        jQuery('.simpleimagehover__shidivbox__').hide();
    });

    tdocument.click(function(e){
        jQuery('.simpleimagehover__shidivbox__').hide();
    });

    tdocument.mousemove(function(e){
        if (e.target.nodeName.toLowerCase() === 'img') {
            return false;
        }

        jQuery('.simpleimagehover__shidivbox__').hide();
    });
jQuery('[rel=\"popover\"]').popover({ html:true }); jQuery('[rel=\"tooltip\"]').tooltip();
SCRIPT;
    $this->widget('ext.bootstrap.widgets.TbExtendedGridView', array(
        'type' => 'striped bordered condensed',
        'id' => 'Items-grid',
        'enablePagination' => true,
        'afterAjaxUpdate' => 'js:function(id, data) {' . $script . '}',
        'beforeAjaxUpdate' => "js:function(id, data){ jQuery('[rel=\"popover\"]').popover('destroy'); jQuery('[rel=\"tooltip\"]').tooltip('destroy'); }",
        'dataProvider' => $model->search(),
        'filter' => $model,
        'template' => '{items}{pager}',
        'columns' => array(
            array(
                'name' => 'id',
                'header' => '#',
                'htmlOptions' => array(
                    'style' => 'width: 40px; vertical-align:middle; text-align: center;')),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'CHtml::link(CHtml::encode($data->name), $data->url)',
                'htmlOptions' => array(
                    'style' => 'vertical-align:middle')),
            array(
                'name' => 'price',
                'value' => '$data->price',
                'htmlOptions' => array(
                    'style' => 'width: 90px; vertical-align:middle; text-align: center;')
            ),
            array(
                'name' => 'type',
                'value' => '$data->itemtype',
                'htmlOptions' => array(
                    'style' => 'width: 100px; vertical-align:middle; text-align: center;'),
            ),
            array(
                'name' => 'category.name',
                'type' => 'raw',
                'header' => 'Categories',
                'value' => '$data->rellink',
                'filter' => CHtml::activedropDownList($model->searchCategory, 'name', CHtml::listData(MMCategory::model()->findAll(array('order'=>'name')),'name','name'), array('empty'=>'Select','style'=>'width:155px;') ),
                'sortable' => false,
                'htmlOptions' => array(
                    'style' => 'width: 155px; vertical-align:middle; text-align: center;'),
            ),
            array(
                'name' => 'image',
                'type' => 'raw',
                'header' => 'Image',
                'value' => '(!empty($data->image))?CHtml::image(Yii::app()->baseUrl . "/" .MM_UPLOADS_URL . "/thumb_".$data->image,"",array("style"=>"height:35px;", "class" => "img_popup")): CHtml::image(Yii::app()->baseUrl . "/images/noimage2.jpg","",array("style"=>"height:35px;"))',
                'filter' => '',
                'sortable' => false,
                'htmlOptions' => array(
                    'style' => 'width: 40px; vertical-align:middle; text-align: center;'),
            ),
            array(
                'name' => 'published',
                'header' => 'Published',
                'htmlOptions' => array(
                'style' => 'width: 65px; vertical-align:middle; text-align: center;'),
                'class' => 'FlagColumn',
                'callbackUrl' => array('flagitem'),
            ),
            array(
                'class' => 'application.components.boot.TbaButtonColumn',
                'template' => '{update}{delete}',
                'htmlOptions' => array(
                'style' => 'width: 55px; vertical-align:middle; text-align: center;'),
                'updateButtonUrl' => '$data->url',
                'deleteButtonUrl' => '$data->delurl',
                'deleteButtonLabel' => 'Delete',
                'updateButtonLabel' => 'Edit',
                'deleteConfirmation' => 'Are you sure you want to delete this item?',
            ),
        ),
    ));
    Yii::app()->clientScript->registerScript('popup-image', $script);
    ?>
</div>
<img src="" class="popimg"/>