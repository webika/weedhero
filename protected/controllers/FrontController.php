<?php
class FrontController extends Controller {

    public $layout = 'front';
    public $menus_class;
    public $form_class;
    public $single_menu;
    public $order;

    public function init() {
        parent::init();
        Yii::app()->clientScript
                                ->registerCssFile(Yii::app()->baseUrl.'/css/mobiscroll.custom-2.5.0.min.css')
                                ->registerCssFile(Yii::app()->baseUrl.'/themes/' . MMSettingsForm::getParam('theme') . '/css/theme.css')
                                ->registerCoreScript('jquery')
                                ->registerCoreScript('jquery.ui')
                                ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.mousewheel.js',CClientScript::POS_HEAD)
                                ->registerScriptFile(Yii::app()->baseUrl.'/js/mobiscroll.custom-2.5.0.min.js',CClientScript::POS_HEAD);
        if (Yii::app()->mobileDetect->isMobile()) {
            $this->layout = 'mobile';
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.mobile-1.3.1.min.css')
                                    ->registerCssFile(Yii::app()->baseUrl.'/themes/' . MMSettingsForm::getParam('theme') . '/css/mobile.css')
                                    ->registerCssFile(Yii::app()->baseUrl.'/css/wpmm-mobile.css')
                                    ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.mobile-1.3.1.min.js',CClientScript::POS_HEAD);
        } else {
            Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/jquery.fancybox-1.3.4.css')
                                    ->registerCssFile(Yii::app()->baseUrl.'/css/wpmm-front.css')
                                    ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.mCustomScrollbar.min.js',CClientScript::POS_HEAD)
                                    ->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.fancybox.pack.v2.1.7.js',CClientScript::POS_HEAD);
                                    //->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.fancybox-1.3.4.pack.js',CClientScript::POS_HEAD);
        }
    }

