<?php
class TbaEditableColumn extends CGridColumn {

    public $name;
    public $sortable = true;
    public $filter;
    public $params;

    //for all types
    public $model = null;
    public $attribute = null;
    public $type = null;
    public $url = null;
    public $title = null;
    public $emptytext = null;
    public $text = null; //will be used as content
    public $value = null;
    public $placement = null;
    public $inputclass = null;
    public $autotext = null;

    //for text & textarea
    public $placeholder = null;

    //for select
    public $source = array();
    public $prepend = null;

    //for date
    public $format = null;
    public $viewformat = null;
    public $language = null;
    public $weekStart = null;
    public $startView = null;

    //methods
    public $validate = null;
    public $success = null;
    public $error = null;

    //events
    public $onInit = null;
    public $onUpdate = null;
    public $onRender = null;
    public $onShown = null;
    public $onHidden = null;

    //js options
    public $options = array();

    //html options
    public $htmlOptions = array();

    //weather to encode text on output
    public $encode = true;

    //if false text will not be editable, but will be rendered
    public $enabled = null;

    public function init() {
        parent::init();


        $this->title=$this->header;
        $this->attribute=$this->name;
        $this->enabled=true;

        if (!strlen($this->text) && $this->type != 'select' && $this->type != 'date') {
            $this->text = $this->model->getAttribute($this->attribute);
        }

        //if enabled not defined directly, set it to true only for safe attributes
        if($this->enabled === null) {
            $this->enabled = $this->model->isAttributeSafe($this->attribute);
        }

        //if not enabled --> just print text
        if (!$this->enabled) {
            return;
        }

        //language: use config's value if not defined directly
        if ($this->language === null && yii::app()->language) {
            $this->language = yii::app()->language;
        }

        //normalize url from array if needed
        $this->url = CHtml::normalizeUrl($this->url);

        //generate title from attribute label
        if ($this->title === null) {
            //todo: i18n here. Add messages folder to extension
            $this->title = (($this->type == 'select' || $this->type == 'date') ? Yii::t('booster', 'Select') : Yii::t('booster', 'Enter')) . ' ' . $this->model->getAttributeLabel($this->attribute);
        }

        $this->buildJsOptions();
        $this->registerAssets();
        $this->registerClientScript($this->id);

    }

