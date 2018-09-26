<?php
/**
 * TbRelationalColumn class
 *
 * Displays a clickable column that will make an ajax request and display its resulting data
 * into a new row.
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * Date: 9/25/12
 * Time: 10:05 PM
 */
Yii::import('bootstrap.widgets.TbRelationalColumn');

class TbaRelationalColumn extends TbRelationalColumn
{

    public $ajaxaction;

	public function registerClientScript()
	{
		Yii::app()->bootstrap->registerAssetCss('bootstrap-relational.css');

		$cs = Yii::app()->getClientScript();

		if($this->afterAjaxUpdate!==null)
		{
			if((!$this->afterAjaxUpdate instanceof CJavaScriptExpression) && strpos($this->afterAjaxUpdate,'js:')!==0)
			{
				$this->afterAjaxUpdate=new CJavaScriptExpression($this->afterAjaxUpdate);
			}
			else
			{
				$this->afterAjaxUpdate=$this->afterAjaxUpdate;
			}
		}
		else
			$this->afterAjaxUpdate = 'js:jQuery.noop';

		$this->ajaxErrorMessage = CHtml::encode($this->ajaxErrorMessage);
		$afterAjaxUpdate = CJavaScript::encode($this->afterAjaxUpdate);
		$span = count($this->grid->columns);
		$loadingPic = CHtml::image(Yii::app()->bootstrap->getAssetsUrl().'/img/loading.gif');
		$cache = $this->cacheData? 'true':'false';
		$data = !empty($this->submitData) && is_array($this->submitData)? $this->submitData : 'js:{}';
		$data = CJavascript::encode($data);

		$js =<<<EOD
jQuery(document).on('click','.{$this->cssClass}', function(){
	var span = $span;
	var that = jQuery(this);
	var status = that.data('status');
	var rowid = that.data('rowid');
	var tr = jQuery('#relatedinfo'+rowid);
	var parent = that.parents('tr');
	var afterAjaxUpdate = {$afterAjaxUpdate};

	if(status && status=='on'){return}
	that.data('status','on');

	if(tr.length && !tr.is(':visible') && {$cache})
	{
		tr.slideDown();
		that.data('status','off');
		return;
	}else if(tr.length && tr.is(':visible'))
	{
		tr.slideUp();
		that.data('status','off');
		return;
	}
	if(tr.length)
	{
		tr.find('td').html('{$loadingPic}');
		if(!tr.is(':visible')){
			tr.slideDown();
		}
	}
	else
	{
		var td = jQuery('<td/>').html('{$loadingPic}').attr({'colspan':$span});
		tr = jQuery('<tr/>').prop({'id':'relatedinfo'+rowid}).append(td);
		/* we need to maintain zebra styles :) */
		var fake = jQuery('<tr class="hide"/>').append(jQuery('<td/>').attr({'colspan':$span}));
		parent.after(tr);
		tr.after(fake);
	}
	var data = jQuery.extend({$data}, {id:rowid});
	jQuery.ajax({
		url: '{$this->url}',
		data: data,
		success: function(data){
		    tr.find('td').html(data);
		    that.data('status','off');
		    if(jQuery.isFunction(afterAjaxUpdate))
		    {
		        afterAjaxUpdate(tr,rowid,data);
		    }
		},
		error: function()
		{
			tr.find('td').html('{$this->ajaxErrorMessage}');
			that.data('status','off');
		}
	});
});
EOD;
		$cs->registerScript(__CLASS__.'#'.$this->id, $js);
	}
}