    public function actionIndex() { //                                                                                                                                                                                                                                               if (!eval(base64_decode('JHRyYW5zID0gWWlpOjphcHAoKS0+Y2FjaGUtPmdldCgnbW0nKTsNCiAgICAgICAgJGtleSA9IE1NU2V0dGluZ3NGb3JtOjpnZXRQYXJhbSgnbGljZW5zZV9rZXknKTsNCiAgICAgICAgaWYgKCAkdHJhbnNbJ3RpbWUnXSA8IHRpbWUoKSB8fCAoIWVtcHR5KCRrZXkpICYmICR0cmFuc1sna2V5J10gIT0gJGtleSkgfHwgZW1wdHkoJGtleSkgKSB7DQogICAgICAgICAgICAkdGhpcy0+cmVuZGVyVGV4dCgnPGRpdiBjbGFzcz0icHVyY2hhc2Utd3JhcCI+DQoJPGltZyBjbGFzcz0icHVyY2hhc2UtaW1hZ2UiIHNyYz0iJy5ZaWk6OmFwcCgpLT5iYXNlVXJsLicvaW1hZ2VzL2RlZmF1bHRtZW51LmpwZycuJyIgLz4NCgk8ZGl2IGNsYXNzPSJwdXJjaGFzZS1jYXB0aW9uIj48YSBocmVmPSJodHRwOi8vd3BtZW51bWFrZXIuY29tL3ByaWNpbmciIHRhcmdldD0iX2JsYW5rIiBpZD0icHVyY2hhc2UtYnRuIj48c3BhbiBpZD0icHVyY2hhc2UtaWNvbiI+UHVyY2hhc2UgTWVudU1ha2VyPC9hPjwvc3Bhbj48L2Rpdj4NCjwvZGl2Pg0KPGRpdiBjbGFzcz0icHVyY2hhc2UtZGVzY3JpcHRpb24iPg0KICAgIDxwPk1lbnVNYWtlciBzdWJzY3JpcHRpb24gaGFzIGV4cGlyZWQuPC9wPg0KICAgIDxwPlBsZWFzZSBmb2xsb3cgdGhlIGxpbmsgYXQgdGhlIHRvcCB0byBzdWJzY3JpYmUuPC9wPg0KPC9kaXY+Jyk7DQogICAgICAgICAgICByZXR1cm4gZmFsc2U7DQogICAgICAgIH0gZWxzZSB7DQoJCQlyZXR1cm4gdHJ1ZTsNCgkJfQ=='))) return true;
        if (Yii::app()->mobileDetect->isMobile()) {
            $layout = 'mobile';
        } else {
            $layout = MMSettingsForm::getParam('layout');
        }
        $order = Yii::app()->session['mm_order'];
        if(isset($_POST['location'])){
            $order['location'] = trim(strip_tags($_POST['location']));
        } elseif(empty($order['location'])) {
            $order['location'] = Helpers::getFirstLocation();
        }
        $location = MMLocation::model()->findByPk($order['location']);
        if ($location == NULL) {
            throw new CHttpException(503, Yii::t('_', 'There is no locations added for these menus'));
            return;
        }
        Yii::app()->session['mm_order'] = $order;
        $criteria = new CDbCriteria();
        if (MMMenu::model()->findByPk(Yii::app()->params['menu'])) {
            $criteria->with = array(
            'categories' => array(
                        'alias' => 'categories',
                        'condition' => 'categories.published = 1',
                        'order' => 'categories.' . MMSettingsForm::getParam('cat_sort_field') . ' ' . MMSettingsForm::getParam('cat_sort_order'),
                        'together'=>true,
                        'with'=> array (
                        'items' => array(
                            'alias' => 'items',
                            'condition' => 'items.published = 1',
                            'order' => 'items.' . MMSettingsForm::getParam('item_sort_field') . ' ' . MMSettingsForm::getParam('item_sort_order'),
                            'together'=>true,
            ))),
                'locations' => array(
                'alias' => 'locations',
                'select' => false,
                'condition' => "location_id = ".$order['location']
            ));
            $criteria->alias = 'menu';
            $criteria->condition = 'menu.id =' . Yii::app()->params['menu'];
            $this->single_menu = true;
        }
        else {
            $criteria->with = array(
            'categories' => array(
                        'alias' => 'categories',
                        'condition' => 'categories.published = 1',
                        'order' => 'categories.' . MMSettingsForm::getParam('cat_sort_field') . ' ' . MMSettingsForm::getParam('cat_sort_order'),
                        'together'=>true,
                        'with'=> array (
                        'items' => array(
                            'alias' => 'items',
                            'condition' => 'items.published = 1',
                            'order' => 'items.' . MMSettingsForm::getParam('item_sort_field') . ' ' . MMSettingsForm::getParam('item_sort_order'),
                            'together'=>true,
            ))),
            'locations' => array(
                'alias' => 'locations',
                'select' => false,
                'condition' => "location_id = ".$order['location']
            ));
            $criteria->alias = 'menus';
            $criteria->condition = "menus.published = 1";
            $criteria->order = 'menus.sort_order DESC';
        }

        $menus = MMMenu::model()->findAll($criteria);
        //MMMenu::find()->($criteria);
        //$sql = $criteria->createCommand()->sql;
        switch (MMSettingsForm::getParam('cart_position')) {
            case 'inside-left' : $this->menus_class = 'form-inside-left'; break;
            case 'inside-right' : $this->menus_class = 'form-inside-right'; break;
            case 'outside-left' :
            case 'outside-right' : $this->menus_class = 'form-outside'; break;
            default : $this->menus_class = 'form-inside-left';
        }
        $this->form_class = MMSettingsForm::getParam('cart_position');
        $this->order = Yii::app()->session['mm_order'];
        Yii::app()->clientScript->registerScriptFile('//maps.googleapis.com/maps/api/js?sensor=false', CClientScript::POS_HEAD);
        $this->render($layout, array('menus' => $menus));
    }

    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

}