    private function json_encodealt($a=false)
    {
        // Some basic debugging to ensure we have something returned
        if (is_null($a)) return 'null';
        if ($a === false) return 'false';
        if ($a === true) return 'true';
        if (is_scalar($a))
        {
            if (is_float($a))
            {
                // Always use '.' for floats.
                return floatval(str_replace(',', '.', strval($a)));
            }
            if (is_string($a))
            {
                static $jsonReplaces = array(array('\\', '/', "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
            }
            else
                return $a;
        }
        $isList = true;
        for ($i = 0, reset($a); true; $i++) {
            if (key($a) !== $i)
            {
                $isList = false;
                break;
            }
        }
        $result = array();
        if ($isList)
        {
            foreach ($a as $v) $result[] = $this->json_encodealt($v);
            return '[' . join(',', $result) . ']';
        }
        else
        {
            foreach ($a as $k => $v) $result[] = $this->json_encodealt($k).':'.$this->json_encodealt($v);
            return '{' . join(',', $result) . '}';
        }
    }

    public function buildJsOptions()
    {
        $options = array(
            'type'  => $this->type,
            'url'   => $this->url,
            'name'  => $this->attribute,
            'title' => CHtml::encode($this->title),
            'params' => $this->json_encodealt($this->params),
        );

        if ($this->emptytext) {
            $options['emptytext'] = $this->emptytext;
        }

        if ($this->placement) {
            $options['placement'] = $this->placement;
        }

        if ($this->inputclass) {
            $options['inputclass'] = $this->inputclass;
        }

        if ($this->autotext) {
            $options['autotext'] = $this->autotext;
        }

        switch ($this->type) {
            case 'text':
            case 'textarea':
                if ($this->placeholder) {
                    $options['placeholder'] = $this->placeholder;
                }
                break;
            case 'select':
                if ($this->source) {
                    $options['source'] = $this->source;
                }
                if ($this->prepend) {
                    $options['prepend'] = $this->prepend;
                }
                break;
            case 'date':
                if ($this->format) {
                    $options['format'] = $this->format;
                }
                if ($this->viewformat) {
                    $options['viewformat'] = $this->viewformat;
                }
                if ($this->language && substr($this->language, 0, 2) != 'en') {
                    $options['datepicker']['language'] = $this->language;
                }
                if ($this->weekStart !== null) {
                    $options['weekStart'] = $this->weekStart;
                }
                if ($this->startView !== null) {
                    $options['startView'] = $this->startView;
                }
                break;
        }

        //methods
        foreach(array('validate', 'success', 'error') as $event) {
            if($this->$event!==null) {
                $options[$event]=(strpos($this->$event, 'js:') !== 0 ? 'js:' : '') . $this->$event;
            }
        }

        //merging options
        $this->options = CMap::mergeArray($this->options, $options);
    }

    public function buildHtmlOptions()
    {
        //html options
        $htmlOptions = array(
            'href'      => '#',
            'rel'       => $this->getSelector(),
            'data-pk'   => $this->model->primaryKey,
        );

        //for select we need to define value directly
        if ($this->type == 'select') {
            $this->value = $this->model->getAttribute($this->attribute);
            $this->htmlOptions['data-value'] = $this->value;
        }

        //for date we use 'format' to put it into value (if text not defined)
        if ($this->type == 'date' && !strlen($this->text)) {
            $this->value = $this->model->getAttribute($this->attribute);

            //if date comes as object, format it to string
            if($this->value instanceOf DateTime) {
                /*
                * unfortunatly datepicker's format does not match Yii locale dateFormat,
                * we need replacements below to convert date correctly
                */
                $count = 0;
                $format = str_replace('MM', 'MMMM', $this->format, $count);
                if(!$count) $format = str_replace('M', 'MMM', $format, $count);
                if(!$count) $format = str_replace('m', 'M', $format);

                $this->value = Yii::app()->dateFormatter->format($format, $this->value->getTimestamp());
            }

            $this->htmlOptions['data-value'] = $this->value;
        }

        //merging options
        $this->htmlOptions = CMap::mergeArray($this->htmlOptions, $htmlOptions);
    }

    public function registerAssets()
    {
        //if bootstrap extension installed, but no js registered -> register it!
        if (($bootstrap = Yii::app()->getComponent('bootstrap')) && !$bootstrap->enableJS) {
            $bootstrap->registerCorePlugins(); //enable bootstrap js if needed
        }

	    $bootstrap->registerAssetCss('bootstrap-editable.css') ;
	    $bootstrap->registerAssetJs('bootstrap-editable' . (!YII_DEBUG ? '.min' : '') . '.js', CClientScript::POS_END);

        //include locale for datepicker
        if ($this->type == 'date' && $this->language && substr($this->language, 0, 2) != 'en') {

             $bootstrap->registerAssetJs('locales/bootstrap-datepicker.'. str_replace('_', '-', $this->language).'.js', CClientScript::POS_END);
        }
    }

    protected function renderDataCellContent($row, $data) {

        $this->model=$data;

        if ($this->type === null) {
            $this->type = 'text';
            //try detect type from metadata.
            if (array_key_exists($this->attribute, $this->model->tableSchema->columns)) {
                $dbType = $this->model->tableSchema->columns[$this->attribute]->dbType;
                if($dbType == 'date' || $dbType == 'datetime') $this->type = 'date';
                if(stripos($dbType, 'text') !== false) $this->type = 'textarea';
            }
        }

        $this->buildHtmlOptions();



        if($this->enabled) {

            $this->renderLink();
        } else {
            $this->renderText();
        }
    }

    public function renderLink()
    {
        echo CHtml::openTag('a', $this->htmlOptions);
        $this->name;
        echo CHtml::closeTag('a');
    }

    public function renderText()
    {
        $encodedText = $this->encode ? CHtml::encode($this->text) : $this->text;
        if($this->type == 'textarea') {
             $encodedText = preg_replace('/\r?\n/', '<br>', $encodedText);
        }
        echo $encodedText;
    }

    public function getSelector()
    {
        //return get_class($this->model) . '_' . $this->attribute . ($this->model->primaryKey ? '_' . $this->model->primaryKey : '_new');
        return $this->id;
    }

    public function registerClientScript($data)
    {
        $script = "jQuery('a[rel={$this->id}]')";

        //attach events
        foreach(array('init', 'update', 'render', 'shown', 'hidden') as $event) {
            $property = 'on'.ucfirst($event);
            if ($this->$property) {
                // CJavaScriptExpression appeared only in 1.1.11, will turn to it later
                //$event = ($this->onInit instanceof CJavaScriptExpression) ? $this->onInit : new CJavaScriptExpression($this->onInit);
                $eventJs = (strpos($this->$property, 'js:') !== 0 ? 'js:' : '') . $this->$property;
                $script .= "\n.on('".$event."', ".CJavaScript::encode($eventJs).")";
            }
        }

        //apply editable
        $options = CJavaScript::encode($this->options);
        $script .= ".editable($options);";
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $data, $script,null);
        $gridId = $this->grid->getId();
        $script1 = <<<SCRIPT
jQuery(document).bind('change', function(){
$script
});
SCRIPT;
        $cs->registerScript(__CLASS__ . $data . '_link', $script1);
        return $script;
    }

    protected function renderHeaderCellContent() {
        if ($this->grid->enableSorting && $this->sortable && $this->name !== null){
             echo $this->grid->dataProvider->getSort()->link($this->name, $this->header.'<span class="caret"></span>');
        }
        else if ($this->name !== null && $this->header === null) {
            if ($this->grid->dataProvider instanceof CActiveDataProvider)
                echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
            else
                echo CHtml::encode($this->name);
        }
        else
            parent::renderHeaderCellContent();
    }

    /**
     * Renders the filter cell.
    */
    public function renderFilterCell()
    {
        echo '<td><div class="filter-container">';
        if(is_array($this->filter)){
            echo CHtml::activeDropDownList($this->grid->dataProvider->model, $this->name, $this->filter);
        }
        else {
           echo CHtml::activeTextField($this->grid->dataProvider->model, $this->name);
        }
        echo '</div></td>';
    }
}